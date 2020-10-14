<?php

namespace Tests\Unit\Subdistrict;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\Subdistrict;
use App\Models\CustomerAddress;
use App\Models\Province;
use App\Models\City;

class SubdistrictTest extends TestCase
{
    /**
     * Subdistrict belongs to city
     *
     * @return void
     */
    public function test_it_belongs_to_city()
    {
        $subdistrict = factory(Subdistrict::class)->create([
          'city_id' => $city = factory(City::class)->create()->id
        ]);

        $this->assertdatabaseHas('subdistricts', [
             'city_id' => $city
        ]);

        $this->assertInstanceOf(City::class, $subdistrict->city);
    }

    /**
     * Subdistrict has many customer addresses
     *
     * @return void
     */
    public function test_it_has_may_customer_addresses()
    {
        $subdistrict = factory(Subdistrict::class)->create();

        factory(CustomerAddress::class)->create([
          'subdistrict_id' => $subdistrict->id
        ]);

        factory(CustomerAddress::class)->create([
          'subdistrict_id' => $subdistrict->id
        ]);

        $this->assertEquals(count($subdistrict->customer_addresses->toArray()), 2);
        $this->assertInstanceOf(CustomerAddress::class, $subdistrict->customer_addresses->first());
    }

    /**
     * Subdistrict belongs to city
     *
     * @return void
     */
    public function test_it_has_one_province()
    {
        $subdistrict = factory(Subdistrict::class)->create([
          'city_id' => $city = factory(City::class)->create()->id
        ]);

        $this->assertInstanceOf(Province::class, $subdistrict->province());
    }
}
