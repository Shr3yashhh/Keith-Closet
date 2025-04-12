<?php

namespace App\Enums;

enum SupplierRoleEnum: string
{
    case SUPPLIER_ADMIN = "supplier-admin";
    case SUPPLIER_USER = "supplier-user";

    public static function getAllValues(): array
    {
        return array_column(self::cases(), "value");
    }

    public function roleType(): string
    {
        return match ($this) {
            self::SUPPLIER_ADMIN => "supplier-admin",
            self::SUPPLIER_USER => "supplier-user",
        };
    }
}
