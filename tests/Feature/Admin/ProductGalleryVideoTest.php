<?php

use App\Models\NgoiAmDuong;
use App\Models\NgoiAmDuongCt;
use App\Models\User;
use App\Services\ProductCartOptionsService;
use App\Support\ProductGallery;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function galleryFakeImage(string $name): UploadedFile
{
    return UploadedFile::fake()->createWithContent(
        $name,
        base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=')
    );
}

function galleryFakeWebp(string $name = 'batch.webp'): UploadedFile
{
    // Minimal valid 1×1 lossy WebP
    return UploadedFile::fake()->createWithContent(
        $name,
        base64_decode('UklGRiQAAABXRUJQVlA4IBgAAAAwAQCdASoBAAEAAwA0JaQAA3AA/vuUAAA=')
    );
}

function makeNgoiAmDuongProduct(array $images = ['seeders/products/cover.png']): NgoiAmDuongCt
{
    return NgoiAmDuongCt::query()->create([
        'code' => 'NAD-VIDEO-'.uniqid(),
        'name' => 'Ngói test gallery video',
        'color' => 'Tự chọn',
        'price' => 25000,
        'size' => '20x20',
        'images' => $images,
        'is_delete' => 0,
    ]);
}

test('admin can append youtube video to ngoi am duong gallery', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $product = makeNgoiAmDuongProduct(['ngoi_am_duong_ct/images/cover.png']);

    $this->put(route('admin.ngoi-am-duong-ct.update', $product->ngoi_am_duong_ct_id), [
        'code' => $product->code,
        'name' => $product->name,
        'color' => $product->color,
        'price' => $product->price,
        'size' => $product->size,
        'new_video_urls' => ['https://www.youtube.com/watch?v=Win12rIicBI'],
    ])->assertRedirect();

    $product->refresh();

    expect($product->images)->toHaveCount(2)
        ->and($product->images[1])->toBe([
            'type' => 'video',
            'url' => 'https://www.youtube.com/watch?v=Win12rIicBI',
        ]);
});

test('admin rejects invalid youtube url for gallery video', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $product = makeNgoiAmDuongProduct();

    $this->from(route('admin.ngoi-am-duong-ct.edit', $product->ngoi_am_duong_ct_id))
        ->put(route('admin.ngoi-am-duong-ct.update', $product->ngoi_am_duong_ct_id), [
            'code' => $product->code,
            'name' => $product->name,
            'color' => $product->color,
            'price' => $product->price,
            'size' => $product->size,
            'new_video_urls' => ['https://example.com/not-youtube'],
        ])
        ->assertRedirect()
        ->assertSessionHasErrors('new_video_urls.0');
});

test('admin can delete gallery video without removing images', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $product = makeNgoiAmDuongProduct([
        'ngoi_am_duong_ct/images/cover.png',
        ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Win12rIicBI'],
    ]);

    $this->delete(route('admin.ngoi-am-duong-ct.image.destroy', $product->ngoi_am_duong_ct_id), [
        'video_url' => 'https://www.youtube.com/watch?v=Win12rIicBI',
    ])->assertRedirect();

    $product->refresh();

    expect($product->images)->toBe(['ngoi_am_duong_ct/images/cover.png']);
});

test('admin ajax can delete a single gallery image without redirect', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $product = makeNgoiAmDuongProduct([
        'ngoi_am_duong_ct/images/cover.png',
        'ngoi_am_duong_ct/images/detail.png',
    ]);

    $this->deleteJson(route('admin.ngoi-am-duong-ct.image.destroy', $product->ngoi_am_duong_ct_id), [
        'image_path' => 'ngoi_am_duong_ct/images/detail.png',
    ])->assertOk()
        ->assertJson([
            'success' => true,
            'remaining_count' => 1,
        ]);

    $product->refresh();

    expect($product->images)->toBe(['ngoi_am_duong_ct/images/cover.png']);
});

test('admin ajax can bulk delete gallery images and videos', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $product = makeNgoiAmDuongProduct([
        'ngoi_am_duong_ct/images/keep.png',
        'ngoi_am_duong_ct/images/remove-a.png',
        'ngoi_am_duong_ct/images/remove-b.png',
        ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Win12rIicBI'],
        ['type' => 'video', 'url' => 'https://youtu.be/OtherVideoId1'],
    ]);

    $this->deleteJson(route('admin.ngoi-am-duong-ct.image.destroy', $product->ngoi_am_duong_ct_id), [
        'image_paths' => [
            'ngoi_am_duong_ct/images/remove-a.png',
            'ngoi_am_duong_ct/images/remove-b.png',
        ],
        'video_urls' => [
            'https://www.youtube.com/watch?v=Win12rIicBI',
        ],
    ])->assertOk()
        ->assertJson([
            'success' => true,
            'remaining_count' => 2,
        ]);

    $product->refresh();

    expect($product->images)->toHaveCount(2)
        ->and($product->images[0])->toBe('ngoi_am_duong_ct/images/keep.png')
        ->and($product->images[1])->toBe([
            'type' => 'video',
            'url' => 'https://youtu.be/OtherVideoId1',
        ]);
});

test('product detail renders youtube gallery embeds', function () {
    $product = makeNgoiAmDuongProduct([
        'assets/images/ngoi-01.jpg',
        ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Win12rIicBI'],
    ]);

    $this->get(route('client.products.ngoi-am-duong.detail', $product->ngoi_am_duong_ct_id))
        ->assertOk()
        ->assertSee('data-gallery-type="video"', false)
        ->assertSee('data-product-video-play', false)
        ->assertSee('data-product-main-prev', false)
        ->assertSee('data-product-main-next', false)
        ->assertSee('https://www.youtube.com/embed/Win12rIicBI?', false)
        ->assertSee('vq=hd1080', false)
        ->assertSee('controls=0', false)
        ->assertSee('https://img.youtube.com/vi/Win12rIicBI/maxresdefault.jpg', false);
});

test('admin can save product journey video separately from gallery', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $product = makeNgoiAmDuongProduct(['ngoi_am_duong_ct/images/cover.png']);

    $this->put(route('admin.ngoi-am-duong-ct.update', $product->ngoi_am_duong_ct_id), [
        'code' => $product->code,
        'name' => $product->name,
        'color' => $product->color,
        'price' => $product->price,
        'size' => $product->size,
        'video' => 'https://www.youtube.com/watch?v=1E6KuLVe07M',
    ])->assertRedirect();

    $product->refresh();

    expect($product->video)->toBe('https://www.youtube.com/watch?v=1E6KuLVe07M')
        ->and($product->images)->toBe(['ngoi_am_duong_ct/images/cover.png']);
});

test('product detail journey video prefers product url then parent config', function () {
    $parent = NgoiAmDuong::query()->first();
    if (! $parent) {
        $parent = NgoiAmDuong::query()->create([
            'thumbnail_main' => 'seeders/products/cover.png',
            'thumbnail1' => 'seeders/products/cover.png',
            'thumbnail2' => 'seeders/products/cover.png',
            'video' => 'https://www.youtube.com/embed/Win12rIicBI',
        ]);
    } else {
        $parent->update(['video' => 'https://www.youtube.com/embed/Win12rIicBI']);
    }

    $withProductVideo = makeNgoiAmDuongProduct();
    $withProductVideo->update(['video' => 'https://www.youtube.com/watch?v=1E6KuLVe07M']);

    $this->get(route('client.products.ngoi-am-duong.detail', $withProductVideo->ngoi_am_duong_ct_id))
        ->assertOk()
        ->assertSee('data-inline-video-shell', false)
        ->assertSee('data-inline-video-play', false)
        ->assertSee('1E6KuLVe07M', false)
        ->assertSee('h-[480px]', false);

    $fallbackProduct = makeNgoiAmDuongProduct();
    $fallbackProduct->update(['video' => null]);

    $this->get(route('client.products.ngoi-am-duong.detail', $fallbackProduct->ngoi_am_duong_ct_id))
        ->assertOk()
        ->assertSee('data-inline-video-shell', false)
        ->assertSee('Win12rIicBI', false);
});

test('cart options use first image path when gallery starts with video', function () {
    $product = makeNgoiAmDuongProduct([
        ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Win12rIicBI'],
        'seeders/products/cover.png',
    ]);

    expect(ProductGallery::firstImagePath($product->images))->toBe('seeders/products/cover.png');

    $payload = app(ProductCartOptionsService::class)->getOptions('ngoi_am_duong_ct', $product->ngoi_am_duong_ct_id);

    expect($payload['image_url'])->toContain('cover.png')
        ->and($payload['image_url'])->not->toContain('youtube');
});

test('creating product can include gallery videos with images', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $this->post(route('admin.ngoi-am-duong-ct.store'), [
        'code' => 'NAD-VIDEO-STORE-001',
        'name' => 'Ngói store with video',
        'color' => 'Men đỏ',
        'price' => 30000,
        'size' => '20x20',
        'images' => [galleryFakeImage('store-cover.png')],
        'video_urls' => ['https://youtu.be/Win12rIicBI'],
    ])->assertRedirect(route('admin.ngoi-am-duong-ct.index'));

    $product = NgoiAmDuongCt::query()->where('code', 'NAD-VIDEO-STORE-001')->firstOrFail();

    expect($product->images)->toHaveCount(2)
        ->and($product->images[1]['type'])->toBe('video')
        ->and($product->images[1]['url'])->toBe('https://youtu.be/Win12rIicBI');
});

test('creating product can include an uploaded gallery video file', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $this->post(route('admin.ngoi-am-duong-ct.store'), [
        'code' => 'NAD-VIDEO-FILE-001',
        'name' => 'Ngói store with file video',
        'color' => 'Men đỏ',
        'price' => 30000,
        'size' => '20x20',
        'images' => [galleryFakeImage('store-cover.png')],
        'videos' => [UploadedFile::fake()->create('clip.webm', 120, 'video/webm')],
    ])->assertRedirect(route('admin.ngoi-am-duong-ct.index'));

    $product = NgoiAmDuongCt::query()->where('code', 'NAD-VIDEO-FILE-001')->firstOrFail();

    expect($product->images)->toHaveCount(2)
        ->and($product->images[1]['type'])->toBe('video')
        ->and($product->images[1]['source'])->toBe('file')
        ->and($product->images[1]['path'])->toContain('ngoi_am_duong_ct/videos/');
});

test('admin can create product with dedicated cover image', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $this->post(route('admin.ngoi-am-duong-ct.store'), [
        'code' => 'NAD-COVER-001',
        'name' => 'Ngói cover test',
        'color' => 'Men đỏ',
        'price' => 30000,
        'size' => '20x20',
        'cover_image' => galleryFakeImage('cover-only.png'),
        'images' => [galleryFakeImage('detail.png')],
    ])->assertRedirect(route('admin.ngoi-am-duong-ct.index'));

    $product = NgoiAmDuongCt::query()->where('code', 'NAD-COVER-001')->firstOrFail();

    expect($product->images)->toHaveCount(2)
        ->and($product->images[0])->toBeString()
        ->and(ProductGallery::firstImagePath($product->images))->toBe($product->images[0]);
});

test('admin ajax can append gallery images in batch', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $product = makeNgoiAmDuongProduct(['ngoi_am_duong_ct/images/cover.png']);

    $this->withHeaders([
        'Accept' => 'application/json',
        'X-Requested-With' => 'XMLHttpRequest',
    ])->post(route('admin.ngoi-am-duong-ct.image.store', $product->ngoi_am_duong_ct_id), [
        'images' => [galleryFakeImage('batch-a.png'), galleryFakeImage('batch-b.png')],
    ])->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('remaining_count', 3);

    $product->refresh();
    expect($product->images)->toHaveCount(3)
        ->and(ProductGallery::firstImagePath($product->images))->toBe('ngoi_am_duong_ct/images/cover.png');
});

test('admin ajax can append webp gallery images', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $product = makeNgoiAmDuongProduct(['ngoi_am_duong_ct/images/cover.png']);

    $this->withHeaders([
        'Accept' => 'application/json',
        'X-Requested-With' => 'XMLHttpRequest',
    ])->post(route('admin.ngoi-am-duong-ct.image.store', $product->ngoi_am_duong_ct_id), [
        'images' => [galleryFakeWebp('tile-a.webp'), galleryFakeWebp('tile-b.webp')],
    ])->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('remaining_count', 3);

    $product->refresh();
    expect($product->images)->toHaveCount(3);
});

test('admin ajax can reorder gallery and set cover', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $product = makeNgoiAmDuongProduct([
        'ngoi_am_duong_ct/images/a.png',
        'ngoi_am_duong_ct/images/b.png',
        ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Win12rIicBI'],
    ]);

    $this->putJson(route('admin.ngoi-am-duong-ct.gallery.reorder', $product->ngoi_am_duong_ct_id), [
        'items' => [
            'image:ngoi_am_duong_ct/images/b.png',
            'image:ngoi_am_duong_ct/images/a.png',
            'video:https://www.youtube.com/watch?v=Win12rIicBI',
        ],
    ])->assertOk()
        ->assertJsonPath('cover_path', 'ngoi_am_duong_ct/images/b.png');

    $product->refresh();
    expect($product->images[0])->toBe('ngoi_am_duong_ct/images/b.png');

    $this->putJson(route('admin.ngoi-am-duong-ct.gallery.reorder', $product->ngoi_am_duong_ct_id), [
        'cover_path' => 'ngoi_am_duong_ct/images/a.png',
    ])->assertOk()
        ->assertJsonPath('cover_path', 'ngoi_am_duong_ct/images/a.png');

    $product->refresh();
    expect(ProductGallery::firstImagePath($product->images))->toBe('ngoi_am_duong_ct/images/a.png');
});

test('admin ajax can append youtube url without waiting for form save', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $product = makeNgoiAmDuongProduct(['ngoi_am_duong_ct/images/cover.png']);

    $this->withHeaders([
        'Accept' => 'application/json',
        'X-Requested-With' => 'XMLHttpRequest',
    ])->post(route('admin.ngoi-am-duong-ct.image.store', $product->ngoi_am_duong_ct_id), [
        'video_urls' => ['https://www.youtube.com/watch?v=Win12rIicBI'],
    ])->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('remaining_count', 2);

    $product->refresh();
    expect($product->images[1])->toBe([
        'type' => 'video',
        'url' => 'https://www.youtube.com/watch?v=Win12rIicBI',
    ]);
});

test('admin ajax can append an uploaded gallery video file', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $product = makeNgoiAmDuongProduct(['ngoi_am_duong_ct/images/cover.png']);
    $video = UploadedFile::fake()->create('clip.mp4', 200, 'video/mp4');

    $this->withHeaders([
        'Accept' => 'application/json',
        'X-Requested-With' => 'XMLHttpRequest',
    ])->post(route('admin.ngoi-am-duong-ct.image.store', $product->ngoi_am_duong_ct_id), [
        'videos' => [$video],
    ])->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('remaining_count', 2);

    $product->refresh();
    expect($product->images)->toHaveCount(2)
        ->and($product->images[1]['type'])->toBe('video')
        ->and($product->images[1]['source'])->toBe('file')
        ->and($product->images[1]['path'])->toContain('ngoi_am_duong_ct/videos/');
});

test('admin ajax rejects non video files for gallery video upload', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $product = makeNgoiAmDuongProduct();

    $this->withHeaders([
        'Accept' => 'application/json',
        'X-Requested-With' => 'XMLHttpRequest',
    ])->post(route('admin.ngoi-am-duong-ct.image.store', $product->ngoi_am_duong_ct_id), [
        'videos' => [UploadedFile::fake()->create('notes.txt', 10, 'text/plain')],
    ])->assertStatus(422);
});

test('admin ajax rejects gallery video files larger than 50mb', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $product = makeNgoiAmDuongProduct();

    $this->withHeaders([
        'Accept' => 'application/json',
        'X-Requested-With' => 'XMLHttpRequest',
    ])->post(route('admin.ngoi-am-duong-ct.image.store', $product->ngoi_am_duong_ct_id), [
        'videos' => [UploadedFile::fake()->create('huge.mp4', 51201, 'video/mp4')],
    ])->assertStatus(422);
});

test('admin ajax can delete an uploaded gallery video file', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    Storage::disk('public')->put('ngoi_am_duong_ct/videos/clip.mp4', 'fake-video');

    $product = makeNgoiAmDuongProduct([
        'ngoi_am_duong_ct/images/cover.png',
        ['type' => 'video', 'source' => 'file', 'path' => 'ngoi_am_duong_ct/videos/clip.mp4'],
    ]);

    $this->deleteJson(route('admin.ngoi-am-duong-ct.image.destroy', $product->ngoi_am_duong_ct_id), [
        'video_path' => 'ngoi_am_duong_ct/videos/clip.mp4',
    ])->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('remaining_count', 1);

    $product->refresh();
    expect($product->images)->toBe(['ngoi_am_duong_ct/images/cover.png']);
    Storage::disk('public')->assertMissing('ngoi_am_duong_ct/videos/clip.mp4');
});

test('product detail renders uploaded file videos with html5 source', function () {
    $product = makeNgoiAmDuongProduct([
        'assets/images/ngoi-01.jpg',
        ['type' => 'video', 'source' => 'file', 'path' => 'ngoi_am_duong_ct/videos/clip.mp4'],
    ]);

    $this->get(route('client.products.ngoi-am-duong.detail', $product->ngoi_am_duong_ct_id))
        ->assertOk()
        ->assertSee('data-gallery-type="video"', false)
        ->assertSee('data-video-src=', false)
        ->assertSee('data-file-player-host', false)
        ->assertSee('ngoi_am_duong_ct/videos/clip.mp4', false)
        ->assertDontSee('data-youtube-id="clip"', false);
});
