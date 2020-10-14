<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ProductVariationType;
use App\Models\ProductVariation;
use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(ProductVariation::class, function (Faker $faker) {
    return [
        'product_id' => factory(Product::class)->create()->id,
        'product_variation_type_id' => factory(ProductVariationType::class)->create()->id,
        'variation_name' => $faker->name,
        'price' => 100, 
        'base_price' => 75, 
        'weight' => 1, 
        'orderable' => rand(1,100)
    ];
});
