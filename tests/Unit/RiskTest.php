<?php

namespace Tests\Unit;

use App\Enums\RiskLevelCategoryEnum;
use App\Models\Project;
use App\Models\Risk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('risk belongs to a user', function () {
    $risk = Risk::factory()->create();

    expect($risk->user)->toBeInstanceOf(User::class);
});

test('risk belongs to a project', function () {
    $risk = Risk::factory()->create();

    expect($risk->project)->toBeInstanceOf(Project::class);
});

test('risk score is calculated correctly', function () {
    $risk = new Risk(['impact' => 3, 'likelihood' => 4]);

    expect($risk->score())->toBe(12);
});

test('risk category is determined from score', function (int $impact, int $likelihood, RiskLevelCategoryEnum $expectedCategory) {
    $risk = new Risk(['impact' => $impact, 'likelihood' => $likelihood]);

    expect($risk->riskCategory())->toBe($expectedCategory);
})->with([
    'low' => [1, 3, RiskLevelCategoryEnum::Low], // score 3
    'medium' => [2, 4, RiskLevelCategoryEnum::Medium], // score 8
    'high' => [3, 4, RiskLevelCategoryEnum::High], // score 12
    'danger' => [4, 4, RiskLevelCategoryEnum::Danger], // score 16
    'extreme' => [5, 5, RiskLevelCategoryEnum::Extreme], // score 25
]);

test('risk attributes are cast correctly', function () {
    $risk = Risk::factory()->create();

    expect($risk->impact)->toBeInt()
        ->and($risk->likelihood)->toBeInt();
});