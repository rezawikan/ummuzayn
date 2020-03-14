<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\Customer\CustomerAddressResource;
use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;

class CustomerAddressController extends Controller
{

    /**
     * List query scope
     *
     * @return array
     */
    protected function scopes()
    {
        return [
        // 'name'    => new NameScope(),
      ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customerAddresses = CustomerAddress::Ordered('name')
        ->withScopes(
            $this->scopes()
        )
        ->paginate(12)
        ->appends(
            $request->except('page')
        );

        return CustomerAddressResource::collection($customerAddresses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\API\Customer\CustomerAddressResource
     */
    public function store(Request $request)
    {
        $customerAddress = CustomerAddress::create(
            $request->only([
            'customer_id',
            'subdistrict_id',
            'default',
            'name',
            'address',
            'phone'
          ])
        );

        return new CustomerAddressResource($customerAddress);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  \App\Models\CustomerAddress
     * @return \App\Http\Resources\API\Customer\CustomerAddressResource
     */
    public function show(CustomerAddress $customerAddress)
    {
        return new CustomerAddressResource($customerAddress);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int \App\Models\CustomerAddress
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerAddress $customerAddress)
    {
        $customerAddress->update(
            $request->all()
        );

        return response()->json([
          'data' => __('response.api.updated', [
            'name' => 'customer address'
          ])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int \App\Models\CustomerAddress
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerAddress $customerAddress)
    {
        $customerAddress->delete();

        return response()->json([
          'data' => __('response.api.deleted', [
            'name' => 'customer address'
          ])
        ], 200);
    }
}
