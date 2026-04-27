<?php

namespace App\Enums;

enum RiskLevelEnum: int
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

    public function riskCategory(): RiskLevelCategoryEnum
    {
        return match ($this) {
            self::one, self::two => RiskLevelCategoryEnum::Low,
            self::three, self::four => RiskLevelCategoryEnum::Medium,
            self::five, self::six => RiskLevelCategoryEnum::High,
            self::seven, self::eight => RiskLevelCategoryEnum::Danger,
            self::nine, self::ten => RiskLevelCategoryEnum::Extreme,

        };
    }
}
