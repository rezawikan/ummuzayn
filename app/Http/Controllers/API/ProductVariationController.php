<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\Product\ProductVariationResource;
use App\Http\Controllers\Controller;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class ProductVariationController extends Controller
{
    /**
   * List query scope
   * @param \Illuminate\Http\Request
   * @return Array
   */
    protected function scopes()
    {
        return [];
    }
  
    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\API\Product\ProductVariationResource
     */
    public function index(Request $request)
    {
        $variations = ProductVariation::latest()
        ->withScopes(
            $this->scopes()
        );

        if ($request->paginate == null || $request->paginate == 'true') {
            $variations = $variations->paginate(12)
            ->appends(
                $request->except('page')
            );
        } else {
            $variations = $variations->get();
        }

        return ProductVariationResource::collection($variations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return @return \App\Http\Resources\API\Product\ProductVariationResource
     */
    public function store(Request $request)
    {
        $variation = ProductVariation::create(
            $request->only([
                'product_id',
                'variation_type_id',
                'price',
                'base_price',
                'weight',
                'orderable'
          ])
        );

        return new ProductVariationResource($variation);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductVariation $variation
     * @return \Illuminate\Http\Response
     */
    public function show(ProductVariation $variation)
    {
        return new ProductVariationResource($variation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\ProductVariation $variation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductVariation $variation)
    {
        $variation->update(
            $request->all()
        );

        return response()->json([
          'data' => __('response.api.created', [
            'name' => 'product variation'
          ])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductVariation $variation
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductVariation $variation)
    {
        $variation->delete();

        return response()->json([
          'data' => __('response.api.deleted', [
            'name' => 'product variation'
          ])
        ], 200);
    }
}
