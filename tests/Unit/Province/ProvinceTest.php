<?php

namespace Tests\Unit\Customer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Subdistrict;
use App\Models\Province;
use App\Models\City;

class ProvinceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Province has many cities in first
     *
     * @return void
     */
    public function test_province_has_many_cities_first()
    {
        $province = factory(Province::class)->create();

        $province->cities()->saveMany([
             factory(City::class)->make(),
             factory(City::class)->make()
         ]);

        $this->assertdatabaseHas('cities', [
             'province_id' => $province->id
         ]);
    }

    /**
     * Province has many cities in second
     *
     * @return void
     */
    public function test_province_has_many_cities_second()
    {
        $province = factory(Province::class)->create();
        factory(City::class)->create([
           'province_id' => $province->id
         ]);
        factory(City::class)->create([
           'province_id' => $province->id
         ]);


        $this->assertEquals(count($province->cities->toArray()), 2);
    }

    /**
     * Province has many cities in di
     *
     * @return void
     */
    public function test_province_has_many_through_subdistrict()
    {
        $province = factory(Province::class)->create();
        $city     = factory(City::class)->create([
          'province_id' => $province->id
        ]);

        factory(Subdistrict::class)->create([
          'city_id' => $city->id
        ]);
        factory(Subdistrict::class)->create([
          'city_id' => $city->id
        ]);

        $this->assertEquals(count($province->subdistricts->toArray()), 2);
    }
}
