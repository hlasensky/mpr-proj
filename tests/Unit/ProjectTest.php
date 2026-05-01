<?php

namespace Tests\Unit;

use App\Models\Risk;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

test('project belongs to a user', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);

    expect($project->user)->toBeInstanceOf(User::class)
        ->and($project->user->id)->toBe($user->id);
});

test('project has many risks', function () {
    $project = Project::factory()
        ->has(Risk::factory()->count(2))
        ->create();

    expect($project->risks)->toBeInstanceOf(Collection::class)
        ->and($project->risks)->toHaveCount(2)
        ->and($project->risks->first())->toBeInstanceOf(Risk::class);
});

test('project date attributes are cast to carbon instances', function () {
    $project = Project::factory()->create([
        'start_date' => '2024-01-01',
        'end_date' => '2024-12-31',
    ]);

    $project = $project->fresh();

    expect($project->start_date)->toBeInstanceOf(Carbon::class)
        ->and($project->end_date)->toBeInstanceOf(Carbon::class);
});