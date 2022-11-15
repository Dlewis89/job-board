<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->jobTitle,
            'description' => $this->faker->paragraph,
            'deadline' => $this->faker->date,
            'no_of_hires' => $this->faker->numberBetween(1, 10),
            'responsibilities' => $this->faker->paragraph,
            'benefits' => $this->faker->paragraph
        ];
    }
}
