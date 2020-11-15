<?php

namespace Tests\Unit\CustomerPoint;

use App\Models\Order;
use App\Models\Customer;
use App\Models\CustomerPoint;
use App\Models\CustomerPointHistory;
use Tests\TestCase;

class CustomerPointTest extends TestCase
{
    /**
     * Customer point has a customer
     *
     * @return void
     */
    public function test_it_has_a_customer()
    {
        $point = factory(CustomerPoint::class)->create();
        $customer = factory(Customer::class)->create([
          'customer_point_id' => $point->id
        ]);

        $this->assertEquals(1, $point->customer()->count());
        $this->assertInstanceOf(Customer::class, $point->customer);
    }

    /**
     * Customer point has many customer point histories
     *
     * @return void
     */
    public function test_it_has_many_customer_point_histories()
    {
        $point = factory(CustomerPoint::class)->create();
        $customer = factory(Customer::class)->create([
          'customer_point_id' => $point->id
        ]);

        $histories = [
          new CustomerPointHistory([
            'customer_point_id' => $customer->point->id,
            'point' => 5,
            'description' => 'Purchases point'
          ]),

          new CustomerPointHistory([
            'customer_point_id' => $customer->point->id,
            'point' => 5,
            'description' => 'Purchases point'
          ])
        ];

        $orders = factory(Order::class, 2)->create();

        foreach ($orders as $key => $order) {
            $order->customer_point_history()->save($histories[$key]);
        }

        $this->assertEquals(2, $point->customer_point_histories()->count());
        $this->assertInstanceOf(CustomerPointHistory::class, $point->customer_point_histories->first());
    }
}
