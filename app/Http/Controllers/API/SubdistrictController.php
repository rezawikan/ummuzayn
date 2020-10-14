<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\SubdistrictResource;
use App\Scoping\Scopes\Address\CityScope;
use App\Http\Controllers\Controller;
use App\Events\Region\UpdateRegionSubdistrict;
use Illuminate\Http\Request;
use App\Models\Subdistrict;

class SubdistrictController extends Controller
{
    /**
     * List query scope
     *
     * @return array
     */
    protected function scopes()
    {
        return [
        'city'    => new CityScope(),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\API\SubdistrictResource
     */
    public function index(Request $request)
    {
        $subdistricts = Subdistrict::latest()
        ->withScopes(
            $this->scopes()
        );

        if ($request->paginate == null || $request->paginate == 'true') {
            $subdistricts = $subdistricts->paginate(12)
            ->appends(
                $request->except('page')
            );
        } else {
            $subdistricts = $subdistricts->get();
        }

        $subdistricts->load(['city.province']);

        return SubdistrictResource::collection($subdistricts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\API\SubdistrictResource
     */
    public function store(Request $request)
    {
        $subdistrict = Subdistrict::create(
            $request->only([
              'city_id',
              'name'
            ])
        );

        event(new UpdateRegionSubdistrict());

        return new SubdistrictResource($subdistrict);
    }

    /**
     * Display the specified resource.
     *
     * @param int \App\Models\Subdistrict
     * @return \App\Http\Resources\API\SubdistrictResource
     */
    public function show(Subdistrict $subdistrict)
    {
        $subdistrict->load(['city.province']);
        return new SubdistrictResource($subdistrict);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int \App\Models\Subdistrict
     * @return \App\Http\Resources\API\SubdistrictResource
     */
    public function update(Request $request, Subdistrict $subdistrict)
    {
        $subdistrict->update(
            $request->all()
        );

        event(new UpdateRegionSubdistrict());

        return response()->json([
          'data' => __('response.api.updated', [
            'name' => 'subdistrict'
          ])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int \App\Models\Subdistrict
     * @return \App\Http\Resources\API\SubdistrictResource
     */
    public function destroy(Subdistrict $subdistrict)
    {
        $subdistrict->delete();

        event(new UpdateRegionSubdistrict());

        return response()->json([
          'data' => __('response.api.deleted', [
            'name' => 'subdistrict'
          ])
        ], 200);
    }
}
