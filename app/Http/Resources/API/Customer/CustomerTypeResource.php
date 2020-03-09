<?php

namespace App\Http\Resources\API\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerTypeResource extends JsonResource
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
          'type' => $this->type,
          'slug' => $this->slug
        ];
    }
}
