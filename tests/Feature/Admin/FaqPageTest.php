<?php

use App\Models\Faq;
use App\Models\PageFaq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::where('role', 'superadmin')->first()
        ?? User::factory()->create(['role' => 'superadmin']);

    // Ensure a PageFaq record exists for firstOrFail queries
    if (PageFaq::query()->count() === 0) {
        PageFaq::create([
            'banner_image' => 'assets/images/faq-banner.png',
        ]);
    }

    // Ensure at least one Faq item exists for update/delete tests
    if (Faq::query()->where('is_delete', 0)->count() === 0) {
        Faq::create([
            'category' => 'sản-phẩm',
            'question' => 'Default question?',
            'answer' => 'Default answer.',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }
});

// ── Banner tests ────────────────────────────────────────────────────────

test('faq page edit renders', function () {
    actingAs($this->admin)
        ->get(route('admin.pages.faq.edit'))
        ->assertOk();
});

test('faq page banner updates without image', function () {
    actingAs($this->admin)
        ->put(route('admin.pages.faq.update'), [])
        ->assertRedirect()
        ->assertSessionHas('success');
});

// ── FAQ items CRUD tests ────────────────────────────────────────────────

test('faq item can be created', function () {
    actingAs($this->admin)
        ->post(route('admin.pages.faqs.store'), [
            'category' => 'sản-phẩm',
            'question' => 'Test question?',
            'answer' => 'Test answer.',
            'sort_order' => 1,
            'is_active' => true,
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect(Faq::where('question', 'Test question?')->where('is_delete', 0)->exists())->toBeTrue();
});

test('faq item can be updated', function () {
    $faq = Faq::where('is_delete', 0)->first();

    actingAs($this->admin)
        ->put(route('admin.pages.faqs.update', $faq), [
            'category' => 'báo-giá',
            'question' => 'Updated question?',
            'answer' => 'Updated answer.',
            'sort_order' => 5,
            'is_active' => true,
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $faq->refresh();
    expect($faq->question)->toBe('Updated question?');
    expect($faq->category)->toBe('báo-giá');
});

test('faq item can be soft deleted', function () {
    $faq = Faq::where('is_delete', 0)->first();

    actingAs($this->admin)
        ->delete(route('admin.pages.faqs.destroy', $faq))
        ->assertRedirect()
        ->assertSessionHas('success');

    $faq->refresh();
    expect($faq->is_delete)->toBe(1);
});

test('faq store requires question and answer', function () {
    actingAs($this->admin)
        ->post(route('admin.pages.faqs.store'), [
            'category' => 'sản-phẩm',
        ])
        ->assertSessionHasErrors(['question', 'answer']);
});
