<?php

namespace Tests\Unit\City;

use Tests\TestCase;
use App\Models\Subdistrict;
use App\Models\Province;
use App\Models\City;

class CityTest extends TestCase
{
    /**
     * City belongs to city
     *
     * @return void
     */
    public function test_it_belongs_to_city()
    {
        $city = factory(City::class)->create([
            'province_id' => $province = factory(Province::class)->create()
          ]);

        $this->assertEquals($city->province->id, $province->id);
    }

    /**
     * City has many subdistricts
     *
     * @return void
     */
    public function test_it_has_many_subdistricts()
    {
        $city = factory(City::class)->create();
        $city->subdistricts()->saveMany([
          factory(Subdistrict::class)->make(),
          factory(Subdistrict::class)->make()
        ]);

        $this->assertEquals($city->has_subdistricts(), true);
    }
}
