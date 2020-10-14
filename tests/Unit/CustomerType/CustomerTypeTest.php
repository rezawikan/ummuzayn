<?php

namespace Tests\Unit\CustomerType;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\CustomerType;

class CustomerTypeTest extends TestCase
{
    /**
     * Customer type has customer type
     *
     * @return void
     */
    public function test_it_has_many_customers()
    {
        $type = factory(CustomerType::class)->create();

        factory(Customer::class)->create([
          'customer_type_id' => $type->id
        ]);

        factory(Customer::class)->create([
          'customer_type_id' => $type->id
        ]);

        $this->assertEquals(2, $type->customers->count());
    }
}
