<?php

namespace Tests\Feature\App\Http\Controller\Job;

use App\Models\Job;
use App\Models\Skill;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class JobControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_that_user_can_fetch_jobs()
    {
        $job = User::factory()
            ->has(
                Job::factory()->count(3)->state(new Sequence(['title' => 'a job title']))
                    ->hasAttached(
                        Skill::factory()->count(3),
                        [
                            'years_of_experience' => 2
                        ]
                    )
            )->create();

        $this->getJson('/api/v1/jobs')
            ->assertStatus(200)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has('status')
                    ->has('message')
                    ->has('data', 3)
                    ->has(
                        'data.0', fn($json) =>
                        $json->where('title', 'a job title')->etc()
                    )
            );
    }
}
