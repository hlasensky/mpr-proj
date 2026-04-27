<?php

namespace App\Enums;

enum RiskCategoryEnum: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Danger = 'danger';
    case Extreme = 'extreme';

    public static function fromScore(int $score): self
    {
        return match (true) {
            $score <= 3 => self::Low,
            $score <= 8 => self::Medium,
            $score <= 14 => self::High,
            $score <= 19 => self::Danger,
            default => self::Extreme,
        };
    }

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

    public function fluxColor(): string
    {
        return match ($this) {
            self::Low => 'green',
            self::Medium => 'yellow',
            self::High => 'orange',
            self::Danger => 'red',
            self::Extreme => 'rose',
        };
    }

    /** Full Tailwind bg class for matrix cells. */
    public function cellClass(): string
    {
        return match ($this) {
            self::Low => 'bg-green-100 dark:bg-green-900/40',
            self::Medium => 'bg-yellow-100 dark:bg-yellow-900/40',
            self::High => 'bg-orange-100 dark:bg-orange-900/40',
            self::Danger => 'bg-red-100 dark:bg-red-900/40',
            self::Extreme => 'bg-rose-200 dark:bg-rose-900/60',
        };
    }

    /** Full Tailwind classes for risk chip inside the cell. */
    public function chipClass(): string
    {
        return match ($this) {
            self::Low => 'bg-green-200 text-green-900 dark:bg-green-800 dark:text-green-100',
            self::Medium => 'bg-yellow-200 text-yellow-900 dark:bg-yellow-800 dark:text-yellow-100',
            self::High => 'bg-orange-200 text-orange-900 dark:bg-orange-800 dark:text-orange-100',
            self::Danger => 'bg-red-200 text-red-900 dark:bg-red-800 dark:text-red-100',
            self::Extreme => 'bg-rose-300 text-rose-950 dark:bg-rose-800 dark:text-rose-100',
        };
    }
}
