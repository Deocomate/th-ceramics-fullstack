<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Auth\ForgotPasswordRequest;
use App\Http\Requests\Client\Auth\LoginRequest;
use App\Http\Requests\Client\Auth\RegisterRequest;
use App\Http\Requests\Client\Auth\ResetPasswordRequest;
use App\Services\AuthService;
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

        return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác.'])->withInput();
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
        Auth::login($user);

        return redirect()->route('client.home')->with('success', 'Đăng ký thành công');
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
            $user = $this->authService->handleGoogleUser($googleUser);
            Auth::login($user);

            return redirect()->intended(route('client.home'))->with('success', 'Đăng nhập thành công');
        } catch (\Exception $e) {
            return redirect()->route('client.auth.login')
                ->withErrors(['error' => 'Đăng nhập bằng Google thất bại. Vui lòng thử lại.']);
        }
    }
}
