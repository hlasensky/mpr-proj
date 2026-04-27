<?php

namespace App\Enums;

enum RiskProbabilityEnum: int
{
    case one = 1;
    case two = 2;
    case three = 3;
    case four = 4;
    case five = 5;
    case six = 6;
    case seven = 7;
    case eight = 8;
    case nine = 9;
    case ten = 10;

    public function riskCategory(): RiskProbabilityCategoryEnum
    {
        return match ($this) {
            self::one, self::two => RiskProbabilityCategoryEnum::Low,
            self::three, self::four => RiskProbabilityCategoryEnum::Medium,
            self::five, self::six => RiskProbabilityCategoryEnum::High,
            self::seven, self::eight => RiskProbabilityCategoryEnum::Danger,
            self::nine, self::ten => RiskProbabilityCategoryEnum::Extreme,

        };
    }
}
