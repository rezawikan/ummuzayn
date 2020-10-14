<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\CustomerType;
use App\Models\Admin;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * It Required an email
     *
     * @return void
     */
    public function test_it_requires_an_email()
    {
        $this->json('POST', 'api/auth/login')
        ->assertJsonValidationErrors(['email']);
    }

    /**
     * It return a validation error if credentials doesn't match
     *
     * @return void
     */
    public function test_it_returns_a_validation_error_if_credentials_doesnt_match()
    {
        $admin = factory(Admin::class)->create();

        $this->json('POST', 'api/auth/login', [
          'email' => $admin->email,
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
        $admin = factory(Admin::class)->create();

        $this->json('POST', 'api/auth/login', [
            'email' => $admin->email,
            'password' => 'password'
        ])->assertJsonStructure([
            'data' => [
                'id',
                'email',
                'name',
                'scope',
                'picture'
            ],
            'meta' => [
                'token'
            ]
        ]);
    }

    /**
     * It return a valid registration
     *
     * @return void
     */
    public function test_it_returns_valid_registration()
    {
        $admin = factory(Admin::class)->create();
        $this->jsonAs($admin, 'POST', 'api/auth/register', [
            'name' => 'testing',
            'email' =>'testing@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ])->assertJsonStructure([
            'id',
            'name',
            'email',
            'updated_at',
            'created_at'
        ]);
    }

    /**
     * unauthenticated
     *
     * @return void
     */
    public function test_it_fails_if_user_isnt_authenticated()
    {
        $this->json('GET', 'api/auth/me')
        ->assertUnauthorized();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_returns_user_details()
    {
        $admin = factory(Admin::class)->create();

        $this->jsonAs($admin, 'GET', 'api/auth/me')
        ->assertJsonFragment([
          'email' => $admin->email
        ]);
    }

    /**
     * it will update admin detail.
     *
     * @return void
     */
    public function test_it_update_admin_details()
    {
        $admin = factory(Admin::class)->create();

        $this->jsonAs($admin, 'PUT', 'api/auth/update/'.$admin->id, [
            'name' => 'john'
        ])->assertJsonStructure([
            'data'
        ])
        ->assertStatus(200);
    }
}
