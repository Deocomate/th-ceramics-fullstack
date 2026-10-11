<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

test('a converted storage image redirects permanently to its WebP file', function () {
    Storage::disk('public')->put('a/b.webp', 'webp');

    $this->get('/storage/a/b.png')->assertStatus(301)->assertRedirect('/storage/a/b.webp');
    $this->get('/storage/a/b.JPG')->assertStatus(301)->assertRedirect('/storage/a/b.webp');
});

test('a converted static asset redirects permanently to its WebP file', function () {
    $publicPath = storage_path('framework/testing/legacy-image-public');
    File::ensureDirectoryExists($publicPath.'/assets/images/home');
    File::put($publicPath.'/assets/images/home/hero.webp', 'webp');
    $this->app->usePublicPath($publicPath);

    try {
        $this->get('/assets/images/home/hero.jpeg')->assertStatus(301)->assertRedirect('/assets/images/home/hero.webp');
    } finally {
        File::deleteDirectory($publicPath);
    }
});

test('a percent-encoded image name is looked up decoded and redirected encoded', function () {
    Storage::disk('public')->put('tin_tuc/Gốm thumb.webp', 'webp');

    $this->get('/storage/tin_tuc/G%E1%BB%91m%20thumb.png')
        ->assertStatus(301)
        ->assertRedirect('/storage/tin_tuc/G%E1%BB%91m%20thumb.webp');
});

// The framework's signed storage/{path} route answers unmatched storage paths with 403.
test('an image with no WebP file is not redirected', function () {
    $this->get('/storage/a/missing.png')->assertForbidden();
    $this->get('/assets/images/missing.png')->assertNotFound();
});

test('an image that still exists is not redirected', function () {
    Storage::disk('public')->put('a/b.png', 'png');
    Storage::disk('public')->put('a/b.webp', 'webp');

    $this->get('/storage/a/b.png')->assertForbidden();
});

test('paths that are not legacy images are not affected', function () {
    Storage::disk('public')->put('a/b.webp', 'webp');
    Storage::disk('public')->put('catalog/files/c.webp', 'webp');

    $this->get('/storage/catalog/files/c.pdf')->assertForbidden();
    $this->get('/storage/a/b.webp')->assertForbidden();
    $this->get('/tin-tuc/b.png')->assertNotFound();
    $this->post('/storage/a/b.png')->assertStatus(405);
    $this->get(route('client.home'))->assertOk();
});
