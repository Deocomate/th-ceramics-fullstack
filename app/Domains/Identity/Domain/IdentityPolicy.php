<?php

namespace App\Domains\Identity\Domain;

final class IdentityPolicy
{
    /**
     * Check if a user with the given role can link / login via client Google OAuth flow.
     * Prevents privilege escalation by denying admin/superadmin accounts.
     */
    public static function canLinkGoogleAccount(?string $role): bool
    {
        if ($role === null) {
            return true;
        }

        return ! Role::isAdmin($role);
    }

    /**
     * Check whether an operator can delete a target user account.
     * Rules:
     * - Cannot delete superadmin account.
     * - Cannot delete one's own account.
     *
     * @param  int|string  $operatorId
     * @param  int|string  $targetId
     */
    public static function canDeleteUser(
        string $operatorRole,
        $operatorId,
        string $targetRole,
        $targetId
    ): bool {
        if (Role::isSuperAdmin($targetRole)) {
            return false;
        }

        if ((string) $operatorId === (string) $targetId) {
            return false;
        }

        return Role::isSuperAdmin($operatorRole) || Role::isAdmin($operatorRole);
    }

    /**
     * Check if a role can be assigned when creating an admin user via admin UI.
     * Only regular 'admin' is allowed; 'superadmin' cannot be created via UI.
     */
    public static function canAssignAdminRole(string $role): bool
    {
        return $role === Role::ADMIN;
    }
}
