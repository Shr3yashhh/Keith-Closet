<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SUPER_ADMIN = "super-admin";
    case CONTRACTOR = "contractor";

    public static function getAllValues(): array
    {
        return array_column(self::cases(), "value");
    }

    public function roleType(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => "super-admin",
            self::CONTRACTOR => "contractor",
        };
    }
}
