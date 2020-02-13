<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\City;
use App\Models\Subdistrict;
use Faker\Generator as Faker;

$factory->define(Subdistrict::class, function (Faker $faker) {
    return [
      'city_id' => factory(City::class)->create()->id,
      'name' => $faker->streetName
    ];
});
