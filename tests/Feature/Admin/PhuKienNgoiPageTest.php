<?php

use App\Models\PhuKienNgoi;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'superadmin']);

    PhuKienNgoi::query()->create([
        'thumbnail_main' => 'assets/images/pk-banner.png',
        'images' => [],
    ]);
});

test('phu kien ngoi edit page renders', function () {
    actingAs($this->admin)
        ->get(route('admin.phu-kien-ngoi.index'))
        ->assertOk()
        ->assertSee('Cấu hình Trang Phụ Kiện Ngói');
});

test('phu kien ngoi page updates design text fields without images', function () {
    actingAs($this->admin)
        ->put(route('admin.phu-kien-ngoi.update'), [
            'banner_text_1' => 'Nâng niu nét chạm trổ',
            'banner_text_2' => 'Hoàn thiện dáng hình kiến trúc Việt',
            'sec1_title' => 'NGÓI BỜ NÓC',
            'sec2_title' => 'BỜ NÓC CHỮ VẠN',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $config = PhuKienNgoi::query()->first();

    expect($config->banner_text_1)->toBe('Nâng niu nét chạm trổ')
        ->and($config->banner_text_2)->toBe('Hoàn thiện dáng hình kiến trúc Việt')
        ->and($config->sec1_title)->toBe('NGÓI BỜ NÓC')
        ->and($config->sec2_title)->toBe('BỜ NÓC CHỮ VẠN');
});

test('phu kien ngoi page rejects invalid image uploads', function () {
    actingAs($this->admin)
        ->put(route('admin.phu-kien-ngoi.update'), [
            'sec1_image' => UploadedFile::fake()->create('document.pdf', 10, 'application/pdf'),
        ])
        ->assertSessionHasErrors('sec1_image');
});

test('phu kien ngoi page appends gallery images', function () {
    Storage::fake('public');

    actingAs($this->admin)
        ->put(route('admin.phu-kien-ngoi.update'), [
            'new_images' => [
                UploadedFile::fake()->createWithContent(
                    'cong-trinh.png',
                    base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=')
                ),
            ],
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $config = PhuKienNgoi::query()->first();

    expect($config->images)->toHaveCount(1);
    Storage::disk('public')->assertExists($config->images[0]);
});
