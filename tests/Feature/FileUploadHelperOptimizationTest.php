<?php

namespace Tests\Feature;

use App\Helpers\FileUploadHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadHelperOptimizationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_file_upload_helper_optimizes_image_to_webp(): void
    {
        $file = UploadedFile::fake()->image('bat-trang-ceramics.png', 1800, 1200);

        $path = FileUploadHelper::upload($file, 'trang_chu/images', 'banner-tet-2026');

        Storage::disk('public')->assertExists($path);
        $this->assertStringEndsWith('.webp', $path);

        $fullPath = Storage::disk('public')->path($path);
        $info = getimagesize($fullPath);
        $this->assertEquals('image/webp', $info['mime']);
        $this->assertLessThanOrEqual(1920, $info[0]);
    }

    public function test_file_upload_helper_leaves_pdf_files_unmodified(): void
    {
        $content = '%PDF-1.4 Fake PDF catalog content';
        $file = UploadedFile::fake()->createWithContent('catalogue-2026.pdf', $content);

        $path = FileUploadHelper::upload($file, 'catalog/files', 'catalog-official');

        Storage::disk('public')->assertExists($path);
        $this->assertStringEndsWith('.pdf', $path);
        $this->assertEquals($content, Storage::disk('public')->get($path));
    }

    public function test_file_upload_helper_replace_deletes_old_file_and_stores_new(): void
    {
        $oldFile = UploadedFile::fake()->image('old-avatar.jpg', 500, 500);
        $oldPath = FileUploadHelper::upload($oldFile, 'users/avatars', 'user-1-avatar');
        Storage::disk('public')->assertExists($oldPath);

        $newFile = UploadedFile::fake()->image('new-avatar.png', 600, 600);
        $newPath = FileUploadHelper::replace($newFile, $oldPath, 'users/avatars', 'user-1-new-avatar');

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($newPath);
        $this->assertStringEndsWith('.webp', $newPath);
    }
}
