<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\CustomerPoint;
use Faker\Generator as Faker;

$factory->define(CustomerPoint::class, function (Faker $faker) {
    return [
        'total_points' => 0
    ];
});
