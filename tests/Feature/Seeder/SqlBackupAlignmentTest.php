<?php

declare(strict_types=1);

use App\Domains\Catalog\Infrastructure\Models\GachHoaThongGioAnh;
use App\Domains\Catalog\Infrastructure\Models\GiaTriGachHoaThongGio;
use App\Domains\Catalog\Infrastructure\Models\Product;
use App\Domains\Content\Infrastructure\Models\AboutPageConfig;
use App\Domains\Content\Infrastructure\Models\HomePageConfig;
use App\Domains\Content\Infrastructure\Models\Project;
use App\Domains\Content\Infrastructure\Models\ProjectPageConfig;
use Database\Seeders\DatabaseSeeder;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

it('matches sql backup row counts', function () {
    expect(Project::count())->toBe(20);
    expect(Product::where('type_key', 'ngoi_am_duong_ct')->count())->toBe(16);
    expect(GiaTriGachHoaThongGio::count())->toBe(3);
    expect(GachHoaThongGioAnh::count())->toBe(10);
});

it('seeds home page with expanded banner gallery', function () {
    $home = HomePageConfig::first();

    expect(count($home->banner))->toBeGreaterThanOrEqual(5);
    expect($home->banner)->toContain('assets/images/ngoi-am-duong-banner.jpg');
    expect(count($home->showroom_images))->toBeGreaterThanOrEqual(5);
});

it('seeds about page from sql backup', function () {
    $about = AboutPageConfig::first();

    expect($about->header_banner)->toBe('GỐM SỨ THANH HẢI');
    expect($about->body_banner)->toContain('40 NĂM');
});

it('seeds project page promo from sql backup', function () {
    $page = ProjectPageConfig::first();

    expect($page->promo_title)->toBe("Gạch thông\ngió 300x300\nthường");
    expect($page->promo_cta_url)->toBe('/san-pham/gach-hoa-thong-gio');
});

it('seeds projects with seven gallery images each', function () {
    // Enhanced from SQL (3 images) to meet 5–10 gallery rule
    Project::all()->each(function (Project $project) {
        expect(count($project->images))->toBe(7);
    });
});

it('uses deterministic product detail data from backup', function () {
    $first = Product::where('type_key', 'ngoi_am_duong_ct')
        ->whereHas('variants', fn ($q) => $q->where('sku', 'NAD-2026-001'))
        ->first();

    expect($first)->not->toBeNull();
    expect($first->name)->toBe('Ngói Âm Dương Tráng Men Cao Cấp Bát Tràng - Phiên bản 1');
    expect(count($first->images))->toBeGreaterThanOrEqual(5);
    expect($first->des)->toBeArray()->and(count($first->des))->toBe(4);
});
