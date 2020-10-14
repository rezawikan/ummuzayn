<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\Product\ProductVariationTypeResource;
use App\Http\Controllers\Controller;
use App\Models\ProductVariationType;
use Illuminate\Http\Request;

class ProductVariationTypeController extends Controller
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
     * @return \App\Http\Resources\API\Product\ProductVariationTypeResource
     */
    public function index(Request $request)
    {
        $variationType = ProductVariationType::latest()
        ->withScopes(
            $this->scopes()
        );

        if ($request->paginate == null || $request->paginate == 'true') {
            $statuses = $statuses->paginate(12)
            ->appends(
                $request->except('page')
            );
        } else {
            $variationType = $variationType->get();
        }

        return ProductVariationTypeResource::collection($variationType);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \App\Http\Resources\API\Product\ProductVariationTypeResource
     */
    public function store(Request $request)
    {
        $variationType = ProductStatus::create(
            $request->only([
            'variation_type',
            'slug'
          ])
        );

        return new ProductVariationTypeResource($variationType);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductVariationType $variationType
     * @return \App\Http\Resources\API\Product\ProductVariationTypeResource
     */
    public function show(ProductVariationType $variationType)
    {
        return new ProductVariationTypeResource($variationType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\ProductVariationType $variationType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductVariationType $variationType)
    {
        $variationType->update(
            $request->all()
        );

        return response()->json([
          'data' => __('response.api.created', [
            'name' => 'product variation type'
          ])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ProductVariationType $variationType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductVariationType $variationType)
    {
        $variationType->delete();

        return response()->json([
          'data' => __('response.api.deleted', [
            'name' => 'product variation type'
          ])
        ], 200);
    }
}
