<?php

namespace App\Http\Resources\API\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\API\Product\ProductResource;
use App\Http\Resources\API\Product\ProductVariationTypeResource;

class ProductVariationResource extends JsonResource
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
          'product_variation_type_id' => $this->product_variation_type_id,
          'variation_name'  => $this->variation_name,
          'price' => $this->price,
          'base_price'  => $this->base_price,
          'weight'  => $this->weight,
          'stock' => $this->stock,
          'orderable' => $this->orderable,
          'product' => new ProductResource($this->whenLoaded('product')),
          'product_variation_type' => new ProductVariationTypeResource($this->whenLoaded('product_variation_type'))
        ];
    }
}
