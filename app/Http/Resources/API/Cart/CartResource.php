<?php

namespace App\Http\Resources\API\Cart;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'products' => CartProductVariationResource::collection($this->cart->makeHidden([
                'pivot',
                'base_price',
                'created_at',
                'updated_at'
                ]))
          ];
    }
}
