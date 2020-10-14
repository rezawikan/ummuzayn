<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\ProvinceResource;
use App\Http\Controllers\Controller;
use App\Events\Region\UpdateRegionProvince;
use Illuminate\Http\Request;
use App\Models\Province;

class ProvinceController extends Controller
{
    /**
     * List query scope
     *
     * @return array
     */
    protected function scopes()
    {
        return [];
    }

    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request
     * @return \App\Http\Resources\API\ProvinceResource
     */
    public function index(Request $request)
    {
        $provinces = Province::latest()
        ->withScopes(
            $this->scopes()
        );

        if ($request->paginate == null || $request->paginate == 'true') {
            $provinces = $provinces->paginate(12)
            ->appends(
                $request->except('page')
            );
        } else {
            $provinces = $provinces->get();
        }

        $provinces->load(['cities']);

        return ProvinceResource::collection($provinces);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\API\ProvinceResource
     */
    public function store(Request $request)
    {
        $province = Province::create(
            $request->only([
            'name'
          ])
        );

        $province->load(['cities']);

        event(new UpdateRegionProvince());

        return $province;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  \App\Models\Province
     * @return \App\Http\Resources\API\ProvinceResource
     */
    public function show(Province $province)
    {
        return new ProvinceResource($province);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  object \App\Models\Province
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Province $province)
    {
        $province->update(
            $request->all()
        );
        
        event(new UpdateRegionProvince());

        return response()->json([
          'data' => __('response.api.updated', [
            'name' => 'province'
          ])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int \App\Models\Province
     * @return \Illuminate\Http\Response
     */
    public function destroy(Province $province)
    {
        $province->delete();

        event(new UpdateRegionProvince());

        return response()->json([
        'data' => __('response.api.deleted', [
          'name' => 'province'
        ])
      ], 200);
    }
}
