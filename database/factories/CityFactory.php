<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\City;
use App\Models\Province;
use Faker\Generator as Faker;

$factory->define(City::class, function (Faker $faker) {
    return [
      'province_id' => factory(Province::class)->create()->id,
      'capital' => $faker->streetSuffix,
      'name' => $faker->city
    ];
});
