<?php

namespace Tests\Unit\Actions\Fortify;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('can create a new user', function () {
    $action = new CreateNewUser();

    $user = $action->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    expect($user)->toBeInstanceOf(User::class);
    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
});

test('throws validation exception for invalid data when creating user', function () {
    (new CreateNewUser())->create(['email' => 'not-an-email']);
})->throws(ValidationException::class);