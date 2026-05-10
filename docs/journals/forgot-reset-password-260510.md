# Forgot & Reset Password Feature -- Smooth Sail, One Lingering Smell

**Date**: 2026-05-10 05:45
**Severity**: Low
**Component**: Auth -- Password Reset (Admin + Client)
**Status**: Resolved (with production warning)

## What Happened

Implemented the complete Forgot/Reset Password flow for both Admin and Client portals. The controllers, routes, services, requests, and views already existed. The gap was a custom Notification to route reset links based on user role -- Laravel's default `ResetPassword` calls `route('password.reset')`, which does not exist in this project (route names are `admin.auth.reset-password` and `client.auth.reset-password`).

Four things delivered:
1. `app/Notifications/ResetPasswordNotification.php` -- extends `Illuminate\Auth\Notifications\ResetPassword`, implements `ShouldQueue`, branches via `$notifiable->isAdmin()` to pick the correct route name
2. `app/Models/User.php` -- added `sendPasswordResetNotification()` override
3. `resources/views/emails/auth/reset_password.blade.php` -- branded Vietnamese email using `x-mail::message` components
4. `tests/Feature/Auth/PasswordResetTest.php` -- 15 Pest tests (4 admin flow, 4 client flow, 5 edge cases, 2 notification URL routing)

All 15 tests pass. Zero regressions in the existing suite (43/44 pass; 1 pre-existing failure in `ExampleTest` unrelated).

## The Brutal Truth

This went about as smoothly as a feature implementation can go. No hair-pulling, no multi-hour debugging rabbit holes. That said, the SMTP credentials in `.env` belong to `kingexpressbus.booking@gmail.com` -- a completely different project. It works for dev testing because Gmail App Passwords are account-scoped, not address-scoped, but this is a ticking time bomb. The first person to deploy this without swapping credentials will send reset emails from a bus booking company's Gmail account. That's embarrassing at best, a trust-destroyer at worst.

Also: `ShouldQueue` with `QUEUE_CONNECTION=database` means password reset emails silently land in the `jobs` table and never get sent unless someone remembers to run `php artisan queue:listen`. This is fine if you know about it. Nobody will know about it in 3 months.

## Technical Details

Key implementation decision: Added `->action('Đặt lại mật khẩu', $url)` to the `MailMessage` chain in `toMail()`. Without it, `->toArray()['actionUrl']` is null, which means the notification URL routing tests cannot assert the generated URL. The `->markdown()` call sets the view and data but does not populate `actionUrl` in the mail message's array representation. The `->action()` chain is what populates it.

Also had to use `urlencode($admin->email)` in test assertions because `route()` encodes the email parameter in query strings. A straight `assertStringContainsString('admin@test.com', ...)` would fail because the actual URL contains `admin%40test.com`.

`Notification::fake()` used instead of `Mail::fake()` since the implementation uses Laravel Notifications, not raw Mailables. `Notification::assertSentTo()` verifies the correct notifiable and notification class.

```php
// In toMail() -- action() populates toArray()['actionUrl']:
return (new MailMessage)
    ->subject('Yêu cầu đặt lại mật khẩu - '.config('app.name'))
    ->markdown('emails.auth.reset_password', ['url' => $url])
    ->action('Đặt lại mật khẩu', $url);
```

Mail component theming: Published `laravel-mail` vendor views, updated `resources/views/vendor/mail/html/themes/default.css` `$primary-color` from `#18181b` to `#A31D1D` (TH Ceramics brand red).

## Root Cause Analysis

No real failure here. The feature was straightforward because the groundwork -- controllers, services, routes, form requests -- was already solid. The only missing piece was the custom Notification, which is a standard Laravel extension point.

The real risk is operational, not technical:
1. SMTP credentials are project-wrong. Root cause: `.env` was copy-pasted from another project and never audited.
2. Queue-based email delivery is invisible. Root cause: `ShouldQueue` is a good pattern but there is no monitoring, no failed-jobs dashboard, no alert if the queue worker is not running.

## Lessons Learned

- When using `x-mail::message` Markdown mailables, if you need to inspect the URL programmatically (tests, logging), chain `->action()` even if the markdown template already has an `<x-mail::button>`. The `actionUrl` is only populated by `->action()`.
- `route()` URL-encodes email parameters. Test assertions must account for this with `urlencode()`.
- `.env` credential hygiene matters. A `.env.example` with placeholder values and a deployment checklist would prevent cross-project credential leaks.
- Publishing `laravel-mail` vendor views is necessary if you want brand colors in emails. The default Tailwind-ish blue looks nothing like any company's brand.

## Next Steps

- Before production: replace SMTP credentials in `.env` with TH Ceramics email and `MAIL_FROM_ADDRESS=no-reply@thceramics.vn`. Owner: deployment engineer.
- Consider adding a health-check that verifies the queue worker is running, or document `php artisan queue:listen` as a required dev step in README.
- The 1 pre-existing `ExampleTest` failure should be fixed or removed -- it adds noise to every test run.
