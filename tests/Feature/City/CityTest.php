<?php

namespace Tests\Feature\City;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Subdistrict;
use App\Models\Province;
use App\Models\Admin;
use App\Models\City;
use Tests\TestCase;

class CityTest extends TestCase
{
    /**
     * It returns many cities with pagination
     *
     * @return void
     */
    public function test_it_returns_many_cities_with_pagination()
    {
        $admin = factory(Admin::class)->create();

        $city = factory(City::class)->create();
        $city->subdistricts()->saveMany([
            factory(Subdistrict::class)->make()
        ]);

        $city1 = factory(City::class)->create();
        $city1->subdistricts()->saveMany([
            factory(Subdistrict::class)->make()
        ]);

        
        $this->jsonAs($admin, 'GET', 'api/cities')
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'capital',
                    'province_id',
                    'province',
                    'subdistricts',
                    'has_subdistricts'
                ]
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next'
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'path',
                'per_page',
                'to',
                'total'
            ]
        ])
        ->assertStatus(200);
    }

    /**
     * It returns many cities without pagination
     *
     * @return void
     */
    public function test_it_returns_many_cities_without_pagination()
    {
        $admin = factory(Admin::class)->create();

        $city = factory(City::class)->create();
        $city->subdistricts()->saveMany([
            factory(Subdistrict::class)->make()
        ]);

        $city1 = factory(City::class)->create();
        $city1->subdistricts()->saveMany([
            factory(Subdistrict::class)->make()
        ]);

        
        $this->jsonAs($admin, 'GET', 'api/cities?paginate=false')
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'capital',
                    'province_id',
                    'province',
                    'subdistricts',
                    'has_subdistricts'
                ]
            ]
        ])
        ->assertStatus(200);
    }

    /**
     * It store city
     *
     * @return void
     */
    public function test_it_store_city()
    {
        $admin = factory(Admin::class)->create();

        $this->jsonAs($admin, 'POST', 'api/cities', [
            'name' => 'Testing' ,
            'capital' => 'Testing One',
            'province_id' => factory(Province::class)->create()->id
        ])
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'capital',
                'province_id',
                'has_subdistricts'
            ]
        ])
        ->assertStatus(201);
    }

    /**
     * It show city
     *
     * @return void
     */
    public function test_it_show_city()
    {
        $admin = factory(Admin::class)->create();

        $city = factory(City::class)->create();

        $this->jsonAs($admin, 'GET', 'api/cities/'.$city->id)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'capital',
                'province_id',
                'has_subdistricts'
            ]
        ])
        ->assertStatus(200);
    }

    /**
     * It update city
     *
     * @return void
     */
    public function test_it_update_city()
    {
        $admin = factory(Admin::class)->create();

        $city = factory(City::class)->create();

        $this->jsonAs($admin, 'PUT', 'api/cities/'.$city->id, [
            'capital' => 'Name'
        ])
        ->assertJsonStructure([
            'data'
        ])
        ->assertStatus(200);
    }

    /**
     * It destroy city
     *
     * @return void
     */
    public function test_it_destroy_city()
    {
        $admin = factory(Admin::class)->create();

        $city = factory(City::class)->create();

        $this->jsonAs($admin, 'DELETE', 'api/cities/'.$city->id)
        ->assertJsonStructure([
            'data'
        ])
        ->assertStatus(200);
    }
}
