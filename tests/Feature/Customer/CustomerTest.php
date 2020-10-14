<?php

namespace Tests\Feature\Customer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\CustomerType;
use App\Models\Customer;
use App\Models\Admin;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    /**
     * It returns many customer with pagination
     *
     * @return void
     */
    public function test_it_returns_customer_with_pagination()
    {
        $admin = factory(Admin::class)->create();
        factory(Customer::class, 10)->create();
        
        $this->jsonAs($admin, 'GET', 'api/customers')
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'customer_type_id',
                    'name',
                    'email',
                    'phone',
                    'type',
                    'created_at',
                    'updated_at'
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
     * It returns many customer without pagination
     *
     * @return void
     */
    public function test_it_returns_customer_without_pagination()
    {
        $admin = factory(Admin::class)->create();
        factory(Customer::class)->create();
        
        $this->jsonAs($admin, 'GET', 'api/customers?paginate=false')
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'customer_type_id',
                    'name',
                    'email',
                    'phone',
                    'type',
                    'created_at',
                    'updated_at'
                ]
            ]
        ])
        ->assertStatus(200);
    }

    /**
     * It store customer
     *
     * @return void
     */
    public function test_it_store_customer()
    {
        $admin = factory(Admin::class)->create();
        $this->jsonAs($admin, 'POST', 'api/customers', [
            'customer_type_id' => factory(CustomerType::class)->create()->id,
            'name' => 'Dika',
            'email' => 'test@gmail.com',
            'phone' => '123456789',
            'password' => 'password'
        ])
        ->assertJsonStructure([
            'data' => [
                'id',
                'customer_type_id',
                'name',
                'email',
                'phone',
                'created_at',
                'updated_at'
            ]
        ])
        ->assertStatus(201);
    }


    /**
     * It show customers
     *
     * @return void
     */
    public function test_it_show_customers()
    {
        $admin = factory(Admin::class)->create();
        $customer = factory(Customer::class)->create();

        $this->jsonAs($admin, 'GET', 'api/customers/'.$customer->id)
        ->assertJsonStructure([
            'data' => [
                'id',
                'customer_type_id',
                'name',
                'email',
                'phone',
                'customer_addresses',
                'created_at',
                'updated_at'
            ]
        ])
        ->assertStatus(200);
    }

    /**
     * It update customer
     *
     * @return void
     */
    public function test_it_update_customer()
    {
        $admin = factory(Admin::class)->create();
        $customer = factory(Customer::class)->create();

        $this->jsonAs($admin, 'PUT', 'api/customers/'.$customer->id, [
            'name' => 'Name'
        ])
        ->assertJsonStructure([
            'data'
        ])
        ->assertStatus(200);
    }

    /**
     * It destroy customer
     *
     * @return void
     */
    public function test_it_destroy_customer()
    {
        $admin = factory(Admin::class)->create();
        $customer = factory(Customer::class)->create();

        $this->jsonAs($admin, 'DELETE', 'api/customers/'.$customer->id)
        ->assertJsonStructure([
            'data'
        ])
        ->assertStatus(200);
    }
}
