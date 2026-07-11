<?php

use App\Models\Catalog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    $this->admin = User::factory()->create(['role' => 'admin']);
});

test('it stores new catalog via ajax', function () {
    $image = UploadedFile::fake()->image('test_image.jpg');
    $file = UploadedFile::fake()->create('test_file.pdf', 5000); // 5MB

    $response = actingAs($this->admin)
        ->postJson(route('admin.catalog.store'), [
            'tieu_de' => 'Catalog Title ABC',
            'anh_dai_dien' => $image,
            'file' => $file,
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'catalog' => [
                'catalog_id',
                'tieu_de',
                'anh_dai_dien',
                'file',
            ],
        ]);

    $this->assertDatabaseHas('catalog', [
        'tieu_de' => 'Catalog Title ABC',
    ]);

    $catalog = Catalog::first();
    Storage::disk('public')->assertExists($catalog->anh_dai_dien);
    Storage::disk('public')->assertExists($catalog->file);
});

test('it rejects invalid image mime via ajax', function () {
    $invalidImage = UploadedFile::fake()->create('test_image.txt', 10);

    $response = actingAs($this->admin)
        ->postJson(route('admin.catalog.store'), [
            'tieu_de' => 'Catalog Title ABC',
            'anh_dai_dien' => $invalidImage,
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['anh_dai_dien']);
});

test('it rejects oversized catalog file via ajax', function () {
    // 204801 KB is > 200MB (limit is 204800 KB)
    $bigFile = UploadedFile::fake()->create('big_file.pdf', 204801);

    $response = actingAs($this->admin)
        ->postJson(route('admin.catalog.store'), [
            'tieu_de' => 'Catalog Title ABC',
            'file' => $bigFile,
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['file']);
});

test('it updates catalog image via ajax and removes old file', function () {
    $catalog = Catalog::factory()->create([
        'anh_dai_dien' => 'catalog/images/old_image.jpg',
        'file' => 'catalog/files/old_file.pdf',
    ]);

    // Seed fake files to storage to check deletion
    Storage::disk('public')->put('catalog/images/old_image.jpg', 'fake content');
    Storage::disk('public')->put('catalog/files/old_file.pdf', 'fake content');

    Storage::disk('public')->assertExists('catalog/images/old_image.jpg');

    $newImage = UploadedFile::fake()->image('new_image.png');

    $response = actingAs($this->admin)
        ->putJson(route('admin.catalog.update', $catalog->catalog_id), [
            'tieu_de' => 'Updated Title',
            'anh_dai_dien' => $newImage,
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    $catalog->refresh();
    expect($catalog->tieu_de)->toBe('Updated Title');

    Storage::disk('public')->assertMissing('catalog/images/old_image.jpg');
    Storage::disk('public')->assertExists($catalog->anh_dai_dien);
    // File remains unchanged
    Storage::disk('public')->assertExists('catalog/files/old_file.pdf');
});
