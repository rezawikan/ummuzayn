<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ProductVariationOrder;
use App\Models\ProductVariationType;
use App\Models\ProductVariation;
use App\Models\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(ProductVariationOrder::class, function (Faker $faker) {
    $product = factory(Product::class)->create();
    $type = factory(ProductVariationType::class)->create();
    $variation = factory(ProductVariation::class)->create([
      'product_id' => $product->id,
      'product_variation_type_id' => $type->id
    ]);

    return [
      'order_id' => factory(Order::class)->create()->id,
      'product_variation_id' => $variation->id,
      'product_name' => $product->name,
      'product_description' => $product->description,
      'product_variation_type' => $type->variation_type,
      'product_variation_name' => $variation->variation_name,
      'price' => $variation->price,
      'base_price' => $variation->base_price,
      'weight' => $variation->weight,
      'quantity' => 1,
      'point' => 10
  ];
});
