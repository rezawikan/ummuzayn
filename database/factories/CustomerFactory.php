<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Customer;
use App\Models\CustomerType;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        // 'customer_type_id' => factory(CustomerType::class)->create()->id,
        'name' => $faker->name,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber
    ];
});
