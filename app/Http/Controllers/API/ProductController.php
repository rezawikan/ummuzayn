<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\Product\ProductResource;
use App\Http\Controllers\Controller;
use App\Scoping\Scopes\All\NameScope;
use Illuminate\Http\Request;
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
          'status',
          'product_images',
          'product_variations.product_variation_type'
        ]);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
