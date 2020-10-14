<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use App\Models\ProductStatus;
use App\Models\ProductCategory;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'product_category_id' => factory(ProductCategory::class)->create()->id,
        'name' => $faker->name, 
        'description' => $faker->name, 
        'status_id' => factory(ProductStatus::class)->create()->id
    ];
});
