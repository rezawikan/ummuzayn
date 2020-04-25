<?php

namespace App\Http\Resources\API\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class CustomerAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->resource instanceof Collection) {
            return CustomerAddressResource::collection($this->resource);
        }

        return [
          'id' => $this->id,
          'customer_id' => $this->customer_id,
          'subdistrict_id' => $this->subdistrict_id,
          'default' => $this->default,
          'name' => $this->name,
          'address' => $this->address,
          'phone' => $this->phone
        ];
    }
}
