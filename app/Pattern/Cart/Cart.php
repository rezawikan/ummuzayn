<?php

namespace App\Pattern\Cart;

use App\Pattern\Cart\ConversionWeight;
use App\Models\Customer;
use App\Models\MarketplaceFee;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

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
     * @param string $price
     * @return integer
     */
    protected function subTotal($price = 'price')
    {
        $subtotal = $this->customer->cart->sum(function ($product) use ($price) {
            return $product->{$price} * $product->pivot->quantity;
        });

        return $subtotal;
    }

    /**
     * Get marketplace fee
     *
     * @param integer $marketplaceFeeID
     * @return Integer
     */
    protected function getMarketPlaceFee($marketplaceFeeID)
    {
        if (!empty($marketplaceFeeID)) {
            return MarketplaceFee::find($marketplaceFeeID);
        }

        return null;
    }

    /**
     * Check total amount price according with quantity and request
     *
     * @param \Illuminate\Http\Request $request
     * @return Integer
     */
    protected function subTotalWithRequest(Request $request)
    {
        $subtotal = $this->subTotal();
        $keys = collect($request->all())->keys();

        foreach ($keys as $value) {
            if ($value == 'marketplaceFeeID') {
                if (!is_null($fee = $this->getMarketPlaceFee($request->{$value}))) {
                    $subtotal = $subtotal - ($subtotal * ($fee->percent / 100));
                }
            }
        }
        return $subtotal;
    }

    /**
     * it check has marketplace
     *
     * @param \Illuminate\Http\Request $request
     * @return boolean
     */
    protected function hasMarketplaceFee(Request $request)
    {
        $keys = collect($request->all())->keys();

        foreach ($keys as $value) {
            if ($value == 'marketplaceFeeID') {
                if (!is_null($fee = $this->getMarketPlaceFee($request->{$value}))) {
                    return $fee->percent > 0 ? true : false;
                }
            }
        }

        return false;
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
     * Check total numbers of weight according with quantity
     *
     * @param \Illuminate\Http\Request $request
     * @return integer
     */
    protected function baseProfitWithFee(Request $request)
    {
        return $this->subTotalWithRequest($request) - $this->subTotal('base_price');
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

    public function summary(Request $request)
    {
        return [
            'empty' => $this->isEmpty(),
            'changed' => $this->hasChanged(),
            'hasMarketplaceFee' => $this->hasMarketplaceFee($request),
            'weight' => $this->totalWeight(),
            'subTotal' => $this->subTotal(),
            'baseProfit' => $this->baseProfit(),
            'subTotalWithFee' => $this->subTotalWithRequest($request),
            'baseProfitWithFee' => $this->baseProfitWithFee($request)
      ];
    }
}
