<?php

namespace App\Http\Resources\API\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

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
          'phone' => $this->phone
        ];
    }
}
