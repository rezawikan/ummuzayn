<?php

namespace App\Http\Controllers\API;

use App\Models\Customer;
use App\Pattern\Cart\Cart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\API\Cart\CartResource;
use App\Http\Resources\API\Cart\CartIndexResource;
use App\Http\Requests\CartStoreRequest;
use App\Http\Requests\CartUpdateRequest;
use App\Http\Requests\CartShowRequest;
use App\Models\ProductVariation;
use App\Http\Resources\API\Cart\CartProductVariationResource;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customers = Customer::paginate(12);
        $customers->load(['cart.product']);
        return CartIndexResource::collection($customers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CartStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CartStoreRequest $request)
    {
        $cart = new Cart($request->customer_id);
        $cart->add($request->products);
        $cart->customer->load(['cart.product', 'cart.product_variation_type']);
    
        return CartProductVariationResource::collection($cart->customer->cart->makeHidden([
            'pivot',
            'base_price',
            'created_at',
            'updated_at'
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function show(CartShowRequest $request, Customer $customer)
    {
        $cart = new Cart($customer);
        $cart->sync();
        $cart->customer->load(['cart.product', 'cart.product_variation_type']);
        
        return (new CartResource($cart->customer))->additional([
          'meta' => $cart->summary($request)
        ]);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \App\Http\Requests\CartUpdateRequest $request
    * @param  \App\Models\Customer $customer
    * @return \Illuminate\Http\Response
    */
    public function update(CartUpdateRequest $request, Customer $customer)
    {
        $cart = new Cart($customer);
        $cart->update($request->product_variation_id, $request->quantity);

        return response()->json([
          'data' => __('response.api.updated', [
            'name' => 'city'
          ])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer $customer
     * @param  \App\Models\ProductVariation $productVariation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer, ProductVariation $productVariation)
    {
        $cart = new Cart($customer);
        $cart->delete($productVariation->id);

        return response()->json([
            'data' => __('response.api.deleted', [
              'name' => 'cart item'
            ])
        ], 200);
    }
}
