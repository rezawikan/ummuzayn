<?php

namespace App\Http\Resources\API\Cart;

use Illuminate\Http\Resources\Json\JsonResource;

class CartIndexResource extends JsonResource
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
          'name'  => $this->name,
          'cart_count' => count($this->cart)
        ];
    }
}
