<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Risk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Risk>
 */
class RiskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(4, false),
            'user_id' => User::factory(),
            'project_id' => Project::factory(),
            'impact' => fake()->numberBetween(1, 5),
            'likelihood' => fake()->numberBetween(1, 5),
        ];
    }
}
