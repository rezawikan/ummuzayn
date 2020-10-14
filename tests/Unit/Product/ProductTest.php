<?php

namespace Tests\Unit\Product;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductStatus;
use App\Models\ProductCategory;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * product has one status
     *
     * @return void
     */
    public function test_it_has_one_status()
    {
        $status = factory(ProductStatus::class)->create();
        $product = factory(Product::class)->create([
            'status_id' => $status->id
        ]);

        $this->assertEquals($product->status->id, $status->id);
        $this->assertInstanceOf(ProductStatus::class, $product->status);
    }

    /**
     * product has many product variations
     *
     * @return void
     */
    public function test_it_has_many_product_variations()
    {
        $product = factory(Product::class)->create();
        $product->product_variations()->saveMany([
            factory(ProductVariation::class)->make(),
            factory(ProductVariation::class)->make()
        ]);

        $this->assertTrue($product->has_variation_type());
        $this->assertEquals($product->status()->count(), 1);
    }

    /**
     * product has many images
     *
     * @return void
     */
    public function test_it_has_many_images()
    {
        $product = factory(Product::class)->create();
        $product->product_images()->saveMany([
            factory(ProductImage::class)->make(),
            factory(ProductImage::class)->make()
        ]);

        $this->assertTrue($product->product_images()->count() > 1);
    }
}
