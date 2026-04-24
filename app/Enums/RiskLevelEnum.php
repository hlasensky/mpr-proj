<?php

namespace App\Enums;

enum RiskLevelEnum:int
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

    public function riskCategory():RiskCategoryEnum
    {
        return match ($this) {
            self::one, self::two => RiskCategoryEnum::Low,
            self::three, self::four => RiskCategoryEnum::Medium,
            self::five, self::six => RiskCategoryEnum::High,
            self::seven, self::eight => RiskCategoryEnum::Danger,
            self::nine, self::ten => RiskCategoryEnum::Extreme,

        };
    }

}
