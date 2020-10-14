<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\API\SubdistrictResource;
use App\Http\Resources\API\ProvinceResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'name' => $this->name,
          'capital' => $this->capital,
          'province_id' => $this->province_id,
          'province' => new ProvinceResource($this->whenLoaded('province')),
          'subdistricts' => SubdistrictResource::collection($this->whenLoaded('subdistricts')),
          'has_subdistricts' => $this->has_subdistricts()
        ];
    }
}
