<?php

use App\Models\DenVuonGomSuCt;
use App\Models\GachHoaThongGioCt;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

function productColorFakeImage(string $name): UploadedFile
{
    return UploadedFile::fake()->createWithContent(
        $name,
        base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=')
    );
}

test('ct tables expose color column', function () {
    foreach ([
        'ngoi_am_duong_ct',
        'ngoi_hai_co_ct',
        'ngoi_hai_van_mieu_ct',
        'gach_hoa_thong_gio_ct',
        'gach_trang_tri_ct',
        'gach_co_bat_trang_ct',
        'linh_vat_phong_thuy_ct',
        'phu_kien_ngoi_ct',
        'lan_can_gom_su_ct',
        'den_vuon_gom_su_ct',
    ] as $table) {
        expect(Schema::hasColumn($table, 'color'))->toBeTrue();
    }
});

test('recommendations render product color summary with fallback', function () {
    $custom = GachHoaThongGioCt::query()->create([
        'code' => 'GHTG-COLOR-001',
        'name' => 'Gạch màu tùy chỉnh',
        'color' => 'Men đỏ cam',
        'images' => ['assets/images/gach-co-work-2.jpg'],
        'price' => 12000,
        'size' => '20 x 20 cm',
        'is_delete' => 0,
    ]);

    $fallback = GachHoaThongGioCt::query()->create([
        'code' => 'GHTG-COLOR-002',
        'name' => 'Gạch màu rỗng',
        'color' => '',
        'images' => ['assets/images/gach-co-work-2.jpg'],
        'price' => 15000,
        'size' => '30 x 30 cm',
        'is_delete' => 0,
    ]);

    $html = Blade::render(
        '<x-client.shared.recommendations :related-products="$products" route-name="client.products.gach-hoa-thong-gio.detail" pk-field="gach_hoa_thong_gio_ct_id" />',
        ['products' => collect([$custom, $fallback])]
    );

    expect($html)->toContain('Men đỏ cam')
        ->and($html)->toContain('Tự chọn')
        ->and($html)->not->toContain('N/A');
});

test('form request backed ct admin store and update persist color', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $this->post(route('admin.gach-hoa-thong-gio-ct.store'), [
        'code' => 'GHTG-COLOR-003',
        'name' => 'Gạch form request',
        'color' => 'Men xanh rêu',
        'price' => 18000,
        'size' => '20 x 20 cm',
        'images' => [productColorFakeImage('gach-form.png')],
    ])->assertRedirect(route('admin.gach-hoa-thong-gio-ct.index'));

    $product = GachHoaThongGioCt::query()->where('code', 'GHTG-COLOR-003')->firstOrFail();
    expect($product->color)->toBe('Men xanh rêu');

    $this->put(route('admin.gach-hoa-thong-gio-ct.update', $product->gach_hoa_thong_gio_ct_id), [
        'code' => 'GHTG-COLOR-003',
        'name' => 'Gạch form request cập nhật',
        'color' => 'Men vàng mật',
        'price' => 19000,
        'size' => '25 x 25 cm',
    ])->assertRedirect();

    expect($product->fresh()->color)->toBe('Men vàng mật');
});

test('inline validated ct admin store and update persist color fallback', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $this->post(route('admin.den-vuon-gom-su-ct.store'), [
        'name' => 'Đèn inline color',
        'color' => 'Men trắng sứ',
        'category_type' => 'den_su',
        'size' => 'H300',
        'images' => [productColorFakeImage('den-inline.png')],
    ])->assertRedirect(route('admin.den-vuon-gom-su-ct.index'));

    $product = DenVuonGomSuCt::query()->where('name', 'Đèn inline color')->firstOrFail();
    expect($product->color)->toBe('Men trắng sứ');

    $this->put(route('admin.den-vuon-gom-su-ct.update', $product->den_vuon_gom_su_ct_id), [
        'name' => 'Đèn inline color updated',
        'color' => '',
        'category_type' => 'den_gom',
        'size' => 'H400',
    ])->assertRedirect();

    expect($product->fresh()->color)->toBe('Tự chọn');
});
