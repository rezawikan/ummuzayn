<?php

namespace Tests\Unit\ProductVariationType;

use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use Tests\TestCase;

class ProductVariationTypeTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_has_product_variation()
    {
        $product_variation_type = factory(ProductVariationType::class)->create();
        factory(ProductVariation::class)->create([
            'product_variation_type_id' => $product_variation_type->id
        ]);

         factory(ProductVariation::class)->create([
            'product_variation_type_id' => $product_variation_type->id
        ]);

        $this->assertTrue($product_variation_type->product_variations()->count() == 2);
    }
}
