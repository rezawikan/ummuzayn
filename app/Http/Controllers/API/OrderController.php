<?php

namespace App\Http\Controllers\API;

use App\Models\Customer;
use App\Pattern\Cart\Cart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\OrderStoreRequest;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Request\OrderStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderStoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $customer = Customer::find($request->customer_id);
            $cartObject = new Cart($customer->id);
            $summaryOrder = $this->statusMerge($request, $cartObject);
        
            $order = $customer->orders()->create($summaryOrder);
            $variations = $this->createOrderVariations($cartObject);
            $order->product_variation_orders()->createMany($variations);
            $customer->cart()->detach();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Convert cart to order variations array
     *
     * @param  Array $data
     * @return Array
     */
    protected function createOrderVariations($cart)
    {
        return $cart->customer->cart->map(function ($value) {
            return [
                'product_variation_id'  => $value->id,
                'product_name' => $value->product->name,
                'product_description' => $value->product->description,
                'product_variation_type' => $value->product_variation_type->variation_type ?? null,
                'product_variation_name' => $value->variation_name,
                'weight' => $value->weight,
                'quantity' => $value->pivot->quantity,
                'price' => $value->price,
                'base_price' => $value->base_price
            ];
        })->toArray();
    }

    /**
     * Merge status with order summary
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Pattern\Cart\Cart $cart
     * @return Array
     */
    protected function statusMerge(Request $request, Cart $cart)
    {
        return array_merge([
            'status_id' => $request->status_id
        ], $cart->summary($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
