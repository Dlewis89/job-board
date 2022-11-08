<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Models\User;
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

        $mock = $this->mock(HasApiTokens::class, function (MockInterface $mock) {
            $mock->shouldReceive('createToken')->withAnyArgs()->andReturn(['accessToken' => "this-is-a-token"]);
        });


        $this->postJson($this->route, $this->data)
            ->assertStatus(200)
            ->assertJson([
                    "status" => true,
                    "message" => "login successful",
                    "data" => [
                        // "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNGIyYmM4ZGYxOWNjMDg5OTFlZjQ2OTZjOTNiMGI3ZjdhMDIzYzUzOTQyYWU1Njk0Yjk3MGY4N2IwOTRiZDcwZjVlMzZhZTBlNWNkYTZjZDUiLCJpYXQiOjE2Njc5Mjc4NTAuODk0NjA2LCJuYmYiOjE2Njc5Mjc4NTAuODk0NjE1LCJleHAiOjE2NjgwMTQyNTAuODc3MTA4LCJzdWIiOiI4Iiwic2NvcGVzIjpbXX0.BF9kCXxnuFFDi_pWrdiXk1z_4aLUNoDrEeBrJK1jLBpLv1LJAJBQdgiVoZhaTta9NgIstSqPE3t7OuI5_0eG7-WGJSFoTbXxjvX4KthBHaocBQOENV4NVUb_RFxFCky2KLs7pFhosjUQOT55uyCCuofB4pNmfBPhCU-1KMhT-R6OTFj7ZBauGC7tHtKDSW75JSrOI41Y0tfefeIc-jNnLh-lgMui5JF6eVxc7TfUHUXyCoaMxgbGpQ_Qxmri2319tKkxrwXOrcjW8y86SHtM-anhgR6hR4Qlj_52aCt4__i5WgHGLVZsQQSwRI1iztoqQOKj3HHJW48F2rKDGSeRYpqEj16Z8bnDO-IqgkmrgGmVgzD_r554Sx4wsFc0YzlWfvY_VdypwaAZhqiG4YjRowoqSc9jy-OWVqDC8y0hox5DOciJ8pjf-hKsFD3TTNXPOPecBQq3iweTPrOBbD7660Cnl_CAQIRsb5iLZqCJa_d2gYNlY0BPNCkZI0qsqQtr542Tok4Mk_E_PnpC0mYBDqHu--Z4K3BDszcshyGRy_0mBEhCH6frQtvxSBxOrypiUymKO2AFVxDYjxUO87LaBQuD8m-avwOtAyZ7yAuvkWqPj3PpDARf_t26N4xX_4wPHASi3UL23qNIIgJ-v0zUGYlzDOTfBXXwwnqiDsrI7Q4",
                        "email" => $user->email,
                    ]
            ]);
    }
}
