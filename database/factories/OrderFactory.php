<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ProductVariationOrder;
use App\Models\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
      'customer_id' => factory(Customer::class)->create()->id,
      'order_status_id' => factory(OrderStatus::class)->create()->id,
      'subtotal' => 100,
      'base_subtotal' => 75,
      'marketplace_fee' => 0,
      'discount' => 0,
      'total' => 100,
      'total_profit' => 75,
      'point' => 10
  ];
});
