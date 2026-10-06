<?php

use App\Domains\Content\Infrastructure\Models\Faq;
use App\Domains\Content\Infrastructure\Models\NewsArticle;
use App\Domains\Content\Infrastructure\Models\NewsCategory;
use App\Domains\Content\Infrastructure\Models\Project;
use App\Domains\Content\Infrastructure\Models\ProjectCategory;
use App\Domains\Identity\Infrastructure\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'superadmin']);
    Storage::fake('public');
});

test('faq resource entry points redirect to the configured faq page route', function () {
    $faq = Faq::query()->create([
        'category' => 'san-pham',
        'question' => 'Cau hoi?',
        'answer' => 'Cau tra loi.',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.pages.faqs.index'))
        ->assertRedirect(route('admin.pages.faq.edit'));
    $this->actingAs($this->admin)
        ->get(route('admin.pages.faqs.create'))
        ->assertRedirect(route('admin.pages.faq.edit'));
    $this->actingAs($this->admin)
        ->get(route('admin.pages.faqs.edit', $faq))
        ->assertRedirect(route('admin.pages.faq.edit'));
});

test('news article creation persists and redirects to the configured news index route', function () {
    $category = NewsCategory::query()->create(['ten_danh_muc' => 'Tin moi']);

    $this->actingAs($this->admin)
        ->post(route('admin.tin-tuc.store'), [
            'danh_muc_tin_tuc_id' => $category->getKey(),
            'tieu_de' => 'Tin tuc kiem tra redirect',
            'mo_ta_ngan' => 'Mo ta kiem tra',
            'trang_thai' => 'draft',
            'anh_dai_dien' => UploadedFile::fake()->image('news.jpg', 64, 64),
        ])
        ->assertRedirect(route('admin.tin-tuc.index'))
        ->assertSessionHas('success');

    expect(NewsArticle::query()->where('tieu_de', 'Tin tuc kiem tra redirect')->exists())->toBeTrue();
});

test('project creation persists and redirects to the configured project index route', function () {
    $category = ProjectCategory::query()->create(['ten_danh_muc' => 'Du an moi']);

    $this->actingAs($this->admin)
        ->post(route('admin.du-an.store'), [
            'danh_muc_du_an_id' => $category->getKey(),
            'ten_du_an' => 'Du an kiem tra redirect',
            'dia_diem' => 'Ha Noi',
            'san_pham' => 'Ngoi gom',
            'nam' => 2026,
            'images' => [UploadedFile::fake()->image('project.jpg', 64, 64)],
        ])
        ->assertRedirect(route('admin.du-an.index'))
        ->assertSessionHas('success');

    expect(Project::query()->where('ten_du_an', 'Du an kiem tra redirect')->exists())->toBeTrue();
});
