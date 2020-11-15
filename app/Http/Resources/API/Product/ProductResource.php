<?php

namespace App\Http\Resources\API\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\API\Product\ProductStatusResource;
use App\Http\Resources\API\Product\ProductImageResource;
use App\Http\Resources\API\Product\ProductVariationResource;

class ProductResource extends JsonResource
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
          'product_category_id' => $this->product_category_id,
          'product_status_id' => $this->product_status_id,
          'name'  => $this->name,
          'description' => $this->description,
          'has_variation_type' => $this->has_variation_type(),
          'status' => new ProductStatusResource($this->whenLoaded('product_status')),
          'product_images' => ProductImageResource::collection($this->whenLoaded('product_images')),
          'product_variations' => ProductVariationResource::collection($this->whenLoaded('product_variations'))
        ];
    }
}
