<?php

namespace Tests\Unit\Actions\Fortify;

use App\Actions\Fortify\ResetUserPassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('can reset user password', function () {
    $user = User::factory()->create();
    $oldPassword = $user->password;

    (new ResetUserPassword())->reset($user, [
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue()
        ->and($user->password)->not->toBe($oldPassword);
});

test('throws validation exception for invalid password when resetting', function () {
    $user = User::factory()->create();

    (new ResetUserPassword())->reset($user, ['password' => 'short']);
})->throws(ValidationException::class);