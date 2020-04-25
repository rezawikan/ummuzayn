<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\API\CityResource;

class SubdistrictResource extends JsonResource
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
          'city_id' => $this->city_id,
          'name' => $this->name,
          'city' => new CityResource($this->whenLoaded('city'))
        ];
    }
}
