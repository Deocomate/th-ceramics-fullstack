<?php

use App\Http\Middleware\SubstituteStagedImages;
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
