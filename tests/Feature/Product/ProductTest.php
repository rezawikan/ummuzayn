<?php

namespace Tests\Feature\Product;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Product;
use App\Models\Admin;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * It returns many products with pagination
     *
     * @return void
     */
    public function test_it_returns_products_with_pagination()
    {
        $admin = factory(Admin::class)->create();
        factory(Product::class, 10)->create();
        
        $this->jsonAs($admin, 'GET', 'api/products')
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'product_category_id',
                    'product_status_id',
                    'name',
                    'description',
                    'has_variation_type',
                    'status',
                    'product_images',
                    'product_variations',
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
     * It returns many products without pagination
     *
     * @return void
     */
    public function test_it_returns_products_without_pagination()
    {
        $admin = factory(Admin::class)->create();
        factory(Product::class, 10)->create();
        
        $this->jsonAs($admin, 'GET', 'api/products?paginate=false')
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'product_category_id',
                    'product_status_id',
                    'name',
                    'description',
                    'has_variation_type',
                    'status',
                    'product_images',
                    'product_variations',
                ]
            ]
        ])
        ->assertStatus(200)
        ->assertJsonCount(10, 'data');
    }
}
