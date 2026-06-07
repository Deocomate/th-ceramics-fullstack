<?php

use App\Models\PageFactory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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

test('factory edit page renders legacy string descriptions as block data', function () {
    PageFactory::first()->forceFill([
        'intro_description' => 'Legacy <b>intro</b>',
    ])->save();

    actingAs($this->admin)
        ->get(route('admin.pages.factory.edit'))
        ->assertOk()
        ->assertSee('Legacy')
        ->assertDontSee('Legacy <b>intro</b>', false);
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

test('factory page updates and cleans structured description blocks', function () {
    actingAs($this->admin)
        ->put(route('admin.pages.factory.update'), [
            'process_description' => [
                [
                    'type' => 'paragraph',
                    'content' => " First paragraph \n with line ",
                ],
                [
                    'type' => 'paragraph',
                    'content' => '   ',
                ],
                [
                    'type' => 'list',
                    'items' => [
                        [
                            'title' => 'Item title:',
                            'content' => 'Item content',
                        ],
                        [
                            'title' => '',
                            'content' => '',
                        ],
                    ],
                ],
            ],
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    expect(PageFactory::first()->process_description)->toBe([
        [
            'type' => 'paragraph',
            'content' => "First paragraph \n with line",
        ],
        [
            'type' => 'list',
            'items' => [
                [
                    'title' => 'Item title:',
                    'content' => 'Item content',
                ],
            ],
        ],
    ]);
});

test('factory client process renders escaped blocks and responsive newlines', function () {
    $factory = PageFactory::first();
    $factory->update([
        'process_title' => "QUY TRÌNH\nKHOA HỌC",
        'process_description' => [
            [
                'type' => 'paragraph',
                'content' => "Line one\n<script>alert(1)</script>",
            ],
            [
                'type' => 'list',
                'items' => [
                    [
                        'title' => 'Khu vực tạo cốt:',
                        'content' => 'Nội dung list',
                    ],
                ],
            ],
        ],
        'process_bottom_desc' => [],
    ]);

    $html = view('components.client.factory.manufacturing-process', ['factory' => $factory->fresh()])->render();

    expect($html)
        ->toContain('QUY TRÌNH<br class="md:hidden" />KHOA HỌC')
        ->toContain('Line one<br />')
        ->toContain('&lt;script&gt;alert(1)&lt;/script&gt;')
        ->toContain('<ul class="space-y-1 list-decimal marker:font-bold marker:text-primary marker:mr-1 ml-5">')
        ->toContain('<strong class="text-primary font-bold">Khu vực tạo cốt:</strong>')
        ->not->toContain('<script>alert(1)</script>');
});

test('factory gallery 2 renders gallery 2 images instead of gallery 1 images', function () {
    $factory = PageFactory::first();
    $factory->update([
        'gallery_1' => ['gallery-one.jpg'],
        'gallery_2' => ['gallery-two.jpg'],
    ]);

    $html = view('components.client.factory.gallery-secondary', ['factory' => $factory->fresh()])->render();

    expect($html)
        ->toContain('/storage/gallery-two.jpg')
        ->not->toContain('/storage/gallery-one.jpg');
});

test('factory gallery 2 uploads and deletes by original index', function () {
    Storage::fake('public');

    PageFactory::first()->update([
        'gallery_2' => ['keep.jpg', 'delete-a.jpg', 'delete-b.jpg'],
    ]);

    actingAs($this->admin)
        ->put(route('admin.pages.factory.update'), [
            'delete_gallery_2' => [1, 2],
            'new_gallery_2' => [
                UploadedFile::fake()->create('new-gallery.jpg', 10, 'image/jpeg'),
            ],
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $gallery = PageFactory::first()->gallery_2;

    expect($gallery)->toHaveCount(2)
        ->and($gallery[0])->toBe('keep.jpg')
        ->and($gallery[1])->toStartWith('pages/factory/gallery_2/');
});

test('factory page rejects invalid image type', function () {
    actingAs($this->admin)
        ->put(route('admin.pages.factory.update'), [
            'hero_banner_desktop' => 'not-an-image',
        ])
        ->assertSessionHasErrors('hero_banner_desktop');
});
