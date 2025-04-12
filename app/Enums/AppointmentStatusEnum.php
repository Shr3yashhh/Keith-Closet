<?php

namespace App\Enums;

enum AppointmentStatusEnum: string
{
    case PENDING = "pending";
    case IN_REVIEW = "in_review";
    case APPROVED = "approved";
    case REJECTED = "rejected";

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

    public function appointmentType(): string
    {
        return match ($this) {
            self::PENDING => "pending",
            self::IN_REVIEW => "in_review",
            self::APPROVED => "approved",
            self::REJECTED => "rejected",
        };
    }
}
