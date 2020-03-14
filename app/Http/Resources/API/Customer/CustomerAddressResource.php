<?php

namespace App\Http\Resources\API\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

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
