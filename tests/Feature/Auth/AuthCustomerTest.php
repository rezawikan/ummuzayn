<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use App\Models\CustomerType;
use App\Models\Customer;
use App\Models\Admin;
use Tests\TestCase;

class AuthCustomerTest extends TestCase
{
    /**
    * It Required an email
    *
    * @return void
    */
    public function test_it_requires_an_email()
    {
        $this->json('POST', 'api/customer/login')
        ->assertJsonValidationErrors(['email']);
    }

    /**
    * It return a validation error if credentials doesn't match
    *
    * @return void
    */
    public function test_it_returns_a_validation_error_if_credentials_doesnt_match()
    {
        $customer = factory(Customer::class)->create();

        $this->json('POST', 'api/customer/login', [
          'email' => $customer->email,
          'password' => 'none'
        ])->assertJsonValidationErrors(['email']);
    }

    /**
    * It return a valid response
    *
    * @return void
    */
    public function test_it_returns_a_valid_reponse()
    {
        $customer = factory(Customer::class)->create();

        $this->json('POST', 'api/customer/login', [
            'email' => $customer->email,
            'password' => 'password'
        ])->assertJsonStructure([
            'data' => [
                'id',
                'email',
                'name',
                'phone',
                'picture'
            ],
            'meta' => [
                'token'
            ]
        ]);
    }

    /**
    * It return a valid response
    *
    * @return void
    */
    public function test_it_returns_a_valid_reponse_from_forgot_password()
    {
        $customer = factory(Customer::class)->create();

        $this->json('POST', 'api/customer/forgot', [
            'email' => $customer->email,
        ])->assertJsonStructure([
            'message',
            'status'
        ]);
    }

    /**
    * It return a valid response
    *
    * @return void
    */
    public function test_it_returns_a_valid_reponse_from_reset_password()
    {
        $customer = factory(Customer::class)->create();
        $token = Password::broker('customers')->createToken($customer);

        $this->json('POST', 'api/customer/reset', [
            'email' => $customer->email,
            'token' => $token,
            'password' => 'password',
            'password_confirmation' => 'password'
        ])->assertJsonStructure([
            'message',
            'status'
        ]);
    }

    /**
     * It return a valid registration for customer
     *
     * @return void
     */
    public function test_it_returns_valid_registration_customer()
    {
        $admin = factory(Admin::class)->create();
        $type = factory(CustomerType::class)->create();
        $registered = $this->jsonAs($admin, 'POST', 'api/auth/register-user', [
            'name' => 'testing',
            'email' =>'testing@gmail.com',
            'password' => 'password',
            'phone' => 435345345,
            'customer_type_id' => $type->id
        ])->assertJsonStructure([
            'id',
            'name',
            'email',
            'phone',
            'updated_at',
            'created_at'
        ]);
    }

    /**
    * it will update admin detail.
    *
    * @return void
    */
    public function test_it_update_customer_details()
    {
        $customer = factory(Customer::class)->create();

        $this->jsonAs($customer, 'PUT', 'api/customer/update/'.$customer->id, [
            'name' => 'john'
        ])->assertStatus(200);
    }

    /**
    * unauthenticated
    *
    * @return void
    */
    public function test_it_fails_if_user_isnt_authenticated()
    {
        $this->json('GET', 'api/customer/me')
        ->assertUnauthorized();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_returns_user_details()
    {
        $customer = factory(Customer::class)->create();
        $token = auth($customer->getTable())->tokenById($customer->id);

        $this->jsonAs($customer, 'GET', 'api/customer/me')
        ->assertJsonFragment([
          'email' => $customer->email
        ]);

        $this->json('POST', 'api/customer/logout', [], [
            'Authorization' => 'Bearer ' . $token
        ])->assertStatus(200);
    }
}
