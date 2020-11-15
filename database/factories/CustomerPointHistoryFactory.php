<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Order;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\CustomerPoint;
use App\Models\CustomerPointHistory;
use App\Models\CustomerPointType;
use Faker\Generator as Faker;

$factory->define(CustomerPointHistory::class, function (Faker $faker) {
    $order = factory(Order::class)->create();
    return [
        'customer_point_id' => factory(CustomerPoint::class)->create()->id,
        'point' => $order->point,
        'description' => $faker->name,
    ];
});
