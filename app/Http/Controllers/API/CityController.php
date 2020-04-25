<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\CityResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Scoping\Scopes\Address\ProvinceScope;

class CityController extends Controller
{
    /**
     * List query scope
     *
     * @return array
     */
    protected function scopes()
    {
        return [
          'province'    => new ProvinceScope(),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cities = City::Ordered('name')
        ->withScopes(
            $this->scopes()
        );

        if ($request->paginate == null || $request->paginate == 'true') {
            $cities = $cities->paginate(12)
            ->appends(
                $request->except('page')
            );
        } else {
            $cities = $cities->get();
        }

        return CityResource::collection($cities);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $city = City::create(
            $request->only([
            'name',
            'capital',
            'province_id'
          ])
        );

        return new CityResource($city);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        return new CityResource($city);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        $city->update(
            $request->all()
        );

        return response()->json([
          'data' => __('response.api.updated', [
            'name' => 'city'
          ])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        $city->delete();

        return response()->json([
          'data' => __('response.api.deleted', [
            'name' => 'city'
          ])
        ], 200);
    }
}
