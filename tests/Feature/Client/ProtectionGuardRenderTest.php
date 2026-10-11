<?php

use App\Domains\Content\Infrastructure\Models\Catalog;
use App\Domains\Content\Infrastructure\Services\HomePageConfigService;
use App\Domains\Identity\Infrastructure\Models\User;

function enableProtection(bool $deterrence, bool $devtoolsGuard): void
{
    app(HomePageConfigService::class)->update([
        'is_content_protection_enabled' => $deterrence,
        'is_devtools_guard_enabled' => $devtoolsGuard,
    ]);
}

test('home page has no protection script while both switches are off', function () {
    enableProtection(false, false);

    $this->get(route('client.home'))
        ->assertOk()
        ->assertDontSee('content-protection', false)
        ->assertDontSee('disable-devtool', false)
        ->assertDontSee('__contentProtection', false);
});

test('deterrence switch renders the protection script without the devtools detector', function () {
    enableProtection(true, false);

    $this->get(route('client.home'))
        ->assertOk()
        ->assertSee('assets/js/content-protection.js', false)
        ->assertSee('-webkit-touch-callout: none', false)
        ->assertSee('"deterrence":true', false)
        ->assertSee('"devtools":null', false)
        ->assertDontSee('disable-devtool', false);
});

test('both switches render the protection script and the devtools detector', function () {
    enableProtection(true, true);

    $content = $this->get(route('client.home'))
        ->assertOk()
        ->assertSee('assets/js/content-protection.js', false)
        ->assertSee('assets/js/vendor/disable-devtool.min.js', false)
        ->assertSee('-webkit-touch-callout: none', false)
        ->getContent();

    preg_match('/window\.__contentProtection = (\{.*?\});/s', $content, $matches);
    $config = json_decode($matches[1] ?? 'null', true);

    expect($config)->toBe([
        'deterrence' => true,
        'devtools' => [
            'reportUrl' => route('client.protection.report'),
            'noticeUrl' => route('client.protection.notice'),
            'detectors' => [0, 1, 3, 4, 6, 7],
            'interval' => 1000,
        ],
    ]);

    expect(strpos($content, 'disable-devtool.min.js'))
        ->toBeLessThan(strpos($content, 'assets/js/content-protection.js'));
});

test('devtools switch alone renders the detector without the deterrence layer', function () {
    enableProtection(false, true);

    $this->get(route('client.home'))
        ->assertOk()
        ->assertSee('assets/js/content-protection.js', false)
        ->assertSee('assets/js/vendor/disable-devtool.min.js', false)
        ->assertSee('"deterrence":false', false)
        ->assertDontSee('-webkit-touch-callout', false);
});

test('signed in admins see no protection script', function (string $role) {
    enableProtection(true, true);

    $this->actingAs(User::factory()->create(['role' => $role]))
        ->get(route('client.home'))
        ->assertOk()
        ->assertDontSee('content-protection', false)
        ->assertDontSee('disable-devtool', false);
})->with(['superadmin', 'admin']);

test('crawlers see no protection script', function (string $userAgent) {
    enableProtection(true, true);

    $this->withHeader('User-Agent', $userAgent)
        ->get(route('client.home'))
        ->assertOk()
        ->assertDontSee('content-protection', false)
        ->assertDontSee('disable-devtool', false);
})->with([
    'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
    'Mozilla/5.0 (Linux; Android 11) Chrome/141.0 Mobile Safari/537.36 Chrome-Lighthouse',
]);

test('signed in customers get the protection script', function () {
    enableProtection(true, true);

    $this->actingAs(User::factory()->create(['role' => 'customer']))
        ->get(route('client.home'))
        ->assertOk()
        ->assertSee('assets/js/content-protection.js', false)
        ->assertSee('assets/js/vendor/disable-devtool.min.js', false);
});

test('catalog flipbook page carries the guard and a csrf token', function () {
    enableProtection(true, true);
    $catalog = Catalog::query()->create([
        'tieu_de' => 'Sample Catalog',
        'file' => 'catalogs/sample.pdf',
        'pages' => ['batch' => 'b1', 'items' => [
            ['path' => 'catalog/pages/1/b1/000.webp', 'w' => 1414, 'h' => 2000, 'pdf_page' => 1, 'side' => 'full'],
        ]],
        'anh_dai_dien' => null,
    ]);

    $this->get(route('client.dich-vu.tai-catalog.read', ['id' => $catalog->catalog_id]))
        ->assertOk()
        ->assertSee('<meta name="csrf-token"', false)
        ->assertSee('assets/js/content-protection.js', false)
        ->assertSee('assets/js/vendor/disable-devtool.min.js', false);
});

test('notice and warning pages are not indexed and do not run the detector', function (string $routeName) {
    enableProtection(true, true);

    $this->get(route($routeName))
        ->assertOk()
        ->assertSee('<meta name="robots" content="noindex, nofollow"', false)
        ->assertDontSee('disable-devtool', false)
        ->assertDontSee('content-protection', false);
})->with(['client.protection.notice', 'client.protection.warning']);

test('warning page states facts without claiming identification or a crime', function () {
    $this->get(route('client.protection.warning'))
        ->assertOk()
        ->assertSee('Cảnh báo bản quyền')
        ->assertSee('Điều 225 Bộ luật Hình sự')
        ->assertDontSee('Mã ghi nhận')
        ->assertDontSee('danh tính');
});

test('privacy policy discloses what is recorded and for how long', function () {
    $this->get(route('client.dich-vu.bao-mat-thong-tin'))
        ->assertOk()
        ->assertSee('địa chỉ IP')
        ->assertSee('90 ngày')
        ->assertSee('quyền sở hữu trí tuệ');
});
