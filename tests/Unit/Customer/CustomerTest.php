<?php

namespace Tests\Unit\Customer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Customer;
use App\Models\CustomerType;

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

        $this->assertEquals(1, $customer->type->count());
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
}
