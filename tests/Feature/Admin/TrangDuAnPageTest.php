<?php

use App\Models\TrangDuAn;
use App\Models\User;

test('admin can access trang du an config page', function () {
    TrangDuAn::query()->create([
        'promo_title' => "Gạch thông\ngió 300x300\nthường",
        'promo_image' => 'assets/images/news-detail-5.png',
        'promo_cta_label' => 'XEM CATALOG',
        'promo_cta_url' => '/san-pham/gach-hoa-thong-gio',
        'promo_enabled' => true,
    ]);

    $this->actingAs(User::factory()->create())
        ->get(route('admin.trang-du-an.index'))
        ->assertOk()
        ->assertSee('Banner promo cuối trang Dự án', false);
});

test('projects index renders promo from database not hardcoded strings', function () {
    TrangDuAn::query()->create([
        'promo_title' => "Promo động\ntừ database",
        'promo_image' => 'assets/images/news-detail-5.png',
        'promo_cta_label' => 'XEM NGAY',
        'promo_cta_url' => '/san-pham/gach-hoa-thong-gio',
        'promo_enabled' => true,
    ]);

    $this->get(route('client.projects.index'))
        ->assertOk()
        ->assertSee('Promo động', false)
        ->assertSee('XEM NGAY', false)
        ->assertDontSee('Thanh Hải Plaza', false);
});
