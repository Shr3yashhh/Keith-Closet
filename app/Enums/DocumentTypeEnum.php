<?php

namespace App\Enums;

enum DocumentTypeEnum: string
{
    case OFFER = "offer";
    case PROJECT = "project";

    public static function getAllValues(): array
    {
        return array_column(self::cases(), "value");
    }

    public static function toSelectArray()
    {
        return collect(self::cases())->mapWithKeys(function ($enum) {
            return [$enum->value => $enum->value];
        })->toArray();
    }
}
