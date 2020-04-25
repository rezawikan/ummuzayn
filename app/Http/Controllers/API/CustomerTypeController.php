<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\Customer\CustomerTypeResource;
use App\Http\Controllers\Controller;
use App\Models\CustomerType;
use Illuminate\Http\Request;
use App\Scoping\Scopes\All\TypeScope;
use App\Http\Requests\CustomerTypeStoreRequest;
use App\Http\Requests\CustomerTypeUpdateRequest;

class CustomerTypeController extends Controller
{
    /**
     * List query scope
     *
     * @return array
     */
    protected function scopes()
    {
        return [
          'type'    => new TypeScope(),
        ];
    }

    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $types = CustomerType::Ordered('type')
        ->withScopes(
            $this->scopes()
        );

        if ($request->paginate == null || $request->paginate == 'true') {
            $types = $types->paginate(12)
            ->appends(
                $request->except('page')
            );
        } else {
            $types = $types->get();
        }

        return CustomerTypeResource::collection($types);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CustomerTypeStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerTypeStoreRequest $request)
    {
        $type = CustomerType::create(
            $request->only([
            'type',
            'slug'
          ])
        );

        return new CustomerTypeResource($type);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerType  $customerType
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerType $customerType)
    {
        return new CustomerTypeResource($customerType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CustomerTypeUpdateRequest  $request
     * @param  \App\Models\CustomerType  $customerType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerType $customerType)
    {
        $customerType->update(
            $request->all()
        );

        return response()->json([
          'data' => __('response.api.created', [
            'name' => 'customer type'
          ])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerType  $customerType
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerType $customerType)
    {
        $customerType->delete();

        return response()->json([
          'data' => __('response.api.deleted', [
            'name' => 'customer type'
          ])
        ], 200);
    }
}
