<?php

use App\Domains\Content\Infrastructure\Services\HomePageConfigService;
use App\Domains\Identity\Infrastructure\Models\User;

test('home page form shows static asset images and uploaded images from their own locations', function () {
    app(HomePageConfigService::class)->getFirstRecord()->update([
        'banner' => ['assets/images/home-hero-01.webp'],
        'khach_hang_doi_tac' => ['assets/images/partner-01.png'],
        'loi_tri_an_anh' => 'assets/images/am-duong-detail-01.png',
        've_chung_toi_logo' => ['trang_chu/ve_chung_toi_logo/logo.webp'],
        'showroom_images' => ['assets/images/showroom-01.webp', 'trang_chu/showroom_images/uploaded.webp'],
    ]);

    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->get(route('admin.trang_chu.edit'))
        ->assertOk()
        ->assertSee('src="'.asset('assets/images/home-hero-01.webp').'"', false)
        ->assertSee('src="'.asset('assets/images/partner-01.png').'"', false)
        ->assertSee('src="'.asset('assets/images/am-duong-detail-01.png').'"', false)
        ->assertSee('src="'.asset('assets/images/showroom-01.webp').'"', false)
        ->assertSee('src="'.asset('storage/trang_chu/ve_chung_toi_logo/logo.webp').'"', false)
        ->assertSee('src="'.asset('storage/trang_chu/showroom_images/uploaded.webp').'"', false)
        ->assertDontSee('storage/assets/images', false);
});
