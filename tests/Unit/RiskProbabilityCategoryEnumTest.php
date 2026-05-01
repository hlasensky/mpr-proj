<?php

namespace Tests\Unit\Enums;

use App\Enums\RiskProbabilityCategoryEnum;

test('it returns correct labels', function (RiskProbabilityCategoryEnum $case, string $expected) {
    expect($case->label())->toBe($expected);
})->with([
    [RiskProbabilityCategoryEnum::Low, 'Nepravděpodobné'],
    [RiskProbabilityCategoryEnum::Medium, 'Málo pravděpodobné'],
    [RiskProbabilityCategoryEnum::High, 'Možné'],
    [RiskProbabilityCategoryEnum::Danger, 'Pravděpodobné'],
    [RiskProbabilityCategoryEnum::Extreme, 'Velmi pravděpodobné'],
]);