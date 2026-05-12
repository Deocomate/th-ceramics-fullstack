<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

test('preview button appears on admin layout pages', function () {
    $response = $this->actingAs(\App\Models\User::factory()->create())->get(route('admin.dashboard'));
    $response->assertStatus(200);
    $response->assertSee('id="preview-toast"', false);
});

test('preview button maps correctly to client home', function () {
    $response = $this->actingAs(\App\Models\User::factory()->create())->get(route('admin.trang_chu.edit'));
    $response->assertStatus(200);
    $response->assertSee('href="' . route('client.home') . '"', false);
});

test('preview button shows toast for non-preview pages', function () {
    $response = $this->actingAs(\App\Models\User::factory()->create())->get(route('admin.dashboard'));
    $response->assertStatus(200);
    $response->assertSee('onclick="showPreviewToast()"', false);
});

test('preview button respects section override', function () {
    try {
        View::startSection('preview_url', '/custom-preview-url');

        $html = Blade::render('<x-admin.preview-button />');

        expect($html)->toContain('href="/custom-preview-url"');
    } finally {
        View::flushSections();
    }
});

test('news and project edit pages expose slug preview urls', function () {
    $user = \App\Models\User::factory()->create();

    $newsCategory = \App\Models\DanhMucTinTuc::create(['ten_danh_muc' => 'News']);
    $news = \App\Models\TinTuc::create([
        'danh_muc_tin_tuc_id' => $newsCategory->danh_muc_tin_tuc_id,
        'tieu_de' => 'Tin preview',
        'slug' => 'tin-preview',
        'mo_ta_ngan' => 'Mo ta',
        'trang_thai' => 'published',
    ]);

    $projectCategory = \App\Models\DanhMucDuAn::create(['ten_danh_muc' => 'Projects']);
    $project = \App\Models\DuAn::create([
        'ten_du_an' => 'Du an preview',
        'dia_diem' => 'Ha Noi',
        'san_pham' => 'Gach',
        'images' => [],
        'danh_muc_du_an_id' => $projectCategory->danh_muc_du_an_id,
        'slug' => 'du-an-preview',
    ]);

    $this->actingAs($user)
        ->get(route('admin.tin-tuc.edit', $news->tin_tuc_id))
        ->assertOk()
        ->assertSee('href="' . route('client.news.detail', $news->slug) . '"', false);

    $this->actingAs($user)
        ->get(route('admin.du-an.edit', $project->du_an_id))
        ->assertOk()
        ->assertSee('href="' . route('client.projects.detail', $project->slug) . '"', false);
});

test('product edit page exposes id preview url', function () {
    $product = \App\Models\GachHoaThongGioCt::create([
        'code' => 'GHTG-001',
        'name' => 'Gach hoa preview',
        'images' => [],
        'price' => 100000,
        'is_delete' => 0,
    ]);

    $this->actingAs(\App\Models\User::factory()->create())
        ->get(route('admin.gach-hoa-thong-gio-ct.edit', $product->gach_hoa_thong_gio_ct_id))
        ->assertOk()
        ->assertSee('href="' . route('client.products.gach-hoa-thong-gio.detail', $product->gach_hoa_thong_gio_ct_id) . '"', false);
});

test('ngoi hai co detail route renders active products and hides deleted products', function () {
    $active = \App\Models\NgoiHaiCoCt::create([
        'name' => 'Ngói hài cổ preview',
        'images' => [],
        'is_delete' => 0,
    ]);

    $deleted = \App\Models\NgoiHaiCoCt::create([
        'name' => 'Ngói hài cổ deleted',
        'images' => [],
        'is_delete' => 1,
    ]);

    $this->get(route('client.products.ngoi-hai-co.detail', $active->ngoi_hai_co_ct_id))
        ->assertOk()
        ->assertSee('Ngói hài cổ preview');

    $this->get(route('client.products.ngoi-hai-co.detail', $deleted->ngoi_hai_co_ct_id))
        ->assertNotFound();
});

test('phu kien detail uses query type to avoid id collisions', function () {
    \App\Models\NgoiBoNocCt::create([
        'ngoi_bo_noc_ct_id' => 1,
        'name' => 'Ngói bò nóc trùng id',
        'images' => [],
        'is_delete' => 0,
    ]);

    \App\Models\BoNocChuVanCt::create([
        'bo_noc_chu_van_ct_id' => 1,
        'name' => 'Bò nóc chữ vạn đúng',
        'images' => [],
        'is_delete' => 0,
    ]);

    $this->get(route('client.products.phu-kien-ngoi.detail', ['id' => 1, 'type' => 'chu_van']))
        ->assertOk()
        ->assertSee('Bò nóc chữ vạn đúng')
        ->assertSee('CV-1')
        ->assertDontSee('BN-1');
});
