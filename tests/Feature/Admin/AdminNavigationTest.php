<?php

use App\Domains\Commerce\Application\Ports\EcommerceStatusPort;
use App\Domains\Content\Infrastructure\Models\HomePageConfig;
use App\Domains\Content\Infrastructure\Services\HomePageConfigService;
use App\Domains\Identity\Infrastructure\Models\User;
use Illuminate\Support\Facades\Route;

function adminNavigationRoutes(array $nodes): array
{
    $routes = [];

    foreach ($nodes as $node) {
        if (isset($node['route'])) {
            $routes[] = $node['route'];
        }
        array_push($routes, ...adminNavigationRoutes($node['items'] ?? $node['children'] ?? []));
    }

    return $routes;
}

test('every admin navigation entry points at a registered route', function () {
    $routes = adminNavigationRoutes(config('admin_navigation'));

    expect($routes)->not->toBeEmpty();

    foreach ($routes as $name) {
        expect(Route::has($name))->toBeTrue("Route [{$name}] is not registered.");
    }
});

test('topbar lists every module and the sidebar only the items of the current one', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->get(route('admin.orders.index'))
        ->assertOk()
        ->assertSeeInOrder(['Tổng quan', 'Bán hàng', 'Sản phẩm', 'Nội dung', 'Giao diện', 'Hệ thống'])
        ->assertSee('Mã giảm giá')
        ->assertDontSee('Giải thưởng &amp; Thành tựu', false)
        ->assertDontSee('Video thi công');
});

test('breadcrumb follows the navigation tree down to the current item', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->get(route('admin.phu-kien-ngoi-ct.index', ['category_type' => 'chu_van']))
        ->assertOk()
        ->assertSee('Sản phẩm › Phụ Kiện Ngói › Bò Nóc Chữ Vạn › Danh sách SP &amp; Phân loại', false);
});

test('superadmin only entries stay hidden from regular admins', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->get(route('admin.settings.edit'))
        ->assertOk()
        ->assertSee('Cài đặt website')
        ->assertDontSee('Sao lưu nội dung')
        ->assertDontSee(route('admin.users.index'));

    $this->actingAs(User::factory()->create(['role' => 'superadmin']))
        ->get(route('admin.settings.edit'))
        ->assertOk()
        ->assertSee('Sao lưu nội dung')
        ->assertSee(route('admin.users.index'));
});

test('site settings switch the sales mode without touching home page content', function () {
    app(HomePageConfigService::class)->getFirstRecord()->update([
        'loi_tri_an' => ['Cảm ơn quý khách'],
        'nhung_con_so' => [['head' => '500', 'body' => 'Công trình']],
    ]);

    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->put(route('admin.settings.update'), ['is_ecommerce_enabled' => '0'])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $home = HomePageConfig::query()->first();

    expect($home->is_ecommerce_enabled)->toBeFalse()
        ->and($home->loi_tri_an)->toBe(['Cảm ơn quý khách'])
        ->and($home->nhung_con_so)->toBe([['head' => '500', 'body' => 'Công trình']])
        ->and(app(EcommerceStatusPort::class)->enabled())->toBeFalse();
});

test('home page form no longer carries the site wide switches', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->get(route('admin.trang_chu.edit'))
        ->assertOk()
        ->assertDontSee('name="is_ecommerce_enabled"', false)
        ->assertDontSee('name="is_content_protection_enabled"', false)
        ->assertDontSee('name="is_devtools_guard_enabled"', false);
});

test('saving the home page keeps the site wide switches', function () {
    app(HomePageConfigService::class)->updateSiteSettings([
        'is_ecommerce_enabled' => false,
        'is_content_protection_enabled' => true,
    ]);

    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->put(route('admin.trang_chu.update'), ['video' => 'https://example.com/video', 'is_ecommerce_enabled' => '1'])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $home = HomePageConfig::query()->first();

    expect($home->is_ecommerce_enabled)->toBeFalse()
        ->and($home->is_content_protection_enabled)->toBeTrue();
});
