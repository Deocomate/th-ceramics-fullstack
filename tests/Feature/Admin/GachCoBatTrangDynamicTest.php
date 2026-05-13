<?php

use App\Models\GachCoBatTrang;
use App\Models\GachCoBatTrangCt;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'superadmin']);

    GachCoBatTrang::query()->create([
        'thumbnail_main' => 'assets/images/gach-co-banner.png',
        'video' => null,
        'images' => [],
        'section_bat' => [
            'title' => 'GẠCH BÁT',
            'subtitle' => 'Phối sắc tự nhiên',
            'description' => 'Mô tả cũ',
            'colors' => ['#A98467', '#B22222', '#5D5FEF'],
            'gallery' => ['gach_co_bat_trang/section_gallery/old-a.jpg'],
        ],
        'section_that' => null,
        'section_the' => null,
    ]);
});

function gachCoBatTrangFakeImage(string $name = 'image.png'): UploadedFile
{
    return UploadedFile::fake()->createWithContent(
        $name,
        base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=')
    );
}

test('admin section update persists text colors gallery upload and order', function () {
    Storage::fake('public');

    actingAs($this->admin)
        ->put(route('admin.gach-co-bat-trang.update'), [
            'section_bat' => [
                'title' => 'Gạch Bát Dynamic',
                'subtitle' => 'Subtitle mới',
                'description' => 'Mô tả mới',
                'colors' => ['#111111', '#222222', '#333333'],
            ],
            'section_bat_gallery_order' => ['gach_co_bat_trang/section_gallery/old-a.jpg'],
            'section_bat_new_images' => [
                gachCoBatTrangFakeImage('bat-new.png'),
            ],
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $section = GachCoBatTrang::query()->first()->section_bat;

    expect($section['title'])->toBe('Gạch Bát Dynamic')
        ->and($section['subtitle'])->toBe('Subtitle mới')
        ->and($section['description'])->toBe('Mô tả mới')
        ->and($section['colors'])->toBe(['#111111', '#222222', '#333333'])
        ->and($section['gallery'][0])->toBe('gach_co_bat_trang/section_gallery/old-a.jpg')
        ->and($section['gallery'])->toHaveCount(2);

    Storage::disk('public')->assertExists($section['gallery'][1]);
});

test('admin section gallery image can be deleted from json and storage', function () {
    Storage::fake('public');
    Storage::disk('public')->put('gach_co_bat_trang/section_gallery/delete-me.jpg', 'image');

    GachCoBatTrang::query()->first()->update([
        'section_bat' => [
            'title' => 'GẠCH BÁT',
            'subtitle' => null,
            'description' => null,
            'colors' => ['#A98467', '#B22222', '#5D5FEF'],
            'gallery' => [
                'gach_co_bat_trang/section_gallery/keep.jpg',
                'gach_co_bat_trang/section_gallery/delete-me.jpg',
            ],
        ],
    ]);

    actingAs($this->admin)
        ->delete(route('admin.gach-co-bat-trang.section-image.destroy'), [
            'section' => 'section_bat',
            'image_path' => 'gach_co_bat_trang/section_gallery/delete-me.jpg',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $gallery = GachCoBatTrang::query()->first()->section_bat['gallery'];

    expect($gallery)->toBe(['gach_co_bat_trang/section_gallery/keep.jpg']);
    Storage::disk('public')->assertMissing('gach_co_bat_trang/section_gallery/delete-me.jpg');
});

test('product create persists category type dinh muc and weight', function () {
    actingAs($this->admin)
        ->post(route('admin.gach-co-bat-trang-ct.store'), [
            'code' => 'GCB-DYN-001',
            'name' => 'Gạch Thẻ Dynamic',
            'category_type' => 'the',
            'price' => 12000,
            'size' => '5 x 20 cm',
            'dinh_muc' => '25',
            'weight' => '0.8',
        ])
        ->assertRedirect(route('admin.gach-co-bat-trang-ct.index'))
        ->assertSessionHas('success');

    $product = GachCoBatTrangCt::query()->where('code', 'GCB-DYN-001')->first();

    expect($product->category_type)->toBe('the')
        ->and($product->dinh_muc)->toBe('25')
        ->and($product->weight)->toBe('0.8');
});

test('admin product index displays category labels', function () {
    GachCoBatTrangCt::query()->create([
        'code' => 'GCB-DYN-002',
        'name' => 'Gạch Thất Dynamic',
        'category_type' => 'that',
        'images' => [],
        'price' => 15000,
        'size' => '10 x 20 cm',
        'dinh_muc' => '11',
        'weight' => '1.2',
        'is_delete' => 0,
    ]);

    actingAs($this->admin)
        ->get(route('admin.gach-co-bat-trang-ct.index'))
        ->assertOk()
        ->assertSee('Gạch Thất &amp; Xây', false)
        ->assertSee('Định mức: 11')
        ->assertSee('Cân nặng: 1.2');
});
