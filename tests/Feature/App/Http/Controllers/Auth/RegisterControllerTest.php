<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->register_route = '/api/v1/auth/register';
        $this->data = [
            'name' => 'Test user',
            'email' => 'test@test.com',
            'password' => 'Password#1!',
            'password_confirmation' => 'Password#1!',
            'permission' => 'candidate'
        ];
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_not_register_without_name()
    {
        unset($this->data['name']);
        $response = $this->postJson(
            $this->register_route,
            $this->data
        );

        $response->assertStatus(422);
        $response->assertJson(
            [
                "errors" => [
                    "name" => [
                        "The name field is required."
                    ]
                ]
            ]
        );
    }

    public function test_user_can_not_register_without_email()
    {
        unset($this->data['email']);

        $response = $this->postJson(
            $this->register_route,
            $this->data
        );

        $response->assertStatus(422);
        $response->assertJson(
            [
                "errors" => [
                    "email" => [
                        "The email field is required."
                    ]
                ]
            ]
        );
    }

    public function test_user_can_not_register_without_valid_email()
    {
        $this->data['email'] = 'asdfwerasdf';

        $response = $this->postJson(
            $this->register_route,
            $this->data
        );

        $response->assertStatus(422);
        $response->assertJson(
            [
                "errors" => [
                    "email" => [
                        "The email must be a valid email address."
                    ]
                ]
            ]
        );
    }

    public function test_user_email_is_unique()
    {
        User::factory()->create([
            'email' => 'test@test.com'
        ]);

        $response = $this->postJson(
            $this->register_route,
            $this->data
        );

        $response->assertStatus(422);
        $response->assertJson(
            [
                "errors" => [
                    "email" => [
                        "The email has already been taken."
                    ]
                ]
            ]
        );
    }

    public function test_user_can_not_register_without_password()
    {
        unset($this->data['password']);
        $response = $this->postJson(
            $this->register_route,
            $this->data
        );

        $response->assertStatus(422);
        $response->assertJson(
            [
                "errors" => [
                    "password" => [
                        "The password field is required."
                    ]
                ]
            ]
        );
    }

    public function test_user_can_not_register_without_password_confirmation()
    {
        unset($this->data['password_confirmation']);
        $response = $this->postJson(
            $this->register_route,
            $this->data
        );

        $response->assertStatus(422);
        $response->assertJson(
            [
                "errors" => [
                    "password" => [
                        "The password confirmation does not match."
                    ]
                ]
            ]
        );
    }

    public function test_user_password_must_be_equal_to_or_more_than_eight_characters()
    {
        $this->data['password'] = 'passw';
        $this->data['password_confirmation'] = 'passw';

        $response = $this->postJson(
            $this->register_route,
            $this->data
        );

        $response->assertStatus(422);
        $response->assertJson(
            [
                "errors" => [
                    "password" => [
                        "The password must be at least 8 characters."
                    ]
                ]
            ]
        );
    }

    public function test_user_can_not_register_without_permission()
    {
        unset($this->data['permission']);

        $response = $this->postJson(
            $this->register_route,
            $this->data
        );

        $response->assertStatus(422);
        $response->assertJson(
            [
                "errors" => [
                    "permission" => [
                        "The permission field is required."
                    ]
                ]
            ]
        );
    }
}
