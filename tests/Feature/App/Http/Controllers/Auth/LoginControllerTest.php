<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Models\User;
use App\Services\Auth\LoginService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\HasApiTokens;
use Mockery\MockInterface;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use WithFaker;
    private array $data;
    private string $route;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function setUp(): void
    {
        parent::setUp();
        $this->route = '/api/v1/auth/login';
        $this->data = [
            'email' => $this->faker->email,
            'password' => 'Password#1!1234'
        ];
    }
    public function test_user_can_not_login_without_email()
    {
        unset($this->data['email']);
        $this->postJson($this->route, $this->data)
            ->assertStatus(422)
            ->assertJson([
                "status" => false,
                "message" => "One or more fields are invalid",
                "errors" => [
                    "email" => [
                        "The email field is required."
                    ]
                ]
        ]);
    }

    public function test_user_can_not_login_without_password()
    {
        unset($this->data['password']);
        $this->postJson($this->route, $this->data)
            ->assertStatus(422)
            ->assertJson([
                "status" => false,
                "message" => "One or more fields are invalid",
                "errors" => [
                    "password" => [
                        "The password field is required."
                    ]
                ]
        ]);
    }

    public function test_user_can_login()
    {

        $user = User::factory()->create(
            $this->data
        );
        \Artisan::call('passport:install --force');

        $this->partialMock(LoginService::class, function (MockInterface $mock) {
            $mock->shouldReceive('create_token')->once()->andReturn((object)['accessToken' => "this-is-a-test-token"]);
        });


        $this->postJson($this->route, $this->data)
            ->assertStatus(200)
            ->assertJson([
                    "status" => true,
                    "message" => "login successful",
                    "data" => [
                        "token" => "this-is-a-test-token",
                        "email" => $user->email,
                    ]
            ]);
    }
}
