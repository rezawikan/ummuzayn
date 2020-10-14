<?php

namespace Tests\Feature\CustomerType;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\CustomerType;
use App\Models\Admin;
use Tests\TestCase;

class CustomerTypeTest extends TestCase
{
    /**
     * It returns many customer type with pagination
     *
     * @return void
     */
    public function test_it_returns_customer_type_with_pagination()
    {
        $admin = factory(Admin::class)->create();
        factory(CustomerType::class, 10)->create();
        
        $this->jsonAs($admin, 'GET', 'api/customer-types')
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'type',
                    'slug'
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
     * It returns many customer type without pagination
     *
     * @return void
     */
    public function test_it_returns_customer_type_without_pagination()
    {
        $admin = factory(Admin::class)->create();
        factory(CustomerType::class, 10)->create();
        
        $this->jsonAs($admin, 'GET', 'api/customer-types?paginate=false')
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'type',
                    'slug'
                ]
            ]
        ])
        ->assertStatus(200);
    }

    /**
    * It store customer type
    *
    * @return void
    */
    public function test_it_store_customer_type()
    {
        $admin = factory(Admin::class)->create();
        $this->jsonAs($admin, 'POST', 'api/customer-types', [
            'type' => 'name',
            'slug' => 'name',
        ])
        ->assertJsonStructure([
            'data' => [
                'id',
                'type',
                'slug'
            ]
        ])
        ->assertStatus(201);
    }

    /**
     * It show customers
     *
     * @return void
     */
    public function test_it_show_customer_types()
    {
        $admin = factory(Admin::class)->create();
        $customerType = factory(CustomerType::class)->create();

        $this->jsonAs($admin, 'GET', 'api/customer-types/'.$customerType->id)
        ->assertJsonStructure([
            'data' => [
                'id',
                'type',
                'slug'
            ]
        ])
        ->assertStatus(200);
    }

    /**
     * It update customer types
     *
     * @return void
     */
    public function test_it_update_customer_type()
    {
        $admin = factory(Admin::class)->create();
        $customerType = factory(CustomerType::class)->create();

        $this->jsonAs($admin, 'PUT', 'api/customer-types/'.$customerType->id, [
            'name' => 'Name'
        ])
        ->assertJsonStructure([
            'data'
        ])
        ->assertStatus(200);
    }

    /**
     * It destroy customer type
     *
     * @return void
     */
    public function test_it_destroy_customer_type()
    {
        $admin = factory(Admin::class)->create();
        $customerType = factory(CustomerType::class)->create();

        $this->jsonAs($admin, 'DELETE', 'api/customer-types/'.$customerType->id)
        ->assertJsonStructure([
            'data'
        ])
        ->assertStatus(200);
    }
}
