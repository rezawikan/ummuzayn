<?php

namespace Tests\Unit\ProductStock;

use App\Models\ProductVariation;
use App\Models\ProductStock;
use Tests\TestCase;

class ProductStockTest extends TestCase
{
    /**
     * It has product variation.
     *
     * @return void
     */
    public function test_it_has_product_variation()
    {
        $product_variation = factory(ProductVariation::class)->create();
        $stock1 = factory(ProductStock::class)->create([
            'product_variation_id' => $product_variation->id,
            'quantity' => 10
        ]); 

        $this->assertEquals($stock1->product_variation()->count(), 1);
    }
}