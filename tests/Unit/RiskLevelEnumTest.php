<?php

namespace Tests\Unit\Enums;

use App\Enums\RiskLevelCategoryEnum;
use App\Enums\RiskLevelEnum;

test('it returns correct risk category', function (RiskLevelEnum $case, RiskLevelCategoryEnum $expected) {
    expect($case->riskCategory())->toBe($expected);
})->with([
    [RiskLevelEnum::one, RiskLevelCategoryEnum::Low],
    [RiskLevelEnum::two, RiskLevelCategoryEnum::Low],
    [RiskLevelEnum::three, RiskLevelCategoryEnum::Medium],
    [RiskLevelEnum::four, RiskLevelCategoryEnum::Medium],
    [RiskLevelEnum::five, RiskLevelCategoryEnum::High],
    [RiskLevelEnum::six, RiskLevelCategoryEnum::High],
    [RiskLevelEnum::seven, RiskLevelCategoryEnum::Danger],
    [RiskLevelEnum::eight, RiskLevelCategoryEnum::Danger],
    [RiskLevelEnum::nine, RiskLevelCategoryEnum::Extreme],
    [RiskLevelEnum::ten, RiskLevelCategoryEnum::Extreme],
]);

test('it returns correct band label', function (int $value, string $expected) {
    expect(RiskLevelEnum::bandLabel($value))->toBe($expected);
})->with([
    [2, 'Velmi nízký'],
    [4, 'Nízký'],
    [6, 'Střední'],
    [8, 'Vysoký'],
    [10, 'Velmi vysoký'],
]);

test('it returns correct bands', function () {
    expect(RiskLevelEnum::bands())->toBe([
        ['label' => 'Velmi nízký',  'min' => 1, 'max' => 2],
        ['label' => 'Nízký',        'min' => 3, 'max' => 4],
        ['label' => 'Střední',      'min' => 5, 'max' => 6],
        ['label' => 'Vysoký',       'min' => 7, 'max' => 8],
        ['label' => 'Velmi vysoký', 'min' => 9, 'max' => 10],
    ]);
});