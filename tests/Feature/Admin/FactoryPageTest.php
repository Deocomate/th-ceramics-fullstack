<?php

use App\Models\PageFactory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::where('role', 'superadmin')->first()
        ?? User::factory()->create(['role' => 'superadmin']);

    // Ensure a PageFactory record exists for firstOrFail queries
    if (PageFactory::query()->count() === 0) {
        PageFactory::create([
            'intro_title' => 'Default Title',
        ]);
    }
});

test('factory edit page renders', function () {
    actingAs($this->admin)
        ->get(route('admin.pages.factory.edit'))
        ->assertOk();
});

test('factory page update requires authentication', function () {
    put(route('admin.pages.factory.update'), ['intro_title' => 'Test'])
        ->assertRedirect(route('admin.auth.login'));
});

test('factory page updates text fields', function () {
    actingAs($this->admin)
        ->put(route('admin.pages.factory.update'), [
            'intro_title' => 'Test Factory Title',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect(PageFactory::first()->intro_title)->toBe('Test Factory Title');
});

test('factory page rejects invalid image type', function () {
    actingAs($this->admin)
        ->put(route('admin.pages.factory.update'), [
            'hero_banner_desktop' => 'not-an-image',
        ])
        ->assertSessionHasErrors('hero_banner_desktop');
});
