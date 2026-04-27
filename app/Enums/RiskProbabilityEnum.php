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

    public static function bandLabel(int $value): string
    {
        return match (true) {
            $value <= 2 => 'Velmi nízká',
            $value <= 4 => 'Nízká',
            $value <= 6 => 'Střední',
            $value <= 8 => 'Velká',
            default => 'Velmi velká',
        };
    }

    /** Bands ordered top-to-bottom for matrix Y-axis (highest first). */
    public static function bands(): array
    {
        return [
            ['label' => 'Velmi velká', 'min' => 9, 'max' => 10],
            ['label' => 'Velká',       'min' => 7, 'max' => 8],
            ['label' => 'Střední',     'min' => 5, 'max' => 6],
            ['label' => 'Nízká',       'min' => 3, 'max' => 4],
            ['label' => 'Velmi nízká', 'min' => 1, 'max' => 2],
        ];
    }

    /** Options ordered ascending for UI selectors (lowest first). */
    public static function options(): array
    {
        return [
            ['label' => 'Velmi nízká', 'min' => 1, 'max' => 2,  'value' => 2],
            ['label' => 'Nízká',       'min' => 3, 'max' => 4,  'value' => 4],
            ['label' => 'Střední',     'min' => 5, 'max' => 6,  'value' => 6],
            ['label' => 'Velká',       'min' => 7, 'max' => 8,  'value' => 8],
            ['label' => 'Velmi velká', 'min' => 9, 'max' => 10, 'value' => 10],
        ];
    }
}
