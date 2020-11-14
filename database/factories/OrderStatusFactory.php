<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\OrderStatus;
use Faker\Generator as Faker;

$factory->define(OrderStatus::class, function (Faker $faker) {
    return [
      'status' => $type = $faker->unique()->name,
      'slug' => Str::slug($type)
    ];
});
