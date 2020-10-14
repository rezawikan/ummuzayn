<?php

namespace Tests\Unit\ProductVariation;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use Tests\TestCase;

class ProductVariationTest extends TestCase
{
    /**
     * It has not variation type
     *
     * @return void
     */
    public function test_it_has_variation_type()
    {
        $product_variation = factory(ProductVariation::class)->create([
            'product_variation_type_id' => NULL
        ]);

        $this->assertTrue(!$product_variation->hasType);
        $this->assertEquals($product_variation->product_variation_type()->count(), 0);
    }

    /**
     * It has product
     *
     * @return void
     */
    public function test_it_has_product()
    {
        $product = factory(Product::class)->create();
        $product_variation = factory(ProductVariation::class)->create([
            'product_id' => $product->id
        ]);

        $this->assertInstanceOf(Product::class, $product_variation->product);
        $this->assertEquals($product_variation->product()->count(), 1);
    }

    /**
     * check stocks and live stocks
     *
     * @return void
     */
    public function test_check_stock_and_live_stocks()
    {
        $product_variation = factory(ProductVariation::class)->create();
        $stock1 = factory(ProductStock::class)->create([
            'product_variation_id' => $product_variation->id,
            'quantity' => 10
        ]); 
        $stock2 = factory(ProductStock::class)->create([
            'product_variation_id' => $product_variation->id,
            'quantity' => 10
        ]); 

        $all_stocks = $stock1->quantity + $stock2->quantity;
        $this->assertTrue($product_variation->live_stocks() == $all_stocks);
        $this->assertEquals($product_variation->stocks()->count(), 2);
    }
}
