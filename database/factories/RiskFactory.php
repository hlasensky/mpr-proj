<?php

namespace Database\Factories;

use App\Enums\RiskLevelEnum;
use App\Enums\RiskProbabilityEnum;
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
            'impact' => fake()->randomElement(RiskLevelEnum::cases())->value,
            'likelihood' => fake()->randomElement(RiskProbabilityEnum::cases())->value,
        ];
    }
}
