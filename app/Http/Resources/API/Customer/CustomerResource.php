<?php

namespace App\Http\Resources\API\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\API\Customer\CustomerTypeResource;
use App\Http\Resources\API\Customer\CustomerAddressResource;

class CustomerResource extends JsonResource
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
          'customer_type_id' => $this->customer_type_id,
          'name' => $this->name,
          'email' => $this->email,
          'phone' => $this->phone,
          'type' => new CustomerTypeResource($this->whenLoaded('type')),
          'customer_addresses' => new CustomerAddressResource($this->whenLoaded('customer_addresses')),
          'created_at' => $this->created_at,
          'updated_at' => $this->updated_at
        ];
    }
}
