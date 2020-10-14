<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ProductVariationType;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(ProductVariationType::class, function (Faker $faker) {
    return [
        'variation_type' => $color = $faker->safeColorName, 
        'slug' => Str::slug($color)
    ];
});
