<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    Storage::fake('local');
});

function legacyImage(string $path, int $width, int $height): string
{
    $image = imagecreatetruecolor($width, $height);
    imagefill($image, 0, 0, imagecolorallocate($image, 150, 90, 40));

    ob_start();
    str_ends_with($path, '.png') ? imagepng($image) : (str_ends_with($path, '.webp') ? imagewebp($image) : imagejpeg($image));
    $contents = (string) ob_get_clean();
    imagedestroy($image);

    Storage::disk('public')->put($path, $contents);

    return $contents;
}

function legacyProduct(array $values = []): int
{
    return DB::table('products')->insertGetId($values + ['type_key' => 'gach_trang_tri_ct', 'name' => 'Gạch thử']);
}

function legacyMedia(int $productId, string $path, int $sortOrder = 0): int
{
    return DB::table('product_media')->insertGetId([
        'product_id' => $productId,
        'kind' => 'image',
        'path' => $path,
        'sort_order' => $sortOrder,
    ]);
}

function conversionManifest(): array
{
    $files = array_values(array_filter(
        Storage::disk('local')->allFiles('media-originals'),
        fn (string $file) => str_ends_with($file, 'manifest.json'),
    ));
    expect($files)->toHaveCount(1);

    return json_decode(Storage::disk('local')->get($files[0]), true) + ['path' => $files[0]];
}

test('an oversized PNG becomes a WebP inside the preset and the references follow', function () {
    $original = legacyImage('du_an/images/big.png', 2500, 2500);
    $product = legacyProduct(['des' => json_encode(['du_an/images/big.png', 'du_an/images/small.png'])]);
    $media = legacyMedia($product, 'du_an/images/big.png');

    $this->artisan('media:convert-webp', ['--force' => true])->assertSuccessful();

    $public = Storage::disk('public');
    $public->assertMissing('du_an/images/big.png');
    $public->assertExists('du_an/images/big.webp');

    $dimensions = getimagesize($public->path('du_an/images/big.webp'));
    expect($dimensions['mime'])->toBe('image/webp')
        ->and($dimensions[0])->toBe(2000)
        ->and($dimensions[1])->toBe(2000)
        ->and($public->size('du_an/images/big.webp'))->toBeLessThan(1_000_000);

    expect(DB::table('product_media')->where('id', $media)->value('path'))->toBe('du_an/images/big.webp')
        ->and(json_decode(DB::table('products')->where('id', $product)->value('des'), true))
        ->toBe(['du_an/images/big.webp', 'du_an/images/small.png']);

    $manifest = conversionManifest();
    expect($manifest['status'])->toBe('complete')
        ->and($manifest['entries'])->toHaveCount(1)
        ->and($manifest['entries'][0]['source'])->toBe('du_an/images/big.png')
        ->and($manifest['entries'][0]['original'])->toStartWith('media-originals/')
        ->and(Storage::disk('local')->get($manifest['entries'][0]['original']))->toBe($original);
});

test('images within the limits are left untouched', function () {
    $small = legacyImage('du_an/images/small.png', 300, 200);
    legacyImage('du_an/images/photo.jpg', 1200, 800);

    $this->artisan('media:convert-webp', ['--force' => true])->assertSuccessful();

    expect(Storage::disk('public')->allFiles())->toEqualCanonicalizing(['du_an/images/small.png', 'du_an/images/photo.jpg'])
        ->and(Storage::disk('public')->get('du_an/images/small.png'))->toBe($small)
        ->and(Storage::disk('local')->allFiles())->toBe([]);
});

test('an oversized WebP is re-encoded in place and its original is kept', function () {
    $original = legacyImage('du_an/images/wide.webp', 2600, 1300);
    $media = legacyMedia(legacyProduct(), 'du_an/images/wide.webp');

    $this->artisan('media:convert-webp', ['--force' => true])->assertSuccessful();

    $dimensions = getimagesize(Storage::disk('public')->path('du_an/images/wide.webp'));
    expect($dimensions[0])->toBe(2000)
        ->and($dimensions[1])->toBe(1000)
        ->and(DB::table('product_media')->where('id', $media)->value('path'))->toBe('du_an/images/wide.webp')
        ->and(Storage::disk('local')->get(conversionManifest()['entries'][0]['original']))->toBe($original);
});

test('a dry run writes nothing', function () {
    $original = legacyImage('du_an/images/big.png', 2500, 2500);
    $media = legacyMedia(legacyProduct(), 'du_an/images/big.png');

    $this->artisan('media:convert-webp', ['--dry-run' => true])
        ->expectsOutputToContain('Tìm thấy 1 ảnh')
        ->expectsOutputToContain('product_media.path: 1')
        ->assertSuccessful();

    expect(Storage::disk('public')->allFiles())->toBe(['du_an/images/big.png'])
        ->and(Storage::disk('public')->get('du_an/images/big.png'))->toBe($original)
        ->and(Storage::disk('local')->allFiles())->toBe([])
        ->and(DB::table('product_media')->where('id', $media)->value('path'))->toBe('du_an/images/big.png');
});

test('the seeders directory is skipped unless the flag is given', function () {
    legacyImage('seeders/products/big.png', 2500, 2500);
    $media = legacyMedia(legacyProduct(), 'seeders/products/big.png');

    $this->artisan('media:convert-webp', ['--force' => true])->assertSuccessful();

    Storage::disk('public')->assertExists('seeders/products/big.png');
    expect(DB::table('product_media')->where('id', $media)->value('path'))->toBe('seeders/products/big.png');

    $this->artisan('media:convert-webp', ['--force' => true, '--include-seeders' => true])->assertSuccessful();

    Storage::disk('public')->assertMissing('seeders/products/big.png');
    Storage::disk('public')->assertExists('seeders/products/big.webp');
    expect(DB::table('product_media')->where('id', $media)->value('path'))->toBe('seeders/products/big.webp');
});

test('an original that already has a WebP beside it is retired in favour of that file', function () {
    $original = legacyImage('du_an/images/big.png', 2500, 2500);
    $existing = legacyImage('du_an/images/big.webp', 1000, 1000);
    $product = legacyProduct();
    $media = legacyMedia($product, 'du_an/images/big.png', 0);
    $alreadyWebp = legacyMedia($product, 'du_an/images/big.webp', 1);

    $this->artisan('media:convert-webp', ['--force' => true])->assertSuccessful();

    $manifest = conversionManifest();
    expect(Storage::disk('public')->allFiles())->toBe(['du_an/images/big.webp'])
        ->and(Storage::disk('public')->get('du_an/images/big.webp'))->toBe($existing)
        ->and(Storage::disk('local')->get($manifest['entries'][0]['original']))->toBe($original)
        ->and(DB::table('product_media')->where('id', $media)->value('path'))->toBe('du_an/images/big.webp');
    $this->artisan('media:convert-webp', ['--verify' => $manifest['run']])->assertSuccessful();

    // The WebP predates the run and had references of its own, so restoring returns the file only.
    $this->artisan('media:convert-webp', ['--restore' => $manifest['run'], '--force' => true])->assertSuccessful();

    expect(Storage::disk('public')->get('du_an/images/big.png'))->toBe($original)
        ->and(Storage::disk('public')->get('du_an/images/big.webp'))->toBe($existing)
        ->and(DB::table('product_media')->where('id', $media)->value('path'))->toBe('du_an/images/big.webp')
        ->and(DB::table('product_media')->where('id', $alreadyWebp)->value('path'))->toBe('du_an/images/big.webp');
});

test('an original whose same-named WebP is a different picture is skipped and listed', function () {
    $original = legacyImage('du_an/images/big.png', 2500, 2500);
    legacyImage('du_an/images/big.webp', 1000, 400);
    Storage::disk('public')->put('du_an/images/other.webp', 'not an image');
    legacyImage('du_an/images/other.png', 2500, 2500);
    $media = legacyMedia(legacyProduct(), 'du_an/images/big.png');

    $this->artisan('media:convert-webp', ['--force' => true])
        ->expectsOutputToContain('du_an/images/big.png: File .webp cùng tên là ảnh khác')
        ->expectsOutputToContain('du_an/images/other.png: File .webp cùng tên là ảnh khác')
        ->assertSuccessful();

    expect(Storage::disk('public')->get('du_an/images/big.png'))->toBe($original)
        ->and(Storage::disk('public')->exists('du_an/images/other.png'))->toBeTrue()
        ->and(Storage::disk('local')->allFiles())->toBe([])
        ->and(DB::table('product_media')->where('id', $media)->value('path'))->toBe('du_an/images/big.png');
});

test('two originals that would share one new WebP file convert the first and list the second', function () {
    legacyImage('du_an/images/big.jpg', 2500, 2500);
    $second = legacyImage('du_an/images/big.png', 2500, 2500);

    $this->artisan('media:convert-webp', ['--force' => true])
        ->expectsOutputToContain('du_an/images/big.png: File đích đã được ảnh khác dùng')
        ->assertSuccessful();

    expect(Storage::disk('public')->allFiles())->toEqualCanonicalizing(['du_an/images/big.png', 'du_an/images/big.webp'])
        ->and(Storage::disk('public')->get('du_an/images/big.png'))->toBe($second);
});

test('an unreadable oversized file is skipped and the rest still converts', function () {
    Storage::disk('public')->put('du_an/images/broken.jpg', str_repeat('x', 1_000_001));
    legacyImage('du_an/images/big.png', 2500, 2500);

    $this->artisan('media:convert-webp', ['--force' => true])
        ->expectsOutputToContain('du_an/images/broken.jpg: GD không đọc được')
        ->assertSuccessful();

    Storage::disk('public')->assertExists('du_an/images/broken.jpg');
    Storage::disk('public')->assertExists('du_an/images/big.webp');
});

test('a copy of a static asset on the public disk does not rewrite the static asset reference', function () {
    legacyImage('assets/images/hero.png', 2500, 2500);
    $product = legacyProduct();
    $staticAsset = legacyMedia($product, 'assets/images/hero.png', 0);
    $diskCopy = legacyMedia($product, 'storage/assets/images/hero.png', 1);

    $this->artisan('media:convert-webp', ['--force' => true])->assertSuccessful();

    expect(DB::table('product_media')->where('id', $staticAsset)->value('path'))->toBe('assets/images/hero.png')
        ->and(DB::table('product_media')->where('id', $diskCopy)->value('path'))->toBe('storage/assets/images/hero.webp');
});

test('verify passes after a run and fails when an old path or a missing file reappears', function () {
    legacyImage('du_an/images/big.png', 2500, 2500);
    $product = legacyProduct();
    $media = legacyMedia($product, 'du_an/images/big.png');
    legacyMedia($product, 'du_an/images/already-missing.jpg', 1);

    $this->artisan('media:convert-webp', ['--force' => true])->assertSuccessful();
    $manifest = conversionManifest();

    $this->artisan('media:convert-webp', ['--verify' => $manifest['run']])->assertSuccessful();
    $this->artisan('media:convert-webp', ['--verify' => $manifest['path']])->assertSuccessful();

    DB::table('product_media')->where('id', $media)->update(['path' => 'du_an/images/big.png']);
    $this->artisan('media:convert-webp', ['--verify' => $manifest['run']])
        ->expectsOutputToContain('product_media.path: 1 dòng còn chứa đường dẫn cũ')
        ->assertFailed();

    DB::table('product_media')->where('id', $media)->update(['path' => 'du_an/images/big.webp']);
    Storage::disk('public')->delete('du_an/images/big.webp');
    $this->artisan('media:convert-webp', ['--verify' => $manifest['run']])
        ->expectsOutputToContain('Thiếu file đã chuyển: du_an/images/big.webp')
        ->assertFailed();
});

test('restore returns the files and the database to their state before the run', function () {
    $png = legacyImage('du_an/images/big.png', 2500, 2500);
    $webp = legacyImage('du_an/images/wide.webp', 2600, 1300);
    $des = json_encode(['du_an/images/big.png', 'du_an/images/wide.webp']);
    $product = legacyProduct(['des' => $des]);
    $media = legacyMedia($product, 'du_an/images/big.png');

    $this->artisan('media:convert-webp', ['--force' => true])->assertSuccessful();
    $manifest = conversionManifest();

    $this->artisan('media:convert-webp', ['--restore' => $manifest['run'], '--force' => true])->assertSuccessful();

    expect(Storage::disk('public')->allFiles())->toEqualCanonicalizing(['du_an/images/big.png', 'du_an/images/wide.webp'])
        ->and(Storage::disk('public')->get('du_an/images/big.png'))->toBe($png)
        ->and(Storage::disk('public')->get('du_an/images/wide.webp'))->toBe($webp)
        ->and(DB::table('product_media')->where('id', $media)->value('path'))->toBe('du_an/images/big.png')
        ->and(DB::table('products')->where('id', $product)->value('des'))->toBe($des)
        ->and(conversionManifest()['status'])->toBe('restored');
});

test('verify fails when a reference to a file that did not exist before appears', function () {
    legacyImage('du_an/images/big.png', 2500, 2500);
    $media = legacyMedia(legacyProduct(), 'du_an/images/big.png');

    $this->artisan('media:convert-webp', ['--force' => true])->assertSuccessful();

    DB::table('product_media')->where('id', $media)->update(['path' => 'du_an/images/gone.webp']);
    $this->artisan('media:convert-webp', ['--verify' => conversionManifest()['run']])
        ->expectsOutputToContain('Tham chiếu trong DB trỏ tới file không tồn tại: du_an/images/gone.webp')
        ->assertFailed();
});

test('a run interrupted before the database rewrite can be restored and does not verify', function () {
    $original = legacyImage('du_an/images/big.png', 2500, 2500);
    Storage::disk('public')->put('du_an/images/big.webp', 'generated');
    $media = legacyMedia(legacyProduct(), 'du_an/images/big.png');
    Storage::disk('local')->put('media-originals/run-1/manifest.json', json_encode([
        'run' => 'run-1',
        'status' => 'pending',
        'missing_before' => [],
        'entries' => [[
            'source' => 'du_an/images/big.png',
            'target' => 'du_an/images/big.webp',
            'original' => 'media-originals/run-1/du_an/images/big.png',
        ]],
    ]));

    $this->artisan('media:convert-webp', ['--verify' => 'run-1'])
        ->expectsOutputToContain('Lần chạy chưa hoàn tất')
        ->assertFailed();
    $this->artisan('media:convert-webp', ['--restore' => 'run-1', '--force' => true])->assertSuccessful();

    expect(Storage::disk('public')->allFiles())->toBe(['du_an/images/big.png'])
        ->and(Storage::disk('public')->get('du_an/images/big.png'))->toBe($original)
        ->and(DB::table('product_media')->where('id', $media)->value('path'))->toBe('du_an/images/big.png');
});

test('restore keeps the WebP file and its references when the original is gone', function () {
    legacyImage('du_an/images/big.png', 2500, 2500);
    legacyImage('du_an/images/other.png', 2500, 2500);
    $product = legacyProduct();
    $lost = legacyMedia($product, 'du_an/images/big.png', 0);
    $kept = legacyMedia($product, 'du_an/images/other.png', 1);

    $this->artisan('media:convert-webp', ['--force' => true])->assertSuccessful();
    $manifest = conversionManifest();
    Storage::disk('local')->delete("media-originals/{$manifest['run']}/du_an/images/big.png");

    $this->artisan('media:convert-webp', ['--restore' => $manifest['run'], '--force' => true])
        ->expectsOutputToContain('Không tìm thấy bản gốc, giữ nguyên file WebP và tham chiếu: du_an/images/big.png')
        ->assertFailed();

    expect(Storage::disk('public')->allFiles())->toEqualCanonicalizing(['du_an/images/big.webp', 'du_an/images/other.png'])
        ->and(DB::table('product_media')->where('id', $lost)->value('path'))->toBe('du_an/images/big.webp')
        ->and(DB::table('product_media')->where('id', $kept)->value('path'))->toBe('du_an/images/other.png');
});

test('a run cannot be restored twice', function () {
    legacyImage('du_an/images/big.png', 2500, 2500);
    $media = legacyMedia(legacyProduct(), 'du_an/images/big.png');

    $this->artisan('media:convert-webp', ['--force' => true])->assertSuccessful();
    $run = conversionManifest()['run'];
    $this->artisan('media:convert-webp', ['--restore' => $run, '--force' => true])->assertSuccessful();

    DB::table('product_media')->where('id', $media)->update(['path' => 'du_an/images/big.webp']);
    $this->artisan('media:convert-webp', ['--restore' => $run, '--force' => true])
        ->expectsOutputToContain('Lần chạy này đã được khôi phục')
        ->assertFailed();

    expect(DB::table('product_media')->where('id', $media)->value('path'))->toBe('du_an/images/big.webp');
});

test('an unknown manifest fails', function () {
    $this->artisan('media:convert-webp', ['--verify' => 'no-such-run'])->assertFailed();
    $this->artisan('media:convert-webp', ['--restore' => 'no-such-run', '--force' => true])->assertFailed();
});
