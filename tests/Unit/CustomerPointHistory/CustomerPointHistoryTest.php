<?php

namespace Tests\Unit\CustomerPointHistory;

use App\Models\Order;
use App\Models\Customer;
use App\Models\CustomerPoint;
use App\Models\CustomerPointHistory;
use Tests\TestCase;

class CustomerPointHistoryTest extends TestCase
{
    /**
     * Customer point has many customer point histories
     *
     * @return void
     */
    public function test_it_has_a_customer_point()
    {
        $point = factory(CustomerPoint::class)->create();
        $customer = factory(Customer::class)->create([
          'customer_point_id' => $point->id
        ]);

        $history = new CustomerPointHistory([
            'customer_point_id' => $customer->point->id,
            'point' => 5,
            'description' => 'Purchases point'
        ]);

        $order = factory(Order::class)->create();
        $order->customer_point_history()->save($history);

        $this->assertEquals(1, $history->customer_point()->count());
        $this->assertInstanceOf(CustomerPoint::class, $history->customer_point);
    }

    /**
     * Customer point has many customer point histories
     *
     * @return void
     */
    public function test_it_has_an_instance_order()
    {
        $point = factory(CustomerPoint::class)->create();
        $customer = factory(Customer::class)->create([
          'customer_point_id' => $point->id
        ]);

        $history = new CustomerPointHistory([
            'customer_point_id' => $customer->point->id,
            'point' => 5,
            'description' => 'Purchases point'
        ]);

        $order = factory(Order::class)->create();
        $order->customer_point_history()->save($history);

        $this->assertInstanceOf(Order::class, $history->customer_point_historic);
    }
}
