<?php

namespace App\Enums;

enum RiskProbabilityCategoryEnum: int
{
    case Low = 1;
    case Medium = 2;
    case High = 3;
    case Danger = 4;
    case Extreme = 5;

    public function label(): string
    {
        return match ($this) {
            self::Low => 'Nízké',
            self::Medium => 'Střední',
            self::High => 'Vysoké',
            self::Danger => 'Nebezpečné',
            self::Extreme => 'Extrémní',
        };
    }
}
