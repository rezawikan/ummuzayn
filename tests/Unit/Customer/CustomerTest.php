<?php

namespace Tests\Unit\Customer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\CustomerAddress;

class CustomerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Customer has customer type
     *
     * @return void
     */
    public function test_it_has_customer_type()
    {
        $customer = factory(Customer::class)->create([
          'customer_type_id' => factory(CustomerType::class)->create()->id
        ]);

        $this->assertEquals(1, $customer->type()->count());
    }

    /**
     * Customer has relation to customer type
     *
     * @return void
     */
    public function test_it_belongs_to_a_customer_type()
    {
        $customer = factory(Customer::class)->create([
          'customer_type_id' => factory(CustomerType::class)->create()->id
        ]);

        $this->assertInstanceOf(CustomerType::class, $customer->type);
    }

    /**
     * Customer has relation to customer type
     *
     * @return void
     */
    public function test_it_has_many_customer_addresses()
    {
        $customer = factory(Customer::class)->create();
        $customer->customer_addresses()->saveMany([
          factory(CustomerAddress::class)->make(),
          factory(CustomerAddress::class)->make()
        ]);

        $this->assertEquals(2, $customer->customer_addresses()->count());
    }

    /**
     * Customer has relation to customer type
     *
     * @return void
     */
    public function test_it_has_one_default_address()
    {
        $customer = factory(Customer::class)->create();
        $customer->customer_addresses()->saveMany([
          factory(CustomerAddress::class)->make(),
          factory(CustomerAddress::class)->make([
            'default' => true
          ])
        ]);

        $this->assertEquals(1, $customer->default_address()->count());
    }
}
