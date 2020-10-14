<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\ProductVariation;
use App\Models\ProductStock;
use Faker\Generator as Faker;

$factory->define(ProductStock::class, function (Faker $faker) {
    return [
        'product_variation_id' => factory(ProductVariation::class)->create()->id, 
        'quantity' => rand(1,100), 
        'info' => $faker->sentence(6)
    ];
});
