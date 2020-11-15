<?php

namespace App\Http\Resources\API\Cart;

use Illuminate\Http\Resources\Json\JsonResource;

class CartProductVariationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'product' => new CartProductIndexResource($this->product),
            'weight' =>  $this->weight,
            'point' => $this->point,
            'quantity' => $this->pivot->quantity,
            'total'    => $this->getTotal(),
            'base_total' => $this->getBaseTotal()
        ]);
    }

    protected function getBaseTotal()
    {
        return $this->pivot->quantity * $this->base_price;
    }

    protected function getTotal()
    {
        return $this->pivot->quantity * $this->price;
    }
}
