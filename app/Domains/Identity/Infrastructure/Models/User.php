<?php

namespace App\Domains\Identity\Infrastructure\Models;

use App\Domains\Identity\Domain\Role;
use App\Domains\Identity\Infrastructure\Notifications\ResetPasswordNotification;
use App\Domains\Identity\Infrastructure\Notifications\VerifyEmailQueued;
use App\Support\AssetPath;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'role',
        'email',
        'email_verified_at',
        'phone',
        'gender',
        'birth_year',
        'password',
        'google_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => AssetPath::url($this->avatar, 'assets/images/default-avatar.png'),
        );
    }

    // -------------------------------------------------------------------------
    // Role Helper Methods
    // -------------------------------------------------------------------------

    /**
     * Check if user is a superadmin.
     */
    public function isSuperAdmin(): bool
    {
        return Role::isSuperAdmin($this->role);
    }

    /**
     * Check if user is an admin (any admin role).
     */
    public function isAdmin(): bool
    {
        return Role::isAdmin($this->role);
    }

    /**
     * Check if user has exactly the admin role (not superadmin).
     */
    public function isRegularAdmin(): bool
    {
        return Role::isRegularAdmin($this->role);
    }

    /**
     * Check if user is a customer.
     */
    public function isCustomer(): bool
    {
        return Role::isCustomer($this->role);
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Send the email verification notification via queue.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailQueued);
    }

    // -------------------------------------------------------------------------
    // Query Scopes
    // -------------------------------------------------------------------------

    /**
     * Scope to get only admin users (excludes superadmin).
     */
    public function scopeAdmins(Builder $query): Builder
    {
        return $query->where('role', Role::ADMIN);
    }

    /**
     * Scope to get all admin-type users (admin + superadmin).
     */
    public function scopeAllAdmins(Builder $query): Builder
    {
        return $query->whereIn('role', Role::adminRoles());
    }

    /**
     * Scope to get only superadmin users.
     */
    public function scopeSuperAdmins(Builder $query): Builder
    {
        return $query->where('role', Role::SUPERADMIN);
    }

    /**
     * Scope to get customer users.
     */
    public function scopeCustomers(Builder $query): Builder
    {
        return $query->where('role', Role::CUSTOMER);
    }
}
