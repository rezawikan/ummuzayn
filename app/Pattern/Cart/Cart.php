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
     * Get summary
     *
     * @param \Illuminate\Http\Request $request
     * @return Array
     */
    public function summary(Request $request)
    {
        return [
            'IsAnyEmpty' => $this->isEmpty(),
            'changed' => $this->hasChanged(),
            'hasMarketplaceFee' => $this->hasMarketplaceFee($request),
            'weight' => $this->totalWeight(),
            'subtotal' => $this->subTotal(),
            'base_subtotal' => $this->subTotal('base_price'),
            'point' => $this->totalPoint(),
            // 'base_profit' => $this->baseProfit(),
            'marketplace_fee' => $this->getMarketplaceFeeAmount($request),
            // 'subtotal_with_fee' => $this->subTotalWithRequest($request),
            // 'base_profit_with_fee' => $this->baseProfitWithFee($request),
            'discount' => $this->getDiscount($request),
            'total_profit' => $this->baseProfitWithFee($request) - $this->getDiscount($request),
            'total' => $this->subTotalWithRequest($request) - $this->getDiscount($request),
      ];
    }

    /**
     * Check some existing item in the cart
     *
     * @return Boolean
     */
    public function isEmpty()
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
    public function subTotal($price = 'price')
    {
        $subtotal = $this->customer->cart->sum(function ($product) use ($price) {
            return $product->{$price} * $product->pivot->quantity;
        });

        return $subtotal;
    }

    /**
     * Check total amount price according with quantity and request
     *
     * @param \Illuminate\Http\Request $request
     * @return Integer
     */
    public function subTotalWithRequest(Request $request)
    {
        $subtotal = $this->subTotal();
        $keys = collect($request->all())->keys();

        foreach ($keys as $value) {
            if ($value == 'marketplace_fee_id') {
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
    public function hasMarketplaceFee(Request $request)
    {
        $keys = collect($request->all())->keys();

        foreach ($keys as $value) {
            if ($value == 'marketplace_fee_id') {
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
    public function totalWeight()
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
    public function totalPoint()
    {
        $points = $this->customer->cart->sum(function ($product) {
            return $product->point * $product->pivot->quantity;
        });

        return $points;
    }

    /**
     * Check total numbers of weight according with quantity
     *
     * @return \App\Pattern\Cart\ConversionWeight
     */
    public function baseProfit()
    {
        return $this->subTotal() - $this->subTotal('base_price');
    }

    /**
     * Check total numbers of weight according with quantity
     *
     * @param \Illuminate\Http\Request $request
     * @return integer
     */
    public function baseProfitWithFee(Request $request)
    {
        return $this->subTotalWithRequest($request) - $this->subTotal('base_price');
    }

    /**
     * Check exsisting items are change
     *
     * @return \App\Pattern\Cart\ConversionWeight
     */
    public function hasChanged()
    {
        return $this->changed;
    }

    /**
     * Get marketplace fee amount
     *
    * @param \Illuminate\Http\Request $request
     * @return integer
     */
    public function getMarketplaceFeeAmount(Request $request)
    {
        if ($request->has('marketplace_fee_id')) {
            if (!is_null($detail = $this->getMarketPlaceFee($request->marketplace_fee_id))) {
                return round($this->subTotal() * ($detail->percent /100));
            }
        }

        return 0;
    }

    /**
     * Get discount
     *
    * @param \Illuminate\Http\Request $request
     * @return integer
     */
    public function getDiscount(Request $request)
    {
        if ($request->has('discount')) {
            if ($request->discount != 0) {
                return (int) $request->discount;
            }
        }

        return 0;
    }

    /**
     * Get marketplace fee
     *
     * @param integer $marketplace_fee_id
     * @return Integer
     */
    protected function getMarketPlaceFee($marketplace_fee_id)
    {
        if (!empty($marketplace_fee_id)) {
            return MarketplaceFee::find($marketplace_fee_id);
        }

        return null;
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
}
