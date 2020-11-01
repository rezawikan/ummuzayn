<?php

namespace Tests\Feature\Cart;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\ProductVariation;
use App\Models\MarketplaceFee;
use App\Models\Admin;
use App\Pattern\Cart\Cart;
use App\Models\Customer;
use Tests\TestCase;

class CartTest extends TestCase
{

    /**
     * It return all customers with cart
     *
     * @return void
     */
    public function test_it_return_all_customers_with_cart()
    {
        $admin = factory(Admin::class)->create();

        $product_variations = factory(ProductVariation::class, 5)->create([
            'product_variation_type_id' => null,
            'stock' => 10
        ]);

        foreach ($product_variations as $variation) {
            $this->jsonAs($admin, 'POST', 'api/cart', [
                'customer_id' => factory(Customer::class)->create()->id,
                'products' => [
                    [
                        'id' => $variation->id,
                        'quantity' => 5
                    ]
                ]
            ]);
        }

        $this->jsonAs($admin, 'GET', 'api/cart')
        ->assertJsonCount(5, 'data')
        ->assertStatus(200);
    }

    /**
     * It return cart with specific customer
     *
     * @return void
     */
    public function test_it_return_cart_with_specific_customer()
    {
        $admin = factory(Admin::class)->create();
        $customer = factory(Customer::class)->create();

        $product_variations = factory(ProductVariation::class, 5)->create([
            'product_variation_type_id' => null,
            'stock' => 10
        ]);

        foreach ($product_variations as $variation) {
            $this->jsonAs($admin, 'POST', 'api/cart', [
                'customer_id' => $customer->id,
                'products' => [
                    [
                        'id' => $variation->id,
                        'quantity' => 5
                    ]
                ]
            ]);
        }

        $this->jsonAs($admin, 'GET', 'api/cart/'.$customer->id)
        ->assertJsonStructure([
            'data' => [
                'products' => [
                    [
                        'id',
                        'product_id',
                        'product_variation_type_id',
                        'variation_name',
                        'weight',
                        'price',
                        'stock',
                        'orderable',
                        'product',
                        'product_variation_type',
                        'quantity',
                        'total',
                        'base_total',
                    ]
                ]
            ]
        ])
        ->assertJsonCount(5, 'data.products')
        ->assertStatus(200);
    }

    /**
     * It detect stock change
     *
     * @return void
     */
    public function test_it_detect_stock_change()
    {
        $admin = factory(Admin::class)->create();
        $customer = factory(Customer::class)->create();

        $product_variations = factory(ProductVariation::class, 5)->create([
            'product_variation_type_id' => null,
            'stock' => 10
        ]);

        foreach ($product_variations as $variation) {
            $this->jsonAs($admin, 'POST', 'api/cart', [
                'customer_id' => $customer->id,
                'products' => [
                    [
                        'id' => $variation->id,
                        'quantity' => 5
                    ]
                ]
            ]);

            $variation->update([
                'stock' => 0
            ]);
        }

        $this->jsonAs($admin, 'GET', 'api/cart/'.$customer->id)
        ->assertJsonFragment([
            'changed' => true
        ])
        ->assertStatus(200);
    }

    /**
     * It sync existing quantity in cart
     *
     * @return void
     */
    public function test_it_sync_existing_quantity()
    {
        $admin = factory(Admin::class)->create();
        $customer = factory(Customer::class)->create();

        $product_variations = factory(ProductVariation::class, 5)->create([
            'product_variation_type_id' => null,
            'stock' => 10
        ]);

        // First cart
        $this->jsonAs($admin, 'POST', 'api/cart', [
            'customer_id' => $customer->id,
            'products' => [
                [
                    'id' => $product_variations[0]['id'],
                    'quantity' => 5
                ]
            ]
        ]);

        foreach ($product_variations as $variation) {
            $this->jsonAs($admin, 'POST', 'api/cart', [
                'customer_id' => $customer->id,
                'products' => [
                    [
                        'id' => $variation->id,
                        'quantity' => 5
                    ]
                ]
            ]);
        }

        $this->jsonAs($admin, 'GET', 'api/cart/'.$customer->id)
        ->assertStatus(200);
    }

    /**
     * It check if cart is empty
     *
     * @return void
     */
    public function test_it_check_cart_is_empty()
    {
        $admin = factory(Admin::class)->create();
        $customer = factory(Customer::class)->create();

        $this->jsonAs($admin, 'GET', 'api/cart/'.$customer->id)
        ->assertJsonFragment([
            'empty' => true
        ])
        ->assertStatus(200);
    }


    /**
    * It will detach existing item in cart
    *
    * @return void
    */
    public function test_it_will_detach_existing_item()
    {
        $admin = factory(Admin::class)->create();
        $customer = factory(Customer::class)->create();
        $cart = new Cart($customer);

        $product_variations = factory(ProductVariation::class, 2)->create([
            'product_variation_type_id' => null,
            'stock' => 10
        ]);

        foreach ($product_variations as $variation) {
            $this->jsonAs($admin, 'POST', 'api/cart', [
                'customer_id' => $customer->id,
                'products' => [
                    [
                        'id' => $variation->id,
                        'quantity' => 5
                    ]
                ]
            ]);
        }

        $this->jsonAs($admin, 'DELETE', 'api/cart/'.$customer->id.'/'.$product_variations[0]['id'])
        ->assertStatus(200);
        $this->assertEquals($cart->customer->cart->count(), 1);
    }

    /**
    * It will update existing item in cart
    *
    * @return void
    */
    public function test_it_will_update_existing_item()
    {
        $admin = factory(Admin::class)->create();
        $customer = factory(Customer::class)->create();
        $cart = new Cart($customer);

        $product_variation = factory(ProductVariation::class)->create([
            'product_variation_type_id' => null,
            'stock' => 10
        ]);

        $this->jsonAs($admin, 'POST', 'api/cart', [
            'customer_id' => $customer->id,
            'products' => [
                [
                    'id' => $product_variation->id,
                    'quantity' => 5
                ]
            ]
        ]);

        $this->jsonAs($admin, 'PUT', 'api/cart/'.$customer->id, [
            'product_variation_id' => $product_variation->id,
            'quantity' => 3
        ]);

        $this->jsonAs($admin, 'GET', 'api/cart/'.$customer->id)
        ->assertJsonFragment([
            'quantity' => 3
        ]);
    }

    /**
     * It return cart with specific customer using marketplace fee
     *
     * @return void
     */
    public function test_it_return_cart_with_specific_customer_with_marketplace_fee()
    {
        $admin = factory(Admin::class)->create();
        $customer = factory(Customer::class)->create();

        $product_variations = factory(ProductVariation::class, 5)->create([
            'product_variation_type_id' => null,
            'base_price' => 1000,
            'price' => 5000,
            'stock' => 10
        ]);

        $marketplace_fee = factory(MarketplaceFee::class)->create([
            'percent' => 2
        ]);

        foreach ($product_variations as $variation) {
            $this->jsonAs($admin, 'POST', 'api/cart', [
                'customer_id' => $customer->id,
                'products' => [
                    [
                        'id' => $variation->id,
                        'quantity' => 1
                    ]
                ]
            ]);
        }

        $this->jsonAs($admin, 'GET', 'api/cart/'.$customer->id, ['marketplaceFeeID' => $marketplace_fee->id ])
        ->assertJsonStructure([
            'data' => [
                'products' => [
                    [
                        'id',
                        'product_id',
                        'product_variation_type_id',
                        'variation_name',
                        'weight',
                        'price',
                        'stock',
                        'orderable',
                        'product',
                        'product_variation_type',
                        'quantity',
                        'total',
                        'base_total',
                    ]
                ]
            ]
        ])
        ->assertJsonCount(5, 'data.products')
        ->assertJsonFragment([
            'baseProfitWithFee' => 19500
        ])
        ->assertStatus(200);
    }

    /**
     * It return cart with specific customer using wrong marketplace fee id
     *
     * @return void
     */
    public function test_it_return_cart_with_specific_customer_with_wrong_marketplace_fee()
    {
        $admin = factory(Admin::class)->create();
        $customer = factory(Customer::class)->create();

        $product_variations = factory(ProductVariation::class, 5)->create([
            'product_variation_type_id' => null,
            'base_price' => 1000,
            'price' => 5000,
            'stock' => 10
        ]);

        foreach ($product_variations as $variation) {
            $this->jsonAs($admin, 'POST', 'api/cart', [
                'customer_id' => $customer->id,
                'products' => [
                    [
                        'id' => $variation->id,
                        'quantity' => 1
                    ]
                ]
            ]);
        }

        $this->jsonAs($admin, 'GET', 'api/cart/'.$customer->id, ['marketplaceFeeID' => null ])
        ->assertJsonStructure([
            'data' => [
                'products' => [
                    [
                        'id',
                        'product_id',
                        'product_variation_type_id',
                        'variation_name',
                        'weight',
                        'price',
                        'stock',
                        'orderable',
                        'product',
                        'product_variation_type',
                        'quantity',
                        'total',
                        'base_total',
                    ]
                ]
            ]
        ])
        ->assertJsonCount(5, 'data.products')
        ->assertStatus(200);
    }
}
