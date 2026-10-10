<?php

use App\Domains\Content\Infrastructure\Models\HomePageConfig;
use App\Domains\Content\Infrastructure\Services\HomePageConfigService;
use App\Domains\Identity\Infrastructure\Models\User;
use App\Domains\Protection\Http\Support\ProtectionExemption;
use App\Domains\Protection\Infrastructure\ProtectionSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

function protectionSettingsForNewRequest(): ProtectionSettings
{
    app()->instance('request', Request::create('/'));

    return app(ProtectionSettings::class);
}

function protectionRequest(?User $user = null, string $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/141.0 Safari/537.36'): Request
{
    $request = Request::create('/', 'GET', server: ['HTTP_USER_AGENT' => $userAgent]);
    $request->setUserResolver(static fn () => $user);

    return $request;
}

test('content protection flags default to disabled after migrate', function () {
    $trangChu = app(HomePageConfigService::class)->getFirstRecord()->fresh();

    expect($trangChu->is_content_protection_enabled)->toBeFalse()
        ->and($trangChu->is_devtools_guard_enabled)->toBeFalse();

    $settings = protectionSettingsForNewRequest();

    expect($settings->deterrenceEnabled())->toBeFalse()
        ->and($settings->devtoolsGuardEnabled())->toBeFalse();
});

test('content protection flags are disabled when no home page record exists', function () {
    expect(HomePageConfig::query()->count())->toBe(0);

    $settings = protectionSettingsForNewRequest();

    expect($settings->deterrenceEnabled())->toBeFalse()
        ->and($settings->devtoolsGuardEnabled())->toBeFalse();
});

test('admin can toggle both content protection flags independently', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    app(HomePageConfigService::class)->getFirstRecord();

    expect(protectionSettingsForNewRequest()->deterrenceEnabled())->toBeFalse();

    $this->actingAs($admin)
        ->put(route('admin.trang_chu.update'), [
            'is_content_protection_enabled' => '1',
            'is_devtools_guard_enabled' => '1',
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $settings = protectionSettingsForNewRequest();

    expect($settings->deterrenceEnabled())->toBeTrue()
        ->and($settings->devtoolsGuardEnabled())->toBeTrue();

    $this->actingAs($admin)
        ->put(route('admin.trang_chu.update'), [
            'is_content_protection_enabled' => '1',
            'is_devtools_guard_enabled' => '0',
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $settings = protectionSettingsForNewRequest();

    expect($settings->deterrenceEnabled())->toBeTrue()
        ->and($settings->devtoolsGuardEnabled())->toBeFalse();
});

test('admin home page form shows both content protection switches', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.trang_chu.edit'))
        ->assertOk()
        ->assertSee('Bảo vệ nội dung')
        ->assertSee('name="is_content_protection_enabled"', false)
        ->assertSee('name="is_devtools_guard_enabled"', false);
});

test('updating trang chu busts content protection cache', function () {
    app(HomePageConfigService::class)->getFirstRecord();
    protectionSettingsForNewRequest()->deterrenceEnabled();

    expect(Cache::has('site_content_protection'))->toBeTrue();

    app(HomePageConfigService::class)->update(['is_content_protection_enabled' => true]);

    expect(Cache::has('site_content_protection'))->toBeFalse()
        ->and(protectionSettingsForNewRequest()->deterrenceEnabled())->toBeTrue();
});

test('updating other home page fields keeps content protection flags', function () {
    app(HomePageConfigService::class)->update([
        'is_content_protection_enabled' => true,
        'is_devtools_guard_enabled' => true,
    ]);

    $trangChu = app(HomePageConfigService::class)->update(['video' => 'https://example.com/video']);

    expect($trangChu->is_content_protection_enabled)->toBeTrue()
        ->and($trangChu->is_devtools_guard_enabled)->toBeTrue();
});

test('signed in admins and superadmins are exempt from content protection', function (string $role) {
    $user = User::factory()->create(['role' => $role]);

    expect(app(ProtectionExemption::class)->isExempt(protectionRequest($user)))->toBeTrue();
})->with(['superadmin', 'admin']);

test('customers and guests are not exempt from content protection', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $exemption = app(ProtectionExemption::class);

    expect($exemption->isExempt(protectionRequest($customer)))->toBeFalse()
        ->and($exemption->isExempt(protectionRequest()))->toBeFalse()
        ->and($exemption->isExempt(protectionRequest(userAgent: '')))->toBeFalse();
});

test('listed crawlers are exempt from content protection', function (string $userAgent) {
    expect(app(ProtectionExemption::class)->isExempt(protectionRequest(userAgent: $userAgent)))->toBeTrue();
})->with([
    'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
    'Mozilla/5.0 (compatible; Google-InspectionTool/1.0;)',
    'Mozilla/5.0 (Linux; Android 11) Chrome/141.0 Mobile Safari/537.36 Chrome-Lighthouse',
    'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)',
    'Mozilla/5.0 (compatible; coccocbot-web/1.0; +http://help.coccoc.com/searchengine)',
    'DuckDuckBot/1.1; (+http://duckduckgo.com/duckduckbot.html)',
    'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)',
    'Mozilla/5.0 (compatible; Zalo/1.0)',
    'Twitterbot/1.0',
    'Mozilla/5.0 (Macintosh) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Safari/605.1.15 (Applebot/0.1)',
]);

test('exempt crawler list is configurable', function () {
    config(['content_protection.exempt_user_agents' => ['ExampleBot']]);
    $exemption = app(ProtectionExemption::class);

    expect($exemption->isExempt(protectionRequest(userAgent: 'ExampleBot/1.0')))->toBeTrue()
        ->and($exemption->isExempt(protectionRequest(userAgent: 'Googlebot/2.1')))->toBeFalse();
});
