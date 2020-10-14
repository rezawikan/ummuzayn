<?php

namespace App\Http\Resources\API\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\API\Product\ProductResource;

class ProductStatusResource extends JsonResource
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
          'status'  => $this->status,
          'slug'  => $this->slug,
          'products'  => ProductResource::collection($this->whenLoaded('products'))
        ];
    }
}
