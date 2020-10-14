<?php

namespace Tests\Unit\ProductImage;

use App\Models\Product;
use App\Models\ProductImage;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    /**
     * it has a product
     *
     * @return void
     */
    public function test_it_has_a_product()
    {
        $product = factory(Product::class)->create();
        $image = factory(ProductImage::class)->create([
            'product_id' => $product->id
        ]);

        $this->assertTrue($image->product()->count() > 0);
    }
}
