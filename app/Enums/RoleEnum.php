<?php

namespace App\Enums;

enum RoleEnum: string
{
    case UnVerified = 'unverified';
    case Manager = 'manager';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            RoleEnum::UnVerified => 'Neověřený',
            RoleEnum::Manager => 'Manažer',
            RoleEnum::Admin => 'Admin',
        };
    }

    public function color(): string
    {
        return match ($this) {
            RoleEnum::UnVerified => 'zinc',
            RoleEnum::Manager => 'blue',
            RoleEnum::Admin => 'violet',
        };
    }

    /** Returns roles that can be actively assigned (excludes UnVerified). */
    public static function assignable(): array
    {
        return array_values(array_filter(self::cases(), fn ($r) => $r !== self::UnVerified));
    }
}
