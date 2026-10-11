<?php

use App\Domains\Content\Infrastructure\Models\Catalog;
use App\Domains\Identity\Infrastructure\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    Storage::fake('public');
    Storage::fake('local');
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->catalog = Catalog::factory()->create(['file' => 'catalog/files/book.pdf']);
});

function pageImage(int $width = 40, int $height = 60): UploadedFile
{
    $image = imagecreatetruecolor($width, $height);
    ob_start();
    imagewebp($image);
    $contents = (string) ob_get_clean();
    imagedestroy($image);

    return UploadedFile::fake()->createWithContent('page.webp', $contents);
}

function pagePath(Catalog $catalog, string $batch, int $index): string
{
    return sprintf('catalog/pages/%d/%s/%03d.webp', $catalog->catalog_id, $batch, $index);
}

function uploadPage(Catalog $catalog, array $overrides = [])
{
    return test()->post(route('admin.catalog.pages.store', $catalog->catalog_id), $overrides + [
        'batch' => (string) Str::uuid(),
        'index' => 0,
        'total' => 2,
        'image' => pageImage(),
    ], ['Accept' => 'application/json']);
}

test('guests and customers cannot reach the page image routes', function () {
    $id = $this->catalog->catalog_id;
    $requests = [
        ['get', route('admin.catalog.file', $id)],
        ['post', route('admin.catalog.pages.store', $id)],
        ['post', route('admin.catalog.pages.finalize', $id)],
    ];

    foreach ($requests as [$method, $url]) {
        $this->{$method}($url)->assertRedirect();
    }

    actingAs(User::factory()->create(['role' => 'customer']));
    foreach ($requests as [$method, $url]) {
        $this->{$method}($url)->assertForbidden();
    }
});

test('a page image is stored under the catalog and batch', function () {
    $batch = (string) Str::uuid();

    actingAs($this->admin);
    uploadPage($this->catalog, ['batch' => $batch, 'index' => 1])->assertOk();

    Storage::disk('public')->assertExists(pagePath($this->catalog, $batch, 1));
    expect($this->catalog->fresh()->pages)->toBeNull();
});

test('invalid page uploads are rejected', function (array $overrides, string $field) {
    actingAs($this->admin);

    uploadPage($this->catalog, array_map(fn ($value) => $value instanceof Closure ? $value() : $value, $overrides))
        ->assertStatus(422)
        ->assertJsonValidationErrors([$field]);

    expect(Storage::disk('public')->allFiles('catalog/pages'))->toBe([]);
})->with([
    'not a WebP image' => [['image' => fn () => UploadedFile::fake()->image('page.png')], 'image'],
    'larger than 1.5MB' => [['image' => fn () => UploadedFile::fake()->create('page.webp', 1600, 'image/webp')], 'image'],
    'index past the total' => [['index' => 2], 'index'],
    'negative index' => [['index' => -1], 'index'],
    'more pages than allowed' => [['total' => 801], 'total'],
    'batch that is not a UUID' => [['batch' => '../escape'], 'batch'],
]);

test('starting a new batch discards an abandoned one but keeps the published set', function () {
    $published = (string) Str::uuid();
    $abandoned = (string) Str::uuid();
    $fresh = (string) Str::uuid();
    Storage::disk('public')->put(pagePath($this->catalog, $published, 0), 'published');
    Storage::disk('public')->put(pagePath($this->catalog, $abandoned, 0), 'abandoned');
    $this->catalog->update(['pages' => ['batch' => $published, 'items' => [
        ['path' => pagePath($this->catalog, $published, 0), 'w' => 40, 'h' => 60, 'pdf_page' => 1, 'side' => 'full'],
    ]]]);

    actingAs($this->admin);
    uploadPage($this->catalog, ['batch' => $fresh])->assertOk();

    Storage::disk('public')->assertExists(pagePath($this->catalog, $published, 0));
    Storage::disk('public')->assertMissing(pagePath($this->catalog, $abandoned, 0));
    Storage::disk('public')->assertExists(pagePath($this->catalog, $fresh, 0));
});

test('finalize with a missing page changes nothing', function () {
    $batch = (string) Str::uuid();
    actingAs($this->admin);
    uploadPage($this->catalog, ['batch' => $batch, 'index' => 0])->assertOk();

    $this->postJson(route('admin.catalog.pages.finalize', $this->catalog->catalog_id), [
        'batch' => $batch,
        'pages' => [['pdf_page' => 1, 'side' => 'full'], ['pdf_page' => 2, 'side' => 'full']],
    ])->assertStatus(422)->assertJsonValidationErrors(['pages']);

    expect($this->catalog->fresh()->pages)->toBeNull();
    Storage::disk('public')->assertExists(pagePath($this->catalog, $batch, 0));
});

test('finalize with every page writes the manifest and removes the previous batch', function () {
    $old = (string) Str::uuid();
    $batch = (string) Str::uuid();
    Storage::disk('public')->put(pagePath($this->catalog, $old, 0), 'old page');
    $this->catalog->update(['pages' => ['batch' => $old, 'items' => [
        ['path' => pagePath($this->catalog, $old, 0), 'w' => 40, 'h' => 60, 'pdf_page' => 1, 'side' => 'full'],
    ]]]);

    actingAs($this->admin);
    uploadPage($this->catalog, ['batch' => $batch, 'index' => 0, 'total' => 3, 'image' => pageImage(40, 60)])->assertOk();
    uploadPage($this->catalog, ['batch' => $batch, 'index' => 1, 'total' => 3, 'image' => pageImage(30, 60)])->assertOk();
    uploadPage($this->catalog, ['batch' => $batch, 'index' => 2, 'total' => 3, 'image' => pageImage(30, 60)])->assertOk();

    Storage::disk('public')->assertExists(pagePath($this->catalog, $old, 0));

    $this->postJson(route('admin.catalog.pages.finalize', $this->catalog->catalog_id), [
        'batch' => $batch,
        'pages' => [
            ['pdf_page' => 1, 'side' => 'full'],
            ['pdf_page' => 2, 'side' => 'left'],
            ['pdf_page' => 2, 'side' => 'right'],
        ],
    ])->assertOk()->assertJson(['success' => true, 'pages' => 3]);

    expect($this->catalog->fresh()->pages)->toBe([
        'batch' => $batch,
        'items' => [
            ['path' => pagePath($this->catalog, $batch, 0), 'w' => 40, 'h' => 60, 'pdf_page' => 1, 'side' => 'full'],
            ['path' => pagePath($this->catalog, $batch, 1), 'w' => 30, 'h' => 60, 'pdf_page' => 2, 'side' => 'left'],
            ['path' => pagePath($this->catalog, $batch, 2), 'w' => 30, 'h' => 60, 'pdf_page' => 2, 'side' => 'right'],
        ],
    ]);
    Storage::disk('public')->assertMissing(pagePath($this->catalog, $old, 0));
});

test('finalize rejects an unknown page side', function () {
    $batch = (string) Str::uuid();
    actingAs($this->admin);
    uploadPage($this->catalog, ['batch' => $batch, 'index' => 0, 'total' => 1])->assertOk();

    $this->postJson(route('admin.catalog.pages.finalize', $this->catalog->catalog_id), [
        'batch' => $batch,
        'pages' => [['pdf_page' => 1, 'side' => 'middle']],
    ])->assertStatus(422)->assertJsonValidationErrors(['pages.0.side']);
});

test('replacing the PDF clears the page images', function () {
    $batch = (string) Str::uuid();
    Storage::disk('local')->put('catalog/files/book.pdf', 'old pdf');
    Storage::disk('public')->put(pagePath($this->catalog, $batch, 0), 'page');
    $this->catalog->update(['pages' => ['batch' => $batch, 'items' => [
        ['path' => pagePath($this->catalog, $batch, 0), 'w' => 40, 'h' => 60, 'pdf_page' => 1, 'side' => 'full'],
    ]]]);

    actingAs($this->admin)
        ->putJson(route('admin.catalog.update', $this->catalog->catalog_id), [
            'tieu_de' => 'Bản mới',
            'file' => UploadedFile::fake()->create('new.pdf', 100, 'application/pdf'),
        ])->assertOk();

    $catalog = $this->catalog->fresh();
    expect($catalog->pages)->toBeNull()
        ->and($catalog->file)->not->toBe('catalog/files/book.pdf');
    Storage::disk('local')->assertMissing('catalog/files/book.pdf');
    Storage::disk('local')->assertExists($catalog->file);
    Storage::disk('public')->assertMissing(pagePath($this->catalog, $batch, 0));
});

test('updating only the title keeps the page images', function () {
    $pages = ['batch' => 'b', 'items' => [['path' => 'catalog/pages/1/b/000.webp', 'w' => 40, 'h' => 60, 'pdf_page' => 1, 'side' => 'full']]];
    $this->catalog->update(['pages' => $pages]);

    actingAs($this->admin)
        ->putJson(route('admin.catalog.update', $this->catalog->catalog_id), ['tieu_de' => 'Tên mới'])
        ->assertOk();

    expect($this->catalog->fresh()->pages)->toBe($pages);
});

test('deleting a catalog removes its file and page images', function () {
    $batch = (string) Str::uuid();
    Storage::disk('local')->put('catalog/files/book.pdf', 'pdf');
    Storage::disk('public')->put(pagePath($this->catalog, $batch, 0), 'page');
    $this->catalog->update(['pages' => ['batch' => $batch, 'items' => [
        ['path' => pagePath($this->catalog, $batch, 0), 'w' => 40, 'h' => 60, 'pdf_page' => 1, 'side' => 'full'],
    ]]]);

    actingAs($this->admin)
        ->delete(route('admin.catalog.destroy', $this->catalog->catalog_id))
        ->assertRedirect();

    Storage::disk('local')->assertMissing('catalog/files/book.pdf');
    expect(Storage::disk('public')->allFiles('catalog/pages'))->toBe([]);
});

test('deleting a catalog leaves page images another catalog still shows', function () {
    $id = $this->catalog->catalog_id;
    $shared = "catalog/pages/{$id}/".Str::uuid().'/000.webp';
    Storage::disk('public')->put($shared, 'page');
    Catalog::factory()->create(['pages' => ['batch' => 'imported', 'items' => [
        ['path' => $shared, 'w' => 40, 'h' => 60, 'pdf_page' => 1, 'side' => 'full'],
    ]]]);

    actingAs($this->admin)->delete(route('admin.catalog.destroy', $id))->assertRedirect();

    Storage::disk('public')->assertExists($shared);
});

test('an admin reads the original file through the authenticated route', function () {
    Storage::disk('local')->put('catalog/files/book.pdf', '%PDF-1.4 original');

    $response = actingAs($this->admin)->get(route('admin.catalog.file', $this->catalog->catalog_id));

    $response->assertOk();
    expect($response->streamedContent())->toBe('%PDF-1.4 original')
        ->and($response->headers->get('Content-Disposition'))->toStartWith('inline')
        ->and($response->headers->get('Cache-Control'))->toContain('no-store')->toContain('private');
});

test('the file route refuses paths outside the catalog file directory', function () {
    Storage::disk('local')->put('content-archives/export.zip', 'archive');
    $this->catalog->update(['file' => 'content-archives/export.zip']);

    actingAs($this->admin)
        ->get(route('admin.catalog.file', $this->catalog->catalog_id))
        ->assertNotFound();
});
