<?php

use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    Storage::fake('local');
});

test('a catalog file moves to the private disk under the same path', function () {
    Storage::disk('public')->put('catalog/files/a.pdf', 'pdf bytes');
    Storage::disk('public')->put('catalog/images/cover.webp', 'image bytes');
    Storage::disk('public')->put('du_an/images/photo.webp', 'photo bytes');

    $this->artisan('media:privatize')->assertSuccessful();

    Storage::disk('public')->assertMissing('catalog/files/a.pdf');
    expect(Storage::disk('local')->get('catalog/files/a.pdf'))->toBe('pdf bytes');

    Storage::disk('public')->assertExists('catalog/images/cover.webp');
    Storage::disk('public')->assertExists('du_an/images/photo.webp');
    Storage::disk('local')->assertMissing('catalog/images/cover.webp');
});

test('a dry run moves nothing', function () {
    Storage::disk('public')->put('catalog/files/a.pdf', 'pdf bytes');

    $this->artisan('media:privatize', ['--dry-run' => true])
        ->expectsOutputToContain('catalog/files/a.pdf')
        ->assertSuccessful();

    Storage::disk('public')->assertExists('catalog/files/a.pdf');
    Storage::disk('local')->assertMissing('catalog/files/a.pdf');
});

test('a second run finds nothing left to move', function () {
    Storage::disk('public')->put('catalog/files/a.pdf', 'pdf bytes');

    $this->artisan('media:privatize')->assertSuccessful();
    $this->artisan('media:privatize')->assertSuccessful();

    expect(Storage::disk('local')->get('catalog/files/a.pdf'))->toBe('pdf bytes');
});

test('a source left behind by an interrupted run is removed once the copy matches', function () {
    Storage::disk('public')->put('catalog/files/a.pdf', 'pdf bytes');
    Storage::disk('local')->put('catalog/files/a.pdf', 'pdf bytes');

    $this->artisan('media:privatize')->assertSuccessful();

    Storage::disk('public')->assertMissing('catalog/files/a.pdf');
    expect(Storage::disk('local')->get('catalog/files/a.pdf'))->toBe('pdf bytes');
});

test('a different file already at the destination is never overwritten', function () {
    Storage::disk('public')->put('catalog/files/a.pdf', 'public bytes');
    Storage::disk('local')->put('catalog/files/a.pdf', 'private bytes');

    $this->artisan('media:privatize')->assertFailed();

    expect(Storage::disk('public')->get('catalog/files/a.pdf'))->toBe('public bytes')
        ->and(Storage::disk('local')->get('catalog/files/a.pdf'))->toBe('private bytes');
});

test('reverse returns private files to the public disk and leaves other private data alone', function () {
    Storage::disk('local')->put('catalog/files/a.pdf', 'pdf bytes');
    Storage::disk('local')->put('content-archives/export.zip', 'archive bytes');

    $this->artisan('media:privatize', ['--reverse' => true])->assertSuccessful();

    Storage::disk('local')->assertMissing('catalog/files/a.pdf');
    expect(Storage::disk('public')->get('catalog/files/a.pdf'))->toBe('pdf bytes');
    Storage::disk('local')->assertExists('content-archives/export.zip');
    Storage::disk('public')->assertMissing('content-archives/export.zip');
});
