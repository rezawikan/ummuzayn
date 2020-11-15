<?php

namespace Tests\Unit\OrderStatus;

use Tests\TestCase;
use App\Models\OrderStatus;
use App\Models\Order;

class OrderStatusTest extends TestCase
{
    /**
     * An order status has many orders
     *
     * @return void
     */
    public function test_it_has_many_orders()
    {
        $status = factory(OrderStatus::class)->create();

        factory(Order::class)->create([
          'order_status_id' => $status->id
        ]);

        factory(Order::class)->create([
            'order_status_id' => $status->id
        ]);

        $this->assertEquals($status->orders()->count(), 2);
        $this->assertInstanceOf(Order::class, $status->orders()->first());
    }
}
