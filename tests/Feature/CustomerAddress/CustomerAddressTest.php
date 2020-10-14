<?php

namespace Tests\Feature\CustomerAddress;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\CustomerAddress;
use App\Models\Subdistrict;
use App\Models\Customer;
use App\Models\Admin;
use Tests\TestCase;

class CustomerAddressTest extends TestCase
{
    /**
     * It returns many customer address with pagination
     *
     * @return void
     */
    public function test_it_returns_customer_address_with_pagination()
    {
        $admin = factory(Admin::class)->create();
        $customerAddress = factory(CustomerAddress::class)->create();
        
        $this->jsonAs($admin, 'GET', 'api/customer-address')
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'customer_id',
                    'subdistrict_id',
                    'default',
                    'name',
                    'address',
                    'phone'
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
     * It returns customer address without pagination
     *
     * @return void
     */
    public function test_it_returns_customer_addres_without_pagination()
    {
        $admin = factory(Admin::class)->create();
        $customerAddress = factory(CustomerAddress::class)->create();
        
        $this->jsonAs($admin, 'GET', 'api/customer-address?paginate=false')
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'customer_id',
                    'subdistrict_id',
                    'default',
                    'name',
                    'address',
                    'phone'
                ]
            ]
        ])
        ->assertStatus(200);
    }

    /**
     * It store customer address
     *
     * @return void
     */
    public function test_it_store_customer_address()
    {
        $admin = factory(Admin::class)->create();
        $this->jsonAs($admin, 'POST', 'api/customer-address', [
            'customer_id' => factory(Customer::class)->create()->id,
            'subdistrict_id' => factory(Subdistrict::class)->create()->id,
            'default' => 1,
            'name' => 'Dika',
            'address' => 'Jalan baru',
            'phone' => '25343465345'
        ])
        ->assertJsonStructure([
            'data' => [
                'id',
                'customer_id',
                'subdistrict_id',
                'default',
                'name',
                'address',
                'phone'
            ]
        ])
        ->assertStatus(201);
    }

    /**
     * It show customer address
     *
     * @return void
     */
    public function test_it_show_customer_address()
    {
        $admin = factory(Admin::class)->create();
        $customerAddress = factory(CustomerAddress::class)->create([
            'customer_id' => factory(Customer::class)->create()->id,
            'subdistrict_id' => factory(Subdistrict::class)->create()->id,
            'default' => 1,
            'name' => 'Testing',
            'address' => 'Jalan baru',
            'phone' => '25343465345'
        ]);

        $this->jsonAs($admin, 'GET', 'api/customer-address/'.$customerAddress->id)
        ->assertJsonStructure([
            'data' => [
                'id',
                'customer_id',
                'subdistrict_id',
                'default',
                'name',
                'address',
                'phone'
            ]
        ])
        ->assertStatus(200);
    }

    /**
     * It update customer address
     *
     * @return void
     */
    public function test_it_update_customer_address()
    {
        $admin = factory(Admin::class)->create();
        $customerAddress = factory(CustomerAddress::class)->create([
            'customer_id' => factory(Customer::class)->create()->id,
            'subdistrict_id' => factory(Subdistrict::class)->create()->id,
            'default' => 1,
            'name' => 'Testing',
            'address' => 'Jalan baru',
            'phone' => '25343465345'
        ]);

        $this->jsonAs($admin, 'PUT', 'api/customer-address/'.$customerAddress->id, [
            'name' => 'Name'
        ])
        ->assertJsonStructure([
            'data'
        ])
        ->assertStatus(200);
    }

    /**
     * It destroy customer address
     *
     * @return void
     */
    public function test_it_destroy_customer_address()
    {
        $admin = factory(Admin::class)->create();
        $customerAddress = factory(CustomerAddress::class)->create([
            'customer_id' => factory(Customer::class)->create()->id,
            'subdistrict_id' => factory(Subdistrict::class)->create()->id,
            'default' => 1,
            'name' => 'Testing',
            'address' => 'Jalan baru',
            'phone' => '25343465345'
        ]);

        $this->jsonAs($admin, 'DELETE', 'api/customer-address/'.$customerAddress->id)
        ->assertJsonStructure([
            'data'
        ])
        ->assertStatus(200);
    }
}
