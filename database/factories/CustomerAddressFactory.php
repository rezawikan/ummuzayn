<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CustomerAddress;
use App\Models\Customer;
use App\Models\Subdistrict;
use Faker\Generator as Faker;

$factory->define(CustomerAddress::class, function (Faker $faker) {
    return [
      'customer_id' => factory(Customer::class)->create()->id,
      'subdistrict_id' => factory(Subdistrict::class)->create()->id,
      'default' => false,
      'name' => $faker->name,
      'address' => $faker->address,
      'phone' => $faker->phoneNumber
    ];
});
