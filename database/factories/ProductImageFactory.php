<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use App\Models\ProductImage;
use Faker\Generator as Faker;

$factory->define(ProductImage::class, function (Faker $faker) {
    return [
        'product_id' => factory(Product::class)->create()->id, 
        'size' => rand(1,10), 
        'location' => $faker->city, 
        'format' => 'jpg'
    ];
});
