<?php

namespace App\Domains\Identity\Domain;

final class Role
{
    public const SUPERADMIN = 'superadmin';

    public const ADMIN = 'admin';

    public const CUSTOMER = 'customer';

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            self::SUPERADMIN,
            self::ADMIN,
            self::CUSTOMER,
        ];
    }

    /**
     * Roles with administrative access.
     *
     * @return list<string>
     */
    public static function adminRoles(): array
    {
        return [
            self::SUPERADMIN,
            self::ADMIN,
        ];
    }

    public static function isValid(?string $role): bool
    {
        if ($role === null) {
            return false;
        }

        return in_array($role, self::all(), true);
    }

    public static function isAdmin(?string $role): bool
    {
        if ($role === null) {
            return false;
        }

        return in_array($role, self::adminRoles(), true);
    }

    public static function isSuperAdmin(?string $role): bool
    {
        return $role === self::SUPERADMIN;
    }

    public static function isRegularAdmin(?string $role): bool
    {
        return $role === self::ADMIN;
    }

    public static function isCustomer(?string $role): bool
    {
        return $role === self::CUSTOMER;
    }

    public static function label(?string $role): string
    {
        return match ($role) {
            self::SUPERADMIN => 'Quản trị viên cấp cao',
            self::ADMIN => 'Quản trị viên',
            self::CUSTOMER => 'Khách hàng',
            default => 'Không xác định',
        };
    }
}
