<?php

namespace App\Enums;

enum UserRole
{
    case ADMIN;
    case USER;

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::USER => 'User',
        };

    }

    public function color(): string
    {
        return match($this) {
            self::ADMIN => 'text-red-600 bg-red-100 ring-red-500/10',
            self::USER => 'text-green-600 bg-green-100 ring-green-500/10',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::ADMIN => 'shield-check',
            self::USER => 'user',
        };
    }

    public static function toArray(): array
    {
        return [
            self::ADMIN->name => self::ADMIN->label(),
            self::USER->name => self::USER->label(),
        ];
    }


    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }


    public function isUser(): bool
    {
        return $this === self::USER;
    }

    public function isDefault(): bool
    {
        return $this === self::USER;
    }
}
