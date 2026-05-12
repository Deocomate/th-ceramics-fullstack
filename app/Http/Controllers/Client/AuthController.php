<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Auth\ForgotPasswordRequest;
use App\Http\Requests\Client\Auth\LoginRequest;
use App\Http\Requests\Client\Auth\RegisterRequest;
use App\Http\Requests\Client\Auth\ResetPasswordRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('clients.auth.login');
    }

    /**
     * Handle login
     */
    public function login(LoginRequest $request)
    {
        if ($this->authService->login($request->validated())) {
            return redirect()->intended(route('client.home'))->with('success', 'Đăng nhập thành công');
        }

        return back()->withErrors(['error' => 'Email hoặc mật khẩu không chính xác. Vui lòng thử lại.'])->withInput();
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        return view('clients.auth.register');
    }

    /**
     * Handle registration
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->authService->registerClient($request->validated());
        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('success', 'Đăng ký thành công. Vui lòng kiểm tra email để xác thực tài khoản.');
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        $this->authService->logout();

        return redirect()->route('client.home')->with('success', 'Đã đăng xuất');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPassword()
    {
        return view('clients.auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(ForgotPasswordRequest $request)
    {
        $status = $this->authService->forgotPassword($request->email);

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors(['email' => __($status)])->withInput();
    }

    /**
     * Show reset password form
     */
    public function showResetPassword(string $token, Request $request)
    {
        return view('clients.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle password reset
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = $this->authService->resetPassword($request->validated());

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('client.auth.login')->with('success', __($status))
            : back()->withErrors(['email' => __($status)])->withInput();
    }

    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $googleId = $googleUser->getId();
            $email = $googleUser->getEmail();
            $avatar = $googleUser->getAvatar();

            $user = User::where('google_id', $googleId)->first();

            if ($user) {
                Auth::login($user);

                return redirect()->intended(route('client.home'))->with('success', 'Đăng nhập thành công');
            }

            $existingUser = User::where('email', $email)->first();

            if ($existingUser) {
                $wasVerified = $existingUser->hasVerifiedEmail();

                $existingUser->forceFill([
                    'google_id' => $googleId,
                    'avatar' => $existingUser->avatar ?: $avatar,
                    'email_verified_at' => $existingUser->email_verified_at ?: now(),
                ])->save();

                if (! $wasVerified && empty($existingUser->phone)) {
                    session()->put('google_user', [
                        'user_id' => $existingUser->id,
                        'name' => $existingUser->name,
                        'email' => $existingUser->email,
                        'google_id' => $googleId,
                        'avatar' => $existingUser->avatar,
                    ]);

                    return redirect()->route('client.auth.google.complete');
                }

                Auth::login($existingUser);

                return redirect()->intended(route('client.home'))->with('success', 'Đăng nhập thành công');
            }

            session()->put('google_user', [
                'name' => $googleUser->getName() ?: $email,
                'email' => $email,
                'google_id' => $googleId,
                'avatar' => $avatar,
            ]);

            return redirect()->route('client.auth.google.complete');
        } catch (\Exception $e) {
            return redirect()->route('client.auth.login')
                ->withErrors(['error' => 'Đăng nhập bằng Google thất bại. Vui lòng thử lại.']);
        }
    }

    public function showCompleteGoogleRegistration()
    {
        if (! session()->has('google_user')) {
            return redirect()->route('client.auth.login')
                ->withErrors(['error' => 'Phiên đăng nhập Google đã hết hạn. Vui lòng thử lại.']);
        }

        return view('clients.auth.complete-google-registration', [
            'googleUser' => session('google_user'),
        ]);
    }

    public function submitCompleteGoogleRegistration(Request $request)
    {
        $googleUser = session('google_user');

        if (! $googleUser) {
            return redirect()->route('client.auth.login')
                ->withErrors(['error' => 'Phiên đăng nhập Google đã hết hạn. Vui lòng thử lại.']);
        }

        $validated = $request->validate(
            [
                'phone' => ['required', 'string', 'max:20'],
            ],
            [
                'phone.required' => 'Vui lòng nhập số điện thoại.',
                'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            ]
        );

        if (! empty($googleUser['user_id'])) {
            $user = User::findOrFail($googleUser['user_id']);
            $user->forceFill([
                'phone' => $validated['phone'],
                'google_id' => $googleUser['google_id'],
                'avatar' => $user->avatar ?: ($googleUser['avatar'] ?? null),
                'email_verified_at' => $user->email_verified_at ?: now(),
            ])->save();
        } else {
            $user = User::create([
                'name' => $googleUser['name'],
                'email' => $googleUser['email'],
                'phone' => $validated['phone'],
                'google_id' => $googleUser['google_id'],
                'avatar' => $googleUser['avatar'] ?? null,
                'role' => 'customer',
                'password' => null,
                'email_verified_at' => now(),
            ]);
        }

        session()->forget('google_user');
        Auth::login($user);

        return redirect()->intended(route('client.home'))->with('success', 'Đăng nhập thành công');
    }

    public function verifyNotice()
    {
        return view('clients.auth.verify-email');
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('client.home')
            ->with('success', 'Email đã được xác thực thành công.');
    }

    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('client.home');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Email xác thực đã được gửi lại.');
    }
}
