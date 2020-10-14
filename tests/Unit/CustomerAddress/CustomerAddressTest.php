<?php

namespace Tests\Unit\CustomerAddress;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\Subdistrict;
use App\Models\CustomerAddress;

class CustomerAddressTest extends TestCase
{
    /**
     * Customer address has customer type
     *
     * @return void
     */
    public function test_it_has_a_customer()
    {
        $address = factory(CustomerAddress::class)->create([
          'customer_id' => factory(Customer::class)->create()->id
        ]);

        $this->assertEquals(1, $address->customer()->count());
        $this->assertInstanceOf(Customer::class, $address->customer);
    }

    /**
     * Customer address has relation to customer type
     *
     * @return void
     */
    public function test_it_has_a_subdistrict()
    {
        $address = factory(CustomerAddress::class)->create([
          'subdistrict_id' => factory(Subdistrict::class)->create()->id
        ]);

        $this->assertEquals(1, $address->subdistrict()->count());
        $this->assertInstanceOf(Subdistrict::class, $address->subdistrict);
    }
}
