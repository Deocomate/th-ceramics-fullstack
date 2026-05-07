<?php

use App\Models\PageContact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::where('role', 'superadmin')->first()
        ?? User::factory()->create(['role' => 'superadmin']);

    // Ensure a PageContact record exists for firstOrFail queries
    if (PageContact::query()->count() === 0) {
        PageContact::create([
            'hotline' => '0000 000 000',
            'form_title' => 'Default Form Title',
        ]);
    }
});

test('contact edit page renders', function () {
    actingAs($this->admin)
        ->get(route('admin.pages.contact.edit'))
        ->assertOk();
});

test('contact page updates text fields', function () {
    actingAs($this->admin)
        ->put(route('admin.pages.contact.update'), [
            'hotline' => '0999 999 999',
            'form_title' => 'Test Form Title',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $contact = PageContact::first();
    expect($contact->hotline)->toBe('0999 999 999');
    expect($contact->form_title)->toBe('Test Form Title');
});
