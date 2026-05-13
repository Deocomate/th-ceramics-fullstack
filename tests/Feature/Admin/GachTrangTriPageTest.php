<?php

use App\Models\GachTrangTri;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'superadmin']);

    GachTrangTri::query()->create([
        'thumbnail_main' => 'assets/images/gach-trang-tri-banner.png',
        'video' => null,
        'images' => [],
        'ung_dung_da_dang' => null,
    ]);
});

function gachTrangTriFakeImage(string $name = 'image.png'): UploadedFile
{
    return UploadedFile::fake()->createWithContent(
        $name,
        base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=')
    );
}

test('gach trang tri edit page renders without dau an crud', function () {
    actingAs($this->admin)
        ->get(route('admin.gach-trang-tri.index'))
        ->assertOk()
        ->assertSee('Ứng dụng đa dạng')
        ->assertSee('Hình ảnh Công đoạn chế tác')
        ->assertDontSee('Dấu Ấn Gạch Trang Trí')
        ->assertDontSee('dau-an');
});

test('gach trang tri update accepts partial application data', function () {
    actingAs($this->admin)
        ->put(route('admin.gach-trang-tri.update'), [
            'ung_dung_da_dang' => [
                'main' => ['title' => 'Tường trang trí'],
            ],
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $config = GachTrangTri::query()->first();

    expect($config->ung_dung_da_dang['main']['title'])->toBe('Tường trang trí')
        ->and($config->ung_dung_da_dang['main']['image'])->toBeNull()
        ->and($config->ung_dung_da_dang['sub_1']['title'])->toBeNull();
});

test('gach trang tri application image upload is stored', function () {
    Storage::fake('public');

    actingAs($this->admin)
        ->put(route('admin.gach-trang-tri.update'), [
            'ung_dung_da_dang' => [
                'main' => [
                    'title' => 'Tường trang trí',
                    'image' => gachTrangTriFakeImage('tuong-trang-tri.png'),
                ],
            ],
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $config = GachTrangTri::query()->first();
    $imagePath = $config->ung_dung_da_dang['main']['image'];

    expect($imagePath)->toStartWith('gach_trang_tri/ung_dung/');
    Storage::disk('public')->assertExists($imagePath);
});

test('gach trang tri rejects invalid nested application image upload', function () {
    actingAs($this->admin)
        ->put(route('admin.gach-trang-tri.update'), [
            'ung_dung_da_dang' => [
                'main' => [
                    'title' => 'Tường trang trí',
                    'image' => UploadedFile::fake()->create('document.pdf', 10, 'application/pdf'),
                ],
            ],
        ])
        ->assertSessionHasErrors('ung_dung_da_dang.main.image');
});

test('gach trang tri process images can be reordered', function () {
    GachTrangTri::query()->first()->update([
        'images' => [
            'gach_trang_tri/cong_doan_che_tac/a.jpg',
            'gach_trang_tri/cong_doan_che_tac/b.jpg',
            'gach_trang_tri/cong_doan_che_tac/c.jpg',
        ],
    ]);

    actingAs($this->admin)
        ->put(route('admin.gach-trang-tri.update'), [
            'cong_doan_order' => [
                'gach_trang_tri/cong_doan_che_tac/c.jpg',
                'gach_trang_tri/cong_doan_che_tac/a.jpg',
            ],
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect(GachTrangTri::query()->first()->images)->toBe([
        'gach_trang_tri/cong_doan_che_tac/c.jpg',
        'gach_trang_tri/cong_doan_che_tac/a.jpg',
        'gach_trang_tri/cong_doan_che_tac/b.jpg',
    ]);
});
