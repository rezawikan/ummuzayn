<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\Customer\CustomerResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
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
     * @return \App\Http\Resources\API\Customer\CustomerResource
     */
    public function index(Request $request)
    {
        $customers = Customer::Ordered('name')
        ->withScopes(
            $this->scopes()
        );

        if ($request->paginate == null || $request->paginate == 'true') {
            $customers = $customers->paginate(12)
            ->appends(
                $request->except('page')
            );
        } else {
            $customers = $customers->get();
        }

        $customers->load(['type']);

        return CustomerResource::collection($customers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\API\Customer\CustomerResource
     */
    public function store(Request $request)
    {
        $customer = Customer::create(
            array_merge($request->only([
                'customer_type_id',
                'customer_point_id',
                'name',
                'email',
                'phone'
              ]), [
                  'password' => Hash::make($request->password)
              ])
        );

        return new CustomerResource($customer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  \App\Models\Customer
     * @return \App\Http\Resources\API\Customer\CustomerResource
     */
    public function show(Customer $customer)
    {
        $customer->load(['customer_addresses']);
        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  \App\Models\Customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $customer->update(
            $request->all()
        );

        return response()->json([
          'data' => __('response.api.updated', [
            'name' => 'customer'
          ])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  \App\Models\Customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json([
          'data' => __('response.api.deleted', [
            'name' => 'customer'
          ])
        ], 200);
    }
}
