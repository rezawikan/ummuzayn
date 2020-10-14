<?php

namespace Tests\Unit\ProductStatus;

use App\Models\Product;
use App\Models\ProductStatus;
use Tests\TestCase;

class ProductStatusTest extends TestCase
{
    /**
     * it has many products
     *
     * @return void
     */
    public function test_it_has_many_products()
    {
        $status = factory(ProductStatus::class)->create();

        $status->products()->saveMany([
            factory(Product::class)->create(),
            factory(Product::class)->create()
        ]);
        
        $this->assertEquals($status->products()->count(), 2);
        $this->assertIsObject($status->products);
    }
}
