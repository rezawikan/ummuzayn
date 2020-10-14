<?php

namespace Tests\Feature\ProductCategory;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Admin;
use App\Models\ProductCategory;
use Tests\TestCase;

class ProductCategoryTest extends TestCase
{
    /**
     * It returns many product categories with pagination
     *
     * @return void
     */
    public function test_it_returns_product_category_with_pagination()
    {
        $admin = factory(Admin::class)->create();
        factory(ProductCategory::class, 10)->create();
        
        $this->jsonAs($admin, 'GET', 'api/product/categories')
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'slug',
                    'parent_id'
                ]
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next'
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'path',
                'per_page',
                'to',
                'total'
            ]
        ])
        ->assertStatus(200);
    }

    /**
     * It returns many product categories without pagination
     *
     * @return void
     */
    public function test_it_returns_product_category_without_pagination()
    {
        $admin = factory(Admin::class)->create();
        factory(ProductCategory::class, 10)->create();
        
        $this->jsonAs($admin, 'GET', 'api/product/categories?paginate=false')
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'slug',
                    'parent_id'
                ]
            ]
        ])
        ->assertStatus(200)
        ->assertJsonCount(10, 'data');
    }

    /**
     * It returns product categories with children
     *
     * @return void
     */
    public function test_it_return_product_category_with_children()
    {
        $admin = factory(Admin::class)->create();
        $parent = factory(ProductCategory::class)->create();
        factory(ProductCategory::class, 10)->create(['parent_id' => $parent->id]);
        
        $this->jsonAs($admin, 'GET', 'api/product/categories?parent=true')
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'slug',
                    'parent_id',
                    'children'
                ]
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next'
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'path',
                'per_page',
                'to',
                'total'
            ]
        ])
        ->assertStatus(200);
    }


    /**
    * It store product category
    *
    * @return void
    */
    public function test_it_store_product_category()
    {
        $admin = factory(Admin::class)->create();
        $this->jsonAs($admin, 'POST', 'api/product/categories', [
            'name' => 'name',
            'slug' => 'name',
        ])
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
                'parent_id'
            ]
        ])
        ->assertStatus(201);
    }

    /**
     * It show category
     *
     * @return void
     */
    public function test_it_show_category()
    {
        $admin = factory(Admin::class)->create();
        $productCategory = factory(ProductCategory::class)->create();

        $this->jsonAs($admin, 'GET', 'api/product/categories/'.$productCategory->id)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
                'parent_id'
            ]
        ])
        ->assertStatus(200);
    }

    /**
     * It update product category
     *
     * @return void
     */
    public function test_it_update_product_category()
    {
        $admin = factory(Admin::class)->create();
        $productCategory = factory(ProductCategory::class)->create();

        $tes = $this->jsonAs($admin, 'PUT', 'api/product/categories/'.$productCategory->id, [
            'name' => 'slug',
            'slug' => 'slug'
        ])
        ->assertJsonStructure([
            'data'
        ])
        ->assertStatus(200);
    }

    /**
     * It destroy product category
     *
     * @return void
     */
    public function test_it_destroy_product_category()
    {
        $admin = factory(Admin::class)->create();
        $productCategory = factory(ProductCategory::class)->create();

        $this->jsonAs($admin, 'DELETE', 'api/product/categories/'.$productCategory->id)
        ->assertJsonStructure([
            'data'
        ])
        ->assertStatus(200);
    }

    /**
     * It destroy product category
     *
     * @return void
     */
    public function test_it_destroy_invalid_when_category_has_children()
    {
        $admin = factory(Admin::class)->create();
        $parent = factory(ProductCategory::class)->create();
        factory(ProductCategory::class, 10)->create(['parent_id' => $parent->id]);

        $this->jsonAs($admin, 'DELETE', 'api/product/categories/'.$parent->id)
        ->assertJsonStructure([
            'data'
        ])
        ->assertStatus(422);
    }
}
