<?php

namespace Tests\Unit\Order;

use App\Models\ProductVariationOrder;
use App\Models\ProductVariation;
use App\Models\OrderStatus;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * An order has an status
     *
     * @return void
     */
    public function test_it_has_an_order_status()
    {
        $status = factory(OrderStatus::class)->create();
        $order = factory(Order::class)->create([
          'order_status_id' => $status->id
        ]);

        $this->assertEquals($order->order_status()->count(), 1);
        $this->assertInstanceOf(OrderStatus::class, $order->order_status);
    }

    /**
     * An order has a customer
     *
     * @return void
     */
    public function test_it_has_a_customer()
    {
        $status = factory(OrderStatus::class)->create();
        $customer = factory(Customer::class)->create();
        $order = factory(Order::class)->create([
          'order_status_id' => $status->id,
          'customer_id' => $customer->id
        ]);

        $this->assertEquals($order->customer()->count(), 1);
        $this->assertInstanceOf(Customer::class, $order->customer);
    }

    /**
     * An order has many product variations
     *
     * @return void
     */
    public function test_it_has_many_product_variations()
    {
        $status = factory(OrderStatus::class)->create();
        $customer = factory(Customer::class)->create();
        $order = factory(Order::class)->create([
          'order_status_id' => $status->id,
          'customer_id' => $customer->id
        ]);

        factory(ProductVariationOrder::class)->create([
          'order_id' => $order->id
        ]);

        factory(ProductVariationOrder::class)->create([
          'order_id' => $order->id
        ]);

        $this->assertEquals($order->product_variation_orders()->count(), 2);
        $this->assertInstanceOf(ProductVariationOrder::class, $order->product_variation_orders()->first());
    }
}
