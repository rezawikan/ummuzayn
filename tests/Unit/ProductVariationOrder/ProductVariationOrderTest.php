<?php

namespace Tests\Unit\ProductVariationOrder;

use App\Models\ProductVariationOrder;
use App\Models\ProductVariation;
use App\Models\OrderStatus;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use Tests\TestCase;

class ProductVariationOrderTest extends TestCase
{
    /**
     * A product variation order has an order
     *
     * @return void
     */
    public function test_it_has_an_order()
    {
        $status = factory(OrderStatus::class)->create();
        $customer = factory(Customer::class)->create();
        $order = factory(Order::class)->create([
          'order_status_id' => $status->id,
          'customer_id' => $customer->id
        ]);

        $product_variation_order = factory(ProductVariationOrder::class)->create([
          'order_id' => $order->id
        ]);

        $this->assertInstanceOf(Order::class, $product_variation_order->order);
    }
}
