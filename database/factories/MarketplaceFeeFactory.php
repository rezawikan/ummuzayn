<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use App\Models\MarketplaceFee;
use Faker\Generator as Faker;

$factory->define(MarketplaceFee::class, function (Faker $faker) {
    return [
        'name' => $type = $faker->unique()->name,
        'slug' => Str::slug($type),
        'percent' => rand(1, 5)
    ];
});
