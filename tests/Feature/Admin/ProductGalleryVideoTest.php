<?php

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

test('product detail renders youtube gallery embeds', function () {
    $product = makeNgoiAmDuongProduct([
        'assets/images/ngoi-01.jpg',
        ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Win12rIicBI'],
    ]);

    $this->get(route('client.products.ngoi-am-duong.detail', $product->ngoi_am_duong_ct_id))
        ->assertOk()
        ->assertSee('data-gallery-type="video"', false)
        ->assertSee('https://www.youtube.com/embed/Win12rIicBI?enablejsapi=1', false)
        ->assertSee('https://img.youtube.com/vi/Win12rIicBI/hqdefault.jpg', false);
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
