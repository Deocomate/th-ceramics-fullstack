<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * Attempt to authenticate a user.
     *
     * @param  array{email: string, password: string, remember: bool}  $credentials
     */
    public function login(array $credentials): bool
    {
        $remember = $credentials['remember'] ?? false;

        $attempt = Auth::attempt(
            [
                'email' => $credentials['email'],
                'password' => $credentials['password'],
            ],
            $remember
        );

        if ($attempt) {
            request()->session()->regenerate();
        }

        return $attempt;
    }

    /**
     * Register a new customer account.
     *
     * @param  array{name: string, email: string, password: string}  $data
     */
    public function registerClient(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'customer', // Default role for customer
        ]);
    }

    /**
     * Handle Google OAuth user login/registration.
     *
     * @param  \Laravel\Socialite\Contracts\User  $googleUser
     */
    public function handleGoogleUser($googleUser): User
    {
        // Check if user exists by email or google_id
        $user = User::where('email', $googleUser->getEmail())
            ->orWhere('google_id', $googleUser->getId())
            ->first();

        if ($user) {
            // Update google_id if user exists but doesn't have it
            if (! $user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }

            return $user;
        }

        // Create new user for Google OAuth
        return User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
            'role' => 'customer',
            'password' => null, // Google OAuth users don't need password
            'email_verified_at' => now(), // Already verified via Google
        ]);
    }

    /**
     * Log the current user out and invalidate the session.
     */
    public function logout(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    /**
     * Send a password-reset link to the given email address.
     *
     * @return string One of the Password::* status constants.
     */
    public function forgotPassword(string $email): string
    {
        return Password::sendResetLink(['email' => $email]);
    }

    /**
     * Reset the user's password using the provided token.
     *
     * @param  array{token: string, email: string, password: string, password_confirmation: string}  $data
     * @return string One of the Password::* status constants.
     */
    public function resetPassword(array $data): string
    {
        return Password::reset($data, function (User $user, string $password): void {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
        });
    }
}
