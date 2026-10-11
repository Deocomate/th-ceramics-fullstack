<?php

use App\Domains\Catalog\Infrastructure\ProductWriter;
use App\Domains\Media\Infrastructure\ProtectedMediaUrl;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

const PROTECTED_VIDEO_PATH = 'ngoi_am_duong_ct/videos/clip.mp4';

beforeEach(function () {
    Storage::fake('public');
    Storage::fake('local');
    Storage::disk('local')->put(PROTECTED_VIDEO_PATH, str_repeat('V', 1000));
});

function signedVideoUrlFor(string $path): string
{
    return URL::temporarySignedRoute('client.media.video', now()->addHour(), ['path' => $path], absolute: false);
}

test('a signed url plays the private video without allowing it to be cached', function () {
    $response = $this->get(ProtectedMediaUrl::video(PROTECTED_VIDEO_PATH))->assertOk();

    expect($response->headers->get('Accept-Ranges'))->toBe('bytes')
        ->and($response->headers->get('Content-Type'))->toBe('video/mp4')
        ->and($response->headers->get('Cache-Control'))->toContain('no-store')
        ->and($response->headers->get('Cache-Control'))->toContain('private')
        ->and($response->headers->get('Cache-Control'))->not->toContain('public');
});

test('a range request gets partial content so the player can seek', function () {
    $response = $this->get(ProtectedMediaUrl::video(PROTECTED_VIDEO_PATH), ['Range' => 'bytes=0-99'])
        ->assertStatus(206);

    expect($response->headers->get('Content-Range'))->toBe('bytes 0-99/1000');
});

test('the signed url does not depend on scheme or host', function () {
    expect(ProtectedMediaUrl::video(PROTECTED_VIDEO_PATH))->toStartWith('/media/video/'.PROTECTED_VIDEO_PATH.'?');
});

test('a url without a signature is refused', function () {
    $this->get(route('client.media.video', ['path' => PROTECTED_VIDEO_PATH]))->assertForbidden();
});

test('a url with an altered signature is refused', function () {
    $url = ProtectedMediaUrl::video(PROTECTED_VIDEO_PATH);
    $signature = substr($url, -1) === 'a' ? 'b' : 'a';

    $this->get(substr($url, 0, -1).$signature)->assertForbidden();
});

test('a signature cannot be reused for another video', function () {
    Storage::disk('local')->put('ngoi_am_duong_ct/videos/other.mp4', 'other');
    $url = str_replace('clip.mp4', 'other.mp4', ProtectedMediaUrl::video(PROTECTED_VIDEO_PATH));

    $this->get($url)->assertForbidden();
});

test('the url stops working after the configured lifetime', function () {
    config(['content_protection.video_url_ttl_minutes' => 30]);
    $url = ProtectedMediaUrl::video(PROTECTED_VIDEO_PATH);

    $this->travel(29)->minutes();
    $this->get($url)->assertOk();

    $this->travel(2)->minutes();
    $this->get($url)->assertForbidden();
});

test('requests that are not the site\'s own player are refused', function (array $headers) {
    $this->get(ProtectedMediaUrl::video(PROTECTED_VIDEO_PATH), $headers)->assertForbidden();
})->with([
    'opened directly in a tab' => [['Sec-Fetch-Dest' => 'document']],
    'embedded by another site' => [['Sec-Fetch-Site' => 'cross-site']],
    'referred by another host' => [['Referer' => 'https://example.com/']],
]);

test('the site\'s own player is served', function () {
    $this->get(ProtectedMediaUrl::video(PROTECTED_VIDEO_PATH), [
        'Sec-Fetch-Dest' => 'video',
        'Sec-Fetch-Site' => 'same-origin',
        'Referer' => url('/san-pham/ngoi-am-duong/1'),
    ])->assertOk();
});

test('a valid signature never unlocks other private files', function (string $path) {
    Storage::disk('local')->put('content-archives/x.zip', 'archive bytes');
    Storage::disk('local')->put('content-archives/x.mp4', 'archive bytes');
    Storage::disk('local')->put('catalog/files/book.pdf', 'pdf bytes');

    $response = $this->get(signedVideoUrlFor($path));

    expect($response->getStatusCode())->toBeIn([403, 404]);
})->with([
    'content archive' => ['content-archives/x.zip'],
    'catalog pdf' => ['catalog/files/book.pdf'],
    'parent traversal' => ['ngoi_am_duong_ct/videos/../../content-archives/x.mp4'],
    'missing video' => ['ngoi_am_duong_ct/videos/missing.mp4'],
]);

test('the video route is not counted against the page rate limit', function () {
    config(['content_protection.page_rate_limit' => 2]);
    $url = ProtectedMediaUrl::video(PROTECTED_VIDEO_PATH);

    foreach (range(1, 4) as $attempt) {
        $this->get($url, ['Range' => 'bytes=0-99'])->assertStatus(206);
    }
});

test('the product page points file videos at the signed route', function () {
    $product = app(ProductWriter::class)->create('ngoi_am_duong_ct', [
        'code' => 'NAD-PRIVATE-VIDEO',
        'name' => 'Ngói có video riêng tư',
        'color' => 'Tự chọn',
        'price' => 25000,
        'size' => '20x20',
        'images' => [
            'assets/images/ngoi-01.jpg',
            ['type' => 'video', 'source' => 'file', 'path' => PROTECTED_VIDEO_PATH],
        ],
        'is_delete' => 0,
    ]);

    $this->get(route('client.products.ngoi-am-duong.detail', $product->ngoi_am_duong_ct_id))
        ->assertOk()
        ->assertSee('/media/video/'.PROTECTED_VIDEO_PATH.'?expires=', false)
        ->assertDontSee('storage/'.PROTECTED_VIDEO_PATH, false);
});
