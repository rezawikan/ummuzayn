<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CustomerType;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(CustomerType::class, function (Faker $faker) {
    return [
      'type' => $type = $faker->unique()->name,
      'slug' => Str::slug($type),
    ];
});
