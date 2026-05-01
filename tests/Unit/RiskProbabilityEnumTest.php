<?php

namespace Tests\Unit\Enums;

use App\Enums\RiskProbabilityCategoryEnum;
use App\Enums\RiskProbabilityEnum;

test('it returns correct risk category', function (RiskProbabilityEnum $case, RiskProbabilityCategoryEnum $expected) {
    expect($case->riskCategory())->toBe($expected);
})->with([
    [RiskProbabilityEnum::one, RiskProbabilityCategoryEnum::Low],
    [RiskProbabilityEnum::two, RiskProbabilityCategoryEnum::Low],
    [RiskProbabilityEnum::three, RiskProbabilityCategoryEnum::Medium],
    [RiskProbabilityEnum::four, RiskProbabilityCategoryEnum::Medium],
    [RiskProbabilityEnum::five, RiskProbabilityCategoryEnum::High],
    [RiskProbabilityEnum::six, RiskProbabilityCategoryEnum::High],
    [RiskProbabilityEnum::seven, RiskProbabilityCategoryEnum::Danger],
    [RiskProbabilityEnum::eight, RiskProbabilityCategoryEnum::Danger],
    [RiskProbabilityEnum::nine, RiskProbabilityCategoryEnum::Extreme],
    [RiskProbabilityEnum::ten, RiskProbabilityCategoryEnum::Extreme],
]);

test('it returns correct band label', function (int $value, string $expected) {
    expect(RiskProbabilityEnum::bandLabel($value))->toBe($expected);
})->with([
    [2, 'Velmi nízká'],
    [4, 'Nízká'],
    [6, 'Střední'],
    [8, 'Velká'],
    [10, 'Velmi velká'],
]);

test('it returns correct bands', function () {
    expect(RiskProbabilityEnum::bands())->toBe([
        ['label' => 'Velmi velká', 'min' => 9, 'max' => 10],
        ['label' => 'Velká',       'min' => 7, 'max' => 8],
        ['label' => 'Střední',     'min' => 5, 'max' => 6],
        ['label' => 'Nízká',       'min' => 3, 'max' => 4],
        ['label' => 'Velmi nízká', 'min' => 1, 'max' => 2],
    ]);
});

test('it returns correct options', function () {
    expect(RiskProbabilityEnum::options())->toBe([
        ['label' => 'Velmi nízká', 'min' => 1, 'max' => 2,  'value' => 2],
        ['label' => 'Nízká',       'min' => 3, 'max' => 4,  'value' => 4],
        ['label' => 'Střední',     'min' => 5, 'max' => 6,  'value' => 6],
        ['label' => 'Velká',       'min' => 7, 'max' => 8,  'value' => 8],
        ['label' => 'Velmi velká', 'min' => 9, 'max' => 10, 'value' => 10],
    ]);
});