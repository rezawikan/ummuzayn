<?php

namespace Tests\Unit\ProductCategory;

use App\Models\ProductCategory;
use App\Models\Product;
use Tests\TestCase;

class ProductCategoryTest extends TestCase
{
    /**
     * product category has many children and nested
     *
     * @return void
     */
    public function test_it_has_children()
    {
        $category = factory(ProductCategory::class)->create();
        $category->children()->saveMany([
            $one = factory(ProductCategory::class)->make(),
            factory(ProductCategory::class)->make()
        ]);

        $one->children()->save(
            factory(ProductCategory::class)->make()
        );

        $categories = $category->childrens()->get();
        $categories_decode = json_decode( $categories, true );

        $this->assertTrue($category->children()->count() > 1);
        $this->assertArrayHasKey('childrens', $categories_decode[0]);
    }

    /**
     * product category has many products
     *
     * @return void
     */
    public function test_it_has_products()
    {
        $category = factory(ProductCategory::class)->create();
        $category->products()->saveMany([
            factory(Product::class)->make(),
            factory(Product::class)->make()
        ]);

        $this->assertTrue($category->products()->count() > 1);
    }
}
