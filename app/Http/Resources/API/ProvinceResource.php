<?php

namespace App\Http\Resources\API;

use App\Http\Resources\API\CityResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProvinceResource extends JsonResource
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
          'cities' => CityResource::collection($this->whenLoaded('cities')),
          'has_cities' => $this->has_cities()
        ];
    }
}
