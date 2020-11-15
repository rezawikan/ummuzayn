<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\Product\ProductResource;
use App\Http\Requests\ProductStoreRequest;
use Illuminate\Support\Facades\Storage;
use App\Scoping\Scopes\All\NameScope;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\ProductVariation;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * List query scope
     *
     * @return array
     */
    protected function scopes()
    {
        return [
          'name'    => new NameScope(),
        ];
    }

    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\API\Product\ProductResource
     */
    public function index(Request $request)
    {
        $products = Product::Ordered('name')
        ->withScopes(
            $this->scopes()
        );

        if ($request->paginate == null || $request->paginate == 'true') {
            $products = $products->paginate(12)
            ->appends(
                $request->except('page')
            );
        } else {
            $products = $products->get();
        }

        $products->load([
          'product_status',
          'product_images',
          'product_variations.product_variation_type'
        ]);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProductStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $product = Product::create(
                $request->only([
                    'product_category_id',
                    'name',
                    'description',
                    'product_status_id'
                ])
            );

            // Handle Variations
            foreach ($request->variations as $key => $variation) {
                ProductVariation::create(
                    array_merge($variation, [
                        'product_id' => $product->id,
                        'orderable' => $key
                    ])
                );
            }

            // Handle files
            $files = $request->file('images');
            if ($request->hasFile('images')) {
                foreach ($files as $file) {
                    ProductImage::create([
                        'product_id' => 17,
                        'location' => Storage::putFile('photos', $file),
                        'format' => $file->getClientOriginalExtension(),
                        'size' => $file->getSize()
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'data' => __('response.api.created', [
                  'name' => 'product'
                ])
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'data' => __('response.api.error')
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load([
            'product_status',
            'product_images',
            'product_variations'
            ]);
            
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        DB::beginTransaction();

        try {
            $product->update(
                $request->only([
                'product_category_id',
                'name',
                'description',
                'product_status_id'
                ])
            );
            
            // Handle Variations
            $product_variations = $product->product_variations;
            $next_variations = [];
            $previous_variations = $product_variations->pluck('id')->toArray();
            $existing_variation = $variation['id'] != null ? true : false;
        
            foreach ($request->variations as $key => $variation) {
                if ($existing_variation) {
                    $update_variation = $product_variations->where('id', $variation['id']);
                    $update_variation->update($variation->except('id'));
                    array_push($next_variations, $update_variation->id);
                } else {
                    $new_variations = ProductVariation::create(
                        array_merge($variation, [
                        'product_id' => $product->id,
                        'orderable' => $key
                    ])
                    );
                    array_push($next_variations, $new_variations->id);
                }
            }

            $exclude = array_diff($next_variations, $previous_variations);
            $product_variations->whereIn('id', $exclude)->delete();

            
            // // Destroy Images
            // $destroy_image =  $request->destroy_image; // Array - list of id
            // if (count($destroy_image) > 0) {
            //     $destroy = $product->product_images->whereIn('id', $destroy_image->toArray());
            //     Storage::delete($destroy->pluck('location')->toArray());
            //     $destroy->delete();
            // }

            // // New Images and Update
            // if ($request->hasFile('images')) {
            //     $files = $request->file('images');
            //     foreach ($files as $key => $file) {
            //         if (condition) {
            //             # code...
            //         }
            //         ProductImage::create([
            //             'product_id' => $product->id,
            //             'location' => Storage::putFile('photos', $file),
            //             'format' => $file->getClientOriginalExtension(),
            //             'size' => $file->getSize(),
            //             'orderable' => $key
            //         ]);
            //     }
            // }

            // // Set Main Image
            // $main_image = $request->main_image;
            // foreach ($image_orderable as $order => $data) {
            //     $image = $product->product_images->where('id', $data['id']);
            //     $image->update($data->except('id'));
            // }

            DB::commit();

            return response()->json([
                'data' => __('response.api.created', [
                  'name' => 'product'
                ])
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'data' => __('response.api.error')
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();

        try {
            ProductVariation::where('product_id', $product->id)->delete();
            ProductImage::where('product_id', $product->id)->delete();
            $product->delete();

            DB::commit();

            return response()->json([
                'data' => __('response.api.deleted', [
                  'name' => 'product'
                ])
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'data' => __('response.api.error')
            ], 422);
        }
    }
}
