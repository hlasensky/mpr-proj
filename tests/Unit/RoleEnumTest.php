<?php

namespace Tests\Unit\Enums;

use App\Enums\RoleEnum;

test('it returns correct labels and colors per role', function (RoleEnum $case, string $label, string $color) {
    expect($case->label())->toBe($label)
        ->and($case->color())->toBe($color);
})->with([
    [RoleEnum::UnVerified, 'Neověřený', 'zinc'],
    [RoleEnum::Manager, 'Manažer', 'blue'],
    [RoleEnum::Admin, 'Admin', 'violet'],
]);

test('it returns assignable roles', function () {
    $assignable = RoleEnum::assignable();

    expect($assignable)->toBeArray()
        ->and($assignable)->not->toContain(RoleEnum::UnVerified)
        ->and($assignable)->toContain(RoleEnum::Manager, RoleEnum::Admin);
});