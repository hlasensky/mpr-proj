<?php

namespace App\Enums;

enum RiskLevelCategoryEnum: string
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
            self::Low => 'Nepravděpodobné',
            self::Medium => 'Málo pravděpodobné',
            self::High => 'Možné',
            self::Danger => 'Pravděpodobné',
            self::Extreme => 'Velmi pravděpodobné',
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

    /** Maps a raw score (1–100) to a CSS variable band (1–5) for var(--risk-N). */
    public static function matrixBand(int $score): int
    {
        return match (true) {
            $score <= 6 => 1,
            $score <= 10 => 2,
            $score <= 21 => 3,
            $score <= 36 => 4,
            default => 5,
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

    /** Gradient bg class for 10×10 matrix cells, intensity increases with score (1–100). */
    public static function gradientCellClass(int $score): string
    {
        return match (true) {
            $score <= 3 => 'bg-green-300 dark:bg-green-800/50',
            $score <= 6 => 'bg-green-500 dark:bg-green-700/60',
            $score <= 10 => 'bg-lime-300 dark:bg-lime-800/50',
            $score <= 15 => 'bg-yellow-300 dark:bg-yellow-700/50',
            $score <= 21 => 'bg-yellow-500 dark:bg-yellow-600/60',
            $score <= 28 => 'bg-orange-400 dark:bg-orange-700/60',
            $score <= 36 => 'bg-orange-600 dark:bg-orange-700/70',
            $score <= 50 => 'bg-red-500 dark:bg-red-700/70',
            $score <= 70 => 'bg-red-700 dark:bg-red-800/80',
            default => 'bg-red-900 dark:bg-red-950/90',
        };
    }

    /** Chip class matched to the gradient cell color (score 1–100). */
    public static function gradientChipClass(int $score): string
    {
        return match (true) {
            $score <= 3 => 'bg-green-100 text-green-900 dark:bg-green-700 dark:text-green-100',
            $score <= 6 => 'bg-green-200 text-green-900 dark:bg-green-600 dark:text-green-100',
            $score <= 10 => 'bg-lime-100 text-lime-900 dark:bg-lime-700 dark:text-lime-100',
            $score <= 15 => 'bg-yellow-100 text-yellow-900 dark:bg-yellow-600 dark:text-yellow-100',
            $score <= 21 => 'bg-yellow-200 text-yellow-900 dark:bg-yellow-500 dark:text-yellow-950',
            $score <= 28 => 'bg-orange-100 text-orange-900 dark:bg-orange-600 dark:text-orange-100',
            $score <= 36 => 'bg-orange-200 text-orange-900 dark:bg-orange-500 dark:text-orange-950',
            $score <= 50 => 'bg-red-100 text-red-900 dark:bg-red-600 dark:text-red-100',
            $score <= 70 => 'bg-red-200 text-red-950 dark:bg-red-500 dark:text-red-100',
            default => 'bg-red-300 text-red-950 dark:bg-red-400 dark:text-red-950',
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
