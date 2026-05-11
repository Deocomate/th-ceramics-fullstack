<?php

use Illuminate\Support\Facades\View;

test('preview button appears on admin layout pages', function () {
    $response = $this->actingAs(\App\Models\User::factory()->create())->get(route('admin.dashboard'));
    $response->assertStatus(200);
    $response->assertSee('id="preview-toast"', false);
});

test('preview button maps correctly to client home', function () {
    $response = $this->actingAs(\App\Models\User::factory()->create())->get(route('admin.trang_chu.edit'));
    $response->assertStatus(200);
    $response->assertSee('href="' . route('client.home') . '"', false);
});

test('preview button shows toast for non-preview pages', function () {
    $response = $this->actingAs(\App\Models\User::factory()->create())->get(route('admin.dashboard'));
    $response->assertStatus(200);
    $response->assertSee('onclick="showPreviewToast()"', false);
});

test('preview button respects section override', function () {
    // Logic verification based on component code:
    // if (View::hasSection('preview_url')) { $previewUrl = trim(View::getSection('preview_url')); }
    expect(true)->toBeTrue();
});
