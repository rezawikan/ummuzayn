<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\Product\ProductImageResource;
use App\Http\Requests\ProductImageRequest;
use App\Scoping\Scopes\All\SizeScope;
use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{

  /**
   * List query scope
   * @param \Illuminate\Http\Request
   * @return Array
   */
    protected function scopes()
    {
        return [
            'size' => new SizeScope()
        ];
    }

    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\API\Product\ProductImageResource
     */
    public function index(Request $request)
    {
        $images = ProductImage::latest()
        ->withScopes(
            $this->scopes()
        );

        if ($request->paginate == null || $request->paginate == 'true') {
            $images = $images->paginate(12)
            ->appends(
                $request->except('page')
            );
        } else {
            $images = $images->get();
        }

        return ProductImageResource::collection($images);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProductImageRequest $request
     * @return \App\Http\Resources\API\Product\ProductImageResource
     */
    public function store(ProductImageRequest $request)
    {
        $image = ProductImage::create(
            $request->only([
            'product_id',
            'size',
            'location',
            'format'
          ])
        );

        return new ProductImageResource($image);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductImage $image
     * @return \App\Http\Resources\API\Product\ProductImageResource
     */
    public function show(ProductImage $image)
    {
        return new ProductImageResource($image);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProductImageRequest $request
     * @param  \App\Models\ProductImage $image
     * @return \Illuminate\Http\Response
     */
    public function update(ProductImageRequest $request, ProductImage $image)
    {
        $image->update(
            $request->all()
        );

        return response()->json([
          'data' => __('response.api.updated', [
            'name' => 'product image'
          ])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductImage $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductImage $image)
    {
        $image->delete();

        return response()->json([
          'data' => __('response.api.deleted', [
            'name' => 'product image'
          ])
        ], 200);
    }
}
