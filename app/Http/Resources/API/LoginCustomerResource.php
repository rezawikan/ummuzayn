<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginCustomerResource extends JsonResource
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
         'id'    => $this->id,
         'email' => $this->email,
         'name'  => $this->name,
         'phone' => $this->phone,
         'picture' => 'https://via.placeholder.com/300.png/09f/fff'
       ];
    }
}
