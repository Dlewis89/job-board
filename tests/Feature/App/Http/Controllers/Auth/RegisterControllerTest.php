<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Events\WelcomeMailEvent;
use App\Listeners\WelcomeMailEventListener;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase, withFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->register_route = '/api/v1/auth/register';
        $this->data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'Password#1!',
            'password_confirmation' => 'Password#1!',
            'permission' => $this->faker->randomElement(['candidate', 'employer'])
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
        $this->postJson(
            $this->register_route,
            $this->data
        )->assertStatus(422)
        ->assertJson(
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

        $this->postJson(
            $this->register_route,
            $this->data
        )->assertStatus(422)
        ->assertJson(
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
        $this->data['email'] = 'invalid email';

        $this->postJson(
            $this->register_route,
            $this->data
        )->assertStatus(422)
        ->assertJson(
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
        User::factory()->create(
            [
                'email' => $this->data['email']
            ]
        );

        $this->postJson(
            $this->register_route,
            $this->data
        )->assertStatus(422)
        ->assertJson(
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
        $this->postJson(
            $this->register_route,
            $this->data
        )->assertStatus(422)
        ->assertJson(
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
        $this->postJson(
            $this->register_route,
            $this->data
        )->assertStatus(422)
        ->assertJson(
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

        $this->postJson(
            $this->register_route,
            $this->data
        )->assertStatus(422)
        ->assertJson(
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

        $this->postJson(
            $this->register_route,
            $this->data
        )->assertStatus(422)
        ->assertJson(
            [
                "errors" => [
                    "permission" => [
                        "The permission field is required."
                    ]
                ]
            ]
        );
    }

    public function test_user_is_created_successfully()
    {
        \Artisan::call('passport:install --force');
        Event::fake(WelcomeMailEvent::class);
        $this->postJson($this->register_route, $this->data)
            ->assertValid()
            ->assertStatus(201)
            ->assertJson(
                [
                    "status" => true,
                    "message" => "user created",
                    "data" => [
                        // "token" => "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZTFhOWJkOTViODVlZGViMTZkYjQ0NmFhODc2MDYzYWZiOTc0YTQ4Y2QwOWMxM2U3NWM3MGE0MjRiMWIyZWUwYzM5YzU2NmI3ZmNhNDQxMDgiLCJpYXQiOjE2Njc5MjQzMDMuNjQ2NzgsIm5iZiI6MTY2NzkyNDMwMy42NDY3ODMsImV4cCI6MTY2ODAxMDcwMy42MzQ1MDMsInN1YiI6IjgiLCJzY29wZXMiOltdfQ.Lz3q0VlFFppDgTjSJhxfWzQxWF2v3OCbqk67itU4HpopETzs2yabc8d8zXAhu-xjRFg4uhp7Nv003WoZlRdbxveBX1tUzvP3EZi05HAXXxxAWvUeQMT39vNBe4YQKdMMF-VFmEbioS4zv4-bS1bJffUMNW8YuqAmll_r3P7IoENnPU5sdFhoSehbow-239kXEhvCISlAT7gGr1lptHI7KGEZd4KP4hWTadEJpHrC70bh1HSG5TRQW7xCniD-XSF1iXE9k3s3uArtLmXuzEYcJ4yNvZH0lR7cFmxAo0kDOlKc1-3WJx2MkMX1dttY7p00Kfbex53HPHCYIFmCicABM_oyeTNeZ5wqYpagXLHrNqXUN0QQODdy1BT1-5lky0k_finu_g5embHOFEtJiWaHzcniktcHnKXBItJY0F6llCMl1mZ5qE-BRPHm78iwNb14O5d7uR7V_IMOPlcsCw0xuMeIxXpylqyGmycSzeD-EauoRURuCpmDyki2FqOU7d6sWsWj3O3j_Knk3oEcvz_ZKs5o4eHZ0DAELCEbfyCHNzVqisjTlqktQo-jVgws0Gaz-_OZgSEgoYWAZpR46-MWnKHIhMB_kcGtVs3LJRnb1SA6JJsYQ7P6JsujwwJLWKCipBg3MVuP9aX7iLsjDOE1zDhzmqNQvPm3cr6zmnKUta4",
                        "name" => $this->data['name'],
                        "email" => $this->data['email'],
                        "permissions" => [
                            [
                                "name" => $this->data['permission'],
                                "guard_name" => "api",
                            ]
                        ]
                    ]
                ]
            );

            Event::assertListening(WelcomeMailEvent::class, WelcomeMailEventListener::class );
            Event::assertDispatched(function (WelcomeMailEvent $event){
                return $event->user->name === $this->data['name'];
            });

            // mock the hash facade and mock passport token
            unset($this->data['password']);
            unset($this->data['password_confirmation']);
            unset($this->data['permission']);
            $this->assertDatabaseHas(User::class, $this->data);

    }
}
