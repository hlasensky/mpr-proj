<?php

use App\Models\User;
use App\Models\Project;
use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user initials can be generated from name', function (string $name, string $expected) {
    $user = new User(['name' => $name]);

    expect($user->initials())->toBe($expected);
})->with([
    'two words' => ['John Doe', 'JD'],
    'one word' => ['Taylor', 'T'],
    'lowercase' => ['laravel framework', 'lf'],
    'more than two words' => ['Martin Luther King', 'ML'],
    'empty string' => ['', ''],
]);

test('user has many projects', function () {
    $user = User::factory()
        ->has(Project::factory()->count(3))
        ->create();

    expect($user->projects)->toBeInstanceOf(Collection::class)
        ->and($user->projects)->toHaveCount(3)
        ->and($user->projects->first())->toBeInstanceOf(Project::class);
});

test('user role is cast to an enum', function () {
    $user = User::factory()->create([
        'role' => 'admin',
    ]);

    expect($user->fresh()->role)
        ->toBeInstanceOf(RoleEnum::class)
        ->toBe(RoleEnum::Admin);
});