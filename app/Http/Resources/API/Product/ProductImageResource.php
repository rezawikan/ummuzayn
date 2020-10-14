<?php

namespace App\Http\Resources\API\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
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
          'id'  => $this->id,
          'product_id'  => $this->product_id,
          'size'  => $this->size,
          'location'  => $this->location,
          'format'  => $this->format
        ];
    }
}
