<?php

namespace Tests\Unit\ProductVariation;

use App\Models\Product;
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
            'product_variation_type_id' => null
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
}
