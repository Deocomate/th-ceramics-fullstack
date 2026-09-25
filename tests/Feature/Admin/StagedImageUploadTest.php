<?php

use App\Http\Middleware\SubstituteStagedImages;
use App\Models\NgoiAmDuongCt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

function minimalWebpUpload(string $name = 'optimized.webp'): UploadedFile
{
    return UploadedFile::fake()->createWithContent(
        $name,
        base64_decode('UklGRiQAAABXRUJQVlA4IBgAAAAwAQCdASoBAAEAAwA0JaQAA3AA/vuUAAA=')
    );
}

function registerStagedImageTestRoute(): void
{
    Route::post('/_test/staged-image-fields', function (Request $request) {
        $request->setLaravelSession(app('session.store'));

        return app(SubstituteStagedImages::class)->handle($request, function (Request $request) {
            $file = $request->file('product.sections.0.image');

            return response()->json([
                'mime' => $file?->getMimeType(),
                'name' => $file?->getClientOriginalName(),
                'size' => $file?->getSize(),
                'keys' => array_keys($request->allFiles()),
            ]);
        });
    })->middleware(['auth']);
}

test('admin can stage a small webp in chunks for later form submission', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->postJson(route('admin.media.staged-images.store'), [
        'chunk' => minimalWebpUpload(),
        'upload_id' => (string) str()->uuid(),
        'chunk_index' => 0,
        'total_chunks' => 1,
        'original_name' => 'ceramic-product.webp',
    ])->assertOk()->assertJsonPath('complete', true);

    expect($response->json('token'))->toBeString();
    expect($response->json('size'))->toBeLessThan(1_000_000);
});

test('retrying the final image chunk returns the same staged token', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $this->actingAs($user);

    $uploadId = (string) str()->uuid();
    $bytes = base64_decode('UklGRiQAAABXRUJQVlA4IBgAAAAwAQCdASoBAAEAAwA0JaQAA3AA/vuUAAA=');
    $parts = str_split($bytes, (int) ceil(strlen($bytes) / 2));
    foreach ($parts as $index => $part) {
        $payload = [
            'chunk' => UploadedFile::fake()->createWithContent('part.bin', $part),
            'upload_id' => $uploadId,
            'chunk_index' => $index,
            'total_chunks' => count($parts),
            'original_name' => 'large-photo.webp',
        ];
        $response = $this->postJson(route('admin.media.staged-images.store'), $payload)->assertOk();
    }

    $retry = $this->postJson(route('admin.media.staged-images.store'), $payload)->assertOk();
    expect($retry->json('token'))->toBe($response->json('token'))
        ->and($retry->json('token'))->toBe($uploadId)
        ->and(Storage::disk('local')->allFiles("staged-images/{$user->getAuthIdentifier()}"))->toHaveCount(2);
});

test('staged image middleware restores a webp into the original nested upload field', function () {
    Storage::fake('local');
    registerStagedImageTestRoute();

    $user = User::factory()->create();
    $this->actingAs($user);
    $token = $this->postJson(route('admin.media.staged-images.store'), [
        'chunk' => minimalWebpUpload(),
        'upload_id' => (string) str()->uuid(),
        'chunk_index' => 0,
        'total_chunks' => 1,
        'original_name' => 'section-photo.webp',
    ])->assertOk()->json('token');

    $metadata = json_decode(Storage::disk('local')->get("staged-images/{$user->getAuthIdentifier()}/{$token}/metadata.json"), true);
    expect((int) $metadata['user_id'])->toBe((int) $user->getAuthIdentifier())
        ->and((int) $metadata['expires_at'])->toBeGreaterThan(now()->timestamp)
        ->and(hash_equals((string) $metadata['session_hash'], hash('sha256', (string) session()->getId())))->toBeTrue();

    $result = $this->postJson('/_test/staged-image-fields', [
        '__staged_images' => json_encode(['product[sections][0][image]' => [$token]], JSON_THROW_ON_ERROR),
    ])->assertOk();
    expect($result->json('keys'))->toBe(['product']);
    $result->assertJsonPath('mime', 'image/webp')
        ->assertJsonPath('name', 'section-photo.webp');
});

test('product creation accepts a staged gallery with the first image as cover', function () {
    Storage::fake('local');
    Storage::fake('public');
    $user = User::factory()->create();
    $this->actingAs($user);
    $this->withCredentials()->withCookie(config('session.cookie'), session()->getId());

    $tokens = [];
    foreach (['cover.webp', 'detail.webp'] as $name) {
        $tokens[] = $this->postJson(route('admin.media.staged-images.store'), [
            'chunk' => minimalWebpUpload(),
            'upload_id' => (string) str()->uuid(),
            'chunk_index' => 0,
            'total_chunks' => 1,
            'original_name' => $name,
        ])->assertOk()->json('token');
    }

    foreach ($tokens as $token) {
        $metadata = json_decode(Storage::disk('local')->get("staged-images/{$user->getAuthIdentifier()}/{$token}/metadata.json"), true);
        expect($metadata['session_hash'])->toBe(hash('sha256', (string) session()->getId()));
    }

    Route::post('/_test/staged-gallery-fields', function (Request $request) {
        $request->setLaravelSession(app('session.store'));

        return app(SubstituteStagedImages::class)->handle($request, function (Request $request) {
            return response()->json(array_map(fn ($file) => [
                'valid' => $file->isValid(),
                'error' => $file->getError(),
                'exists' => is_file($file->getPathname()),
            ], $request->file('images', [])));
        });
    })->middleware(['auth']);
    $inspection = $this->postJson('/_test/staged-gallery-fields', [
        '__staged_images' => json_encode(['images[]' => $tokens], JSON_THROW_ON_ERROR),
    ])->assertOk();
    expect($inspection->json())->toBe([
        ['valid' => true, 'error' => 0, 'exists' => true],
        ['valid' => true, 'error' => 0, 'exists' => true],
    ]);

    $this->post(route('admin.ngoi-am-duong-ct.store'), [
        'code' => 'NAD-STAGED-GALLERY-001',
        'name' => 'Ngói có ảnh tải tạm',
        'color' => 'Men đỏ',
        'price' => 30000,
        'size' => '20x20',
        '__staged_images' => json_encode(['images[]' => $tokens], JSON_THROW_ON_ERROR),
    ])->assertRedirect(route('admin.ngoi-am-duong-ct.index'));

    $product = NgoiAmDuongCt::query()->where('code', 'NAD-STAGED-GALLERY-001')->firstOrFail();
    expect($product->images)->toHaveCount(2);
    foreach ($product->images as $path) {
        Storage::disk('public')->assertExists($path);
    }
});

test('staged images cannot be reused by a different admin user', function () {
    Storage::fake('local');
    registerStagedImageTestRoute();
    $this->actingAs(User::factory()->create());
    $token = $this->postJson(route('admin.media.staged-images.store'), [
        'chunk' => minimalWebpUpload(),
        'upload_id' => (string) str()->uuid(),
        'chunk_index' => 0,
        'total_chunks' => 1,
        'original_name' => 'private-photo.webp',
    ])->assertOk()->json('token');

    $this->actingAs(User::factory()->create());
    $this->postJson('/_test/staged-image-fields', [
        '__staged_images' => json_encode(['product[sections][0][image]' => [$token]], JSON_THROW_ON_ERROR),
    ])->assertUnprocessable();
});

test('expired staged image is rejected with a useful message', function () {
    Storage::fake('local');
    registerStagedImageTestRoute();
    $user = User::factory()->create();
    $this->actingAs($user);

    $token = $this->postJson(route('admin.media.staged-images.store'), [
        'chunk' => minimalWebpUpload(),
        'upload_id' => (string) str()->uuid(),
        'chunk_index' => 0,
        'total_chunks' => 1,
        'original_name' => 'expired.webp',
    ])->assertOk()->json('token');

    $path = "staged-images/{$user->getAuthIdentifier()}/{$token}/metadata.json";
    $metadata = json_decode(Storage::disk('local')->get($path), true);
    $metadata['expires_at'] = now()->subMinute()->timestamp;
    Storage::disk('local')->put($path, json_encode($metadata, JSON_THROW_ON_ERROR));

    $this->postJson('/_test/staged-image-fields', [
        '__staged_images' => json_encode(['product[sections][0][image]' => [$token]], JSON_THROW_ON_ERROR),
    ])->assertUnprocessable()->assertJsonValidationErrors('product[sections][0][image]');
});

test('cleanup removes old gallery retry records and keeps recent ones', function () {
    Storage::fake('local');
    $disk = Storage::disk('local');
    $disk->put('gallery-chunks/1/old/_meta.json', json_encode(['created_at' => now()->subHours(2)->timestamp]));
    $disk->put('gallery-chunks/1/old/completed.json', '{}');
    $disk->put('gallery-chunks/1/new/_meta.json', json_encode(['created_at' => now()->timestamp]));
    $disk->put('gallery-chunks/1/new/completed.json', '{}');

    $this->artisan('images:clean-staged')->assertExitCode(0);

    $disk->assertMissing('gallery-chunks/1/old/completed.json');
    $disk->assertExists('gallery-chunks/1/new/completed.json');
});
