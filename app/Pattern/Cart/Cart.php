<?php

namespace App\Pattern\Cart;

use App\Pattern\Cart\ConversionWeight;
use App\Models\Customer;
use Illuminate\Support\Arr;

class Cart
{
    public $customer;
    protected $changed = false;

    public function __construct($customer)
    {
        if ($customer instanceof Customer) {
            $this->customer = $customer;
        } else {
            $this->customer = Customer::find($customer);
        }
    }

    /**
     * Stock sync to make sure stock is available.
     *
     * @return void
     */
    public function sync()
    {
        $this->customer->fresh()->cart->each(function ($product) {
            $quantity = min($product->stock, $product->pivot->quantity);

            if ($quantity != $product->pivot->quantity) {
                $this->changed = true;
            }

            $product->pivot->update([
                'quantity' => $quantity
            ]);
        });
    }

    /**
     * Add items without detach existing cart
     *
     * @param \App\Http\Requests\CartStoreRequest $products
     * @return void
     */
    public function add($products)
    {
        $this->customer->cart()->syncWithoutDetaching(
            $this->getStorePayload($products)
        );
    }

    /**
     * Add items without detach existing cart
     *
     * @param integer $variationID
     * @param integer $quantity
     * @return void
     */
    public function update($variationID, $quantity)
    {
        $this->customer->cart()->updateExistingPivot($variationID, [
          'quantity' => $quantity
        ]);
    }

    /**
     * Detach item in exsiting cart
     *
     * @param int $variationID
     * @return void
     */
    public function delete($variationID)
    {
        $this->customer->cart()->detach([$variationID]);
    }

    /**
     * Check some existing item in the cart
     *
     * @return Boolean
     */
    protected function isEmpty()
    {
        if (count($this->customer->cart) != 0) {
            $map = collect($this->customer->cart)->map(function ($cart) {
                return [
                  $cart->pivot->quantity <= 0
                ];
            });

            return in_array(true, Arr::flatten($map->toArray()));
        }

        return true;
    }

    /**
     * Check total amount price according with quantity
     *
     * @return Integer
     */
    protected function subTotal($price = "price")
    {
        $subtotal = $this->customer->cart->sum(function ($product) use ($price) {
            return $product->{$price} * $product->pivot->quantity;
        });

        return $subtotal;
    }

    /**
     * Check total numbers of weight according with quantity
     *
     * @return \App\Pattern\Cart\ConversionWeight
     */
    protected function totalWeight()
    {
        $weight = $this->customer->cart->sum(function ($product) {
            return $product->weight * $product->pivot->quantity;
        });

        return (new ConversionWeight($weight))->result();
    }

    /**
     * Check total numbers of weight according with quantity
     *
     * @return \App\Pattern\Cart\ConversionWeight
     */
    protected function baseProfit()
    {
        return $this->subTotal() - $this->subTotal('base_price');
    }


    /**
     * Check exsisting items are change
     *
     * @return \App\Pattern\Cart\ConversionWeight
     */
    protected function hasChanged()
    {
        return $this->changed;
    }

    
    /**
     * Convert request to different format (set id as a key)
     *
     * @param \App\Http\Requests\CartStoreRequest $products
     * @return Array
     */
    protected function getStorePayload($products)
    {
        return collect($products)->keyBy('id')->map(function ($product) {
            return [
              'quantity' => $product['quantity'] + $this->getCurrentQuantity($product['id'])
            ];
        })->toArray();
    }

    /**
     * Convert request to different format (set id as a key)
     *
     * @param integer $productID
     * @return integer
     */
    protected function getCurrentQuantity($productID)
    {
        if ($product = $this->customer->cart->where('id', $productID)->first()) {
            return $product->pivot->quantity;
        }

        return 0;
    }

    public function summary()
    {
        return [
        'empty' => $this->isEmpty(),
        'weight' => $this->totalWeight(),
        'subtotal' => $this->subTotal(),
        'changed' => $this->hasChanged(),
        'baseProfit' => $this->baseProfit()
      ];
    }
}
