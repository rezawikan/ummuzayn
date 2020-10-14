<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\Product\ProductStatusResource;
use App\Http\Requests\ProductStatusUpdateRequest;
use App\Http\Requests\ProductStatusStoreRequest;
use App\Http\Controllers\Controller;
use App\Models\ProductStatus;
use Illuminate\Http\Request;

class ProductStatusController extends Controller
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
     * @return \App\Http\Resources\API\Product\ProductStatusResource
     */
    public function index(Request $request)
    {
        $statuses = ProductStatus::latest()
        ->withScopes(
            $this->scopes()
        );

        if ($request->paginate == null || $request->paginate == 'true') {
            $statuses = $statuses->paginate(12)
            ->appends(
                $request->except('page')
            );
        } else {
            $statuses = $statuses->get();
        }

        return ProductStatusResource::collection($statuses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProductStatusStoreRequest $request
     * @return \App\Http\Resources\API\Product\ProductStatusResource
     */
    public function store(ProductStatusStoreRequest $request)
    {
        $status = ProductStatus::create(
            $request->only([
            'status',
            'slug'
          ])
        );

        return new ProductStatusResource($status);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductStatus $status
     * @return \App\Http\Resources\API\Product\ProductStatusResource
     */
    public function show(ProductStatus $status)
    {
        return new ProductStatusResource($status);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProductStatusUpdateRequest $request
     * @param  \App\Models\ProductStatus $status
     * @return \Illuminate\Http\Response
     */
    public function update(ProductStatus $status, ProductStatusUpdateRequest $request)
    {
        // dd($request);
        $status->update(
            $request->all()
        );

        return response()->json([
          'data' => __('response.api.updated', [
            'name' => 'product status'
          ])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductStatus $status
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductStatus $status)
    {
        if($status->products()->count() > 0) {
            return response()->json([
              'data' => __('response.api.delete_has_relation', [
                'name' => 'product status'
              ])
            ], 422);
        }

        $status->delete();
        return response()->json([
          'data' => __('response.api.deleted', [
            'name' => 'product status'
          ])
        ], 200);
    }
}
