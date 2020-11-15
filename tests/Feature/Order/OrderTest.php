<?php

namespace Tests\Feature\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use App\Pattern\Cart\Cart;
use App\Models\Customer;
use App\Models\ProductVariation;
use App\Models\MarketplaceFee;
use App\Models\OrderStatus;

class OrderTest extends TestCase
{
    /**
     * proceed an order without marketplace fee
     *
     * @return void
     */
    public function test_it_can_proceed_an_order()
    {
        $admin = factory(Admin::class)->create();
        $customer = factory(Customer::class)->create();

        $product_variations = factory(ProductVariation::class, 5)->create([
            'product_variation_type_id' => null,
            'stock' => 10
        ]);

        $order_status = factory(OrderStatus::class)->create();

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

        $this->jsonAs($admin, 'POST', 'api/order', [
            'customer_id' => $customer->id,
            'order_status_id' => $order_status->id
        ])->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'order_status_id' => $order_status->id
        ]);
    }

    /**
     * proceed an order with marketplace fee
     *
     * @return void
     */
    public function test_it_can_proceed_an_order_with_marketplace_fee()
    {
        $admin = factory(Admin::class)->create();
        $customer = factory(Customer::class)->create();

        $product_variations = factory(ProductVariation::class, 5)->create([
            'product_variation_type_id' => null,
            'stock' => 10
        ]);

        $order_status = factory(OrderStatus::class)->create();
        $marketplace_fee = factory(MarketplaceFee::class)->create();

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

        $this->jsonAs($admin, 'POST', 'api/order', [
            'customer_id' => $customer->id,
            'order_status_id' => $order_status->id,
            'marketplace_fee_id' => $marketplace_fee->id,
            'discount' => 500
        ])->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'order_status_id' => $order_status->id,
            'discount' => 500
        ]);
    }
}
