<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-1 year', 'now');

        return [
            'name' => fake()->words(3, true),
            'user_id' => User::factory(),
            'description' => fake()->sentences(2, true),
            'start_date' => $start,
            'end_date' => fake()->optional(0.7)->dateTimeBetween($start, '+2 years'),
        ];
    }
}
