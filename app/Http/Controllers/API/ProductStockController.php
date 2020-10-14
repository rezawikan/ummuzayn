<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\Product\ProductStockResource;
use App\Http\Controllers\Controller;
use App\Models\ProductStock;
use Illuminate\Http\Request;

class ProductStockController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\API\Product\ProductStockResource
     */
    public function index(Request $request)
    {
        $stocks = ProductStatus::latest();

        if ($request->paginate == null || $request->paginate == 'true') {
            $stocks = $stocks->paginate(12)
            ->appends(
                $request->except('page')
            );
        } else {
            $stocks = $stocks->get();
        }

        return ProductStockResource::collection($stocks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \App\Http\Resources\API\Product\ProductStockResource
     */
    public function store(Request $request)
    {
        $stock = ProductStock::create(
            $request->only([
                'product_variation_id',
                'quantity',
                'info'
            ])
        );

        return new ProductStockResource($stock);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductStock $stock
     * @return \App\Http\Resources\API\Product\ProductStockResource
     */
    public function show(ProductStock $stock)
    {
        return new ProductStockResource($stock);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\ProductStock $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductStock $stock)
    {
        $stock->update(
            $request->all()
        );

        return response()->json([
          'data' => __('response.api.created', [
            'name' => 'product stock'
          ])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductStock $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductStock $stock)
    {
        $stock->delete();

        return response()->json([
          'data' => __('response.api.deleted', [
            'name' => 'product stock'
          ])
        ], 200);
    }
}
