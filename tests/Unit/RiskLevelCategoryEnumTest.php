<?php

namespace Tests\Unit\Enums;

use App\Enums\RiskLevelCategoryEnum;

test('fromScore returns correct category based on score boundaries', function (int $score, RiskLevelCategoryEnum $expected) {
    expect(RiskLevelCategoryEnum::fromScore($score))->toBe($expected);
})->with([
    'lowest low' => [0, RiskLevelCategoryEnum::Low],
    'highest low' => [3, RiskLevelCategoryEnum::Low],
    'lowest medium' => [4, RiskLevelCategoryEnum::Medium],
    'highest medium' => [8, RiskLevelCategoryEnum::Medium],
    'lowest high' => [9, RiskLevelCategoryEnum::High],
    'highest high' => [14, RiskLevelCategoryEnum::High],
    'lowest danger' => [15, RiskLevelCategoryEnum::Danger],
    'highest danger' => [19, RiskLevelCategoryEnum::Danger],
    'lowest extreme' => [20, RiskLevelCategoryEnum::Extreme],
]);

test('it returns correct labels, colors, and classes per category', function (
    RiskLevelCategoryEnum $case,
    string $label,
    string $color,
    string $cellClass,
    string $chipClass
) {
    expect($case->label())->toBe($label)
        ->and($case->fluxColor())->toBe($color)
        ->and($case->cellClass())->toBe($cellClass)
        ->and($case->chipClass())->toBe($chipClass);
})->with([
    [RiskLevelCategoryEnum::Low, 'Nepravděpodobné', 'green', 'bg-green-100 dark:bg-green-900/40', 'bg-green-200 text-green-900 dark:bg-green-800 dark:text-green-100'],
    [RiskLevelCategoryEnum::Medium, 'Málo pravděpodobné', 'yellow', 'bg-yellow-100 dark:bg-yellow-900/40', 'bg-yellow-200 text-yellow-900 dark:bg-yellow-800 dark:text-yellow-100'],
    [RiskLevelCategoryEnum::High, 'Možné', 'orange', 'bg-orange-100 dark:bg-orange-900/40', 'bg-orange-200 text-orange-900 dark:bg-orange-800 dark:text-orange-100'],
    [RiskLevelCategoryEnum::Danger, 'Pravděpodobné', 'red', 'bg-red-100 dark:bg-red-900/40', 'bg-red-200 text-red-900 dark:bg-red-800 dark:text-red-100'],
    [RiskLevelCategoryEnum::Extreme, 'Velmi pravděpodobné', 'rose', 'bg-rose-200 dark:bg-rose-900/60', 'bg-rose-300 text-rose-950 dark:bg-rose-800 dark:text-rose-100'],
]);

test('matrixBand returns correct band based on score', function (int $score, int $expected) {
    expect(RiskLevelCategoryEnum::matrixBand($score))->toBe($expected);
})->with([
    'band 1 lower' => [1, 1],
    'band 1 upper' => [6, 1],
    'band 2 lower' => [7, 2],
    'band 2 upper' => [10, 2],
    'band 3 lower' => [11, 3],
    'band 3 upper' => [21, 3],
    'band 4 lower' => [22, 4],
    'band 4 upper' => [36, 4],
    'band 5 lower' => [37, 5],
    'band 5 upper' => [100, 5],
]);

test('gradientCellClass returns correct class based on score', function (int $score, string $expected) {
    expect(RiskLevelCategoryEnum::gradientCellClass($score))->toBe($expected);
})->with([
    [3, 'bg-green-300 dark:bg-green-800/50'],
    [6, 'bg-green-500 dark:bg-green-700/60'],
    [10, 'bg-lime-300 dark:bg-lime-800/50'],
    [15, 'bg-yellow-300 dark:bg-yellow-700/50'],
    [21, 'bg-yellow-500 dark:bg-yellow-600/60'],
    [28, 'bg-orange-400 dark:bg-orange-700/60'],
    [36, 'bg-orange-600 dark:bg-orange-700/70'],
    [50, 'bg-red-500 dark:bg-red-700/70'],
    [70, 'bg-red-700 dark:bg-red-800/80'],
    [100, 'bg-red-900 dark:bg-red-950/90'],
]);

test('gradientChipClass returns correct class based on score', function (int $score, string $expected) {
    expect(RiskLevelCategoryEnum::gradientChipClass($score))->toBe($expected);
})->with([
    [3, 'bg-green-100 text-green-900 dark:bg-green-700 dark:text-green-100'],
    [6, 'bg-green-200 text-green-900 dark:bg-green-600 dark:text-green-100'],
    [10, 'bg-lime-100 text-lime-900 dark:bg-lime-700 dark:text-lime-100'],
    [15, 'bg-yellow-100 text-yellow-900 dark:bg-yellow-600 dark:text-yellow-100'],
    [21, 'bg-yellow-200 text-yellow-900 dark:bg-yellow-500 dark:text-yellow-950'],
    [28, 'bg-orange-100 text-orange-900 dark:bg-orange-600 dark:text-orange-100'],
    [36, 'bg-orange-200 text-orange-900 dark:bg-orange-500 dark:text-orange-950'],
    [50, 'bg-red-100 text-red-900 dark:bg-red-600 dark:text-red-100'],
    [70, 'bg-red-200 text-red-950 dark:bg-red-500 dark:text-red-100'],
    [100, 'bg-red-300 text-red-950 dark:bg-red-400 dark:text-red-950'],
]);