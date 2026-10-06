<?php

namespace Tests\Unit\Identity;

use App\Domains\Commerce\Infrastructure\Models\Order;
use App\Domains\Identity\Domain\IdentityPolicy;
use App\Domains\Identity\Domain\Role;
use App\Domains\Identity\Domain\UserProfileRules;
use App\Domains\Identity\Infrastructure\Models\User;
use App\Domains\Identity\Infrastructure\Notifications\ResetPasswordNotification;
use App\Domains\Identity\Infrastructure\Notifications\VerifyEmailQueued;
use App\Notifications\ResetPasswordNotification as LegacyResetPasswordNotification;
use App\Notifications\VerifyEmailQueued as LegacyVerifyEmailQueued;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class QueueCompatibilityAndDomainTest extends TestCase
{
    public function test_legacy_reset_password_notification_serializes_and_deserializes(): void
    {
        $legacy = new LegacyResetPasswordNotification('token-123');
        $serialized = serialize($legacy);
        $unserialized = unserialize($serialized);

        $this->assertInstanceOf(LegacyResetPasswordNotification::class, $unserialized);
        $this->assertInstanceOf(ResetPasswordNotification::class, $unserialized);
        $this->assertSame('token-123', $unserialized->token);
    }

    public function test_canonical_reset_password_notification_serializes_and_deserializes(): void
    {
        $canonical = new ResetPasswordNotification('token-456');
        $serialized = serialize($canonical);
        $unserialized = unserialize($serialized);

        $this->assertInstanceOf(ResetPasswordNotification::class, $unserialized);
        $this->assertSame('token-456', $unserialized->token);
    }

    public function test_legacy_verify_email_queued_serializes_and_deserializes(): void
    {
        $legacy = new LegacyVerifyEmailQueued;
        $serialized = serialize($legacy);
        $unserialized = unserialize($serialized);

        $this->assertInstanceOf(LegacyVerifyEmailQueued::class, $unserialized);
        $this->assertInstanceOf(VerifyEmailQueued::class, $unserialized);
    }

    public function test_canonical_verify_email_queued_serializes_and_deserializes(): void
    {
        $canonical = new VerifyEmailQueued;
        $serialized = serialize($canonical);
        $unserialized = unserialize($serialized);

        $this->assertInstanceOf(VerifyEmailQueued::class, $unserialized);
    }

    public function test_user_model_legacy_and_canonical_resolution(): void
    {
        $canonical = new User;
        $canonical->name = 'Test User';
        $canonical->email = 'test@example.com';
        $canonical->role = 'customer';

        $serialized = serialize($canonical);
        $unserialized = unserialize($serialized);

        $this->assertInstanceOf(User::class, $unserialized);
        $this->assertInstanceOf(\App\Domains\Identity\Models\User::class, $unserialized);
        $this->assertSame('test@example.com', $unserialized->email);
    }

    public function test_commerce_order_belongs_to_identity_user_relation(): void
    {
        $order = new Order;
        $relation = $order->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }

    public function test_role_domain_logic(): void
    {
        $this->assertTrue(Role::isSuperAdmin('superadmin'));
        $this->assertFalse(Role::isSuperAdmin('admin'));
        $this->assertFalse(Role::isSuperAdmin('customer'));

        $this->assertTrue(Role::isAdmin('superadmin'));
        $this->assertTrue(Role::isAdmin('admin'));
        $this->assertFalse(Role::isAdmin('customer'));
        $this->assertFalse(Role::isAdmin(null));

        $this->assertTrue(Role::isRegularAdmin('admin'));
        $this->assertFalse(Role::isRegularAdmin('superadmin'));

        $this->assertTrue(Role::isCustomer('customer'));
        $this->assertFalse(Role::isCustomer('admin'));

        $this->assertTrue(Role::isValid('admin'));
        $this->assertTrue(Role::isValid('customer'));
        $this->assertTrue(Role::isValid('superadmin'));
        $this->assertFalse(Role::isValid('unknown'));

        $this->assertSame('Quản trị viên cấp cao', Role::label('superadmin'));
        $this->assertSame('Quản trị viên', Role::label('admin'));
        $this->assertSame('Khách hàng', Role::label('customer'));
    }

    public function test_identity_policy_google_account_linking(): void
    {
        // Only customer role or null can link via client Google OAuth
        $this->assertTrue(IdentityPolicy::canLinkGoogleAccount('customer'));
        $this->assertTrue(IdentityPolicy::canLinkGoogleAccount(null));

        // Admin and superadmin cannot link via client Google OAuth
        $this->assertFalse(IdentityPolicy::canLinkGoogleAccount('admin'));
        $this->assertFalse(IdentityPolicy::canLinkGoogleAccount('superadmin'));
    }

    public function test_identity_policy_account_deletion(): void
    {
        // Operator cannot delete themselves
        $this->assertFalse(IdentityPolicy::canDeleteUser('superadmin', 1, 'admin', 1));
        $this->assertFalse(IdentityPolicy::canDeleteUser('admin', 2, 'admin', 2));

        // Nobody can delete superadmin
        $this->assertFalse(IdentityPolicy::canDeleteUser('superadmin', 1, 'superadmin', 2));
        $this->assertFalse(IdentityPolicy::canDeleteUser('admin', 3, 'superadmin', 2));

        // Superadmin can delete regular admin
        $this->assertTrue(IdentityPolicy::canDeleteUser('superadmin', 1, 'admin', 2));

        // Admin can delete another regular admin
        $this->assertTrue(IdentityPolicy::canDeleteUser('admin', 3, 'admin', 4));
    }

    public function test_identity_policy_admin_role_assignment(): void
    {
        $this->assertTrue(IdentityPolicy::canAssignAdminRole('admin'));
        $this->assertFalse(IdentityPolicy::canAssignAdminRole('superadmin'));
        $this->assertFalse(IdentityPolicy::canAssignAdminRole('customer'));
    }

    public function test_user_profile_rules(): void
    {
        $this->assertTrue(UserProfileRules::isValidGender('male'));
        $this->assertTrue(UserProfileRules::isValidGender('female'));
        $this->assertTrue(UserProfileRules::isValidGender('other'));
        $this->assertTrue(UserProfileRules::isValidGender(null));
        $this->assertFalse(UserProfileRules::isValidGender('invalid'));

        $this->assertTrue(UserProfileRules::isValidBirthYear(1990, 2026));
        $this->assertTrue(UserProfileRules::isValidBirthYear(2026, 2026));
        $this->assertTrue(UserProfileRules::isValidBirthYear(null, 2026));
        $this->assertFalse(UserProfileRules::isValidBirthYear(1899, 2026));
        $this->assertFalse(UserProfileRules::isValidBirthYear(2027, 2026));
    }
}
