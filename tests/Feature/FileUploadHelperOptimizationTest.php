<?php

namespace Tests\Feature;

use App\Domains\Media\Infrastructure\FileUploadHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class FileUploadHelperOptimizationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Storage::fake('local');
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

        // Catalog files are private, so they never land on the public disk.
        Storage::disk('local')->assertExists($path);
        Storage::disk('public')->assertMissing($path);
        $this->assertStringEndsWith('.pdf', $path);
        $this->assertEquals($content, Storage::disk('local')->get($path));

        FileUploadHelper::delete($path);
        Storage::disk('local')->assertMissing($path);
    }

    public function test_file_upload_helper_keeps_other_documents_on_the_public_disk(): void
    {
        $file = UploadedFile::fake()->createWithContent('huong-dan.pdf', '%PDF-1.4 guide');

        $path = FileUploadHelper::upload($file, 'thi_cong/files', 'huong-dan');

        Storage::disk('public')->assertExists($path);
        Storage::disk('local')->assertMissing($path);
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

    public function test_failed_replacement_keeps_the_existing_image(): void
    {
        $oldPath = FileUploadHelper::upload(UploadedFile::fake()->image('old.jpg'), 'users/avatars');
        $corrupt = UploadedFile::fake()->create('broken.jpg', 10, 'image/jpeg');

        try {
            FileUploadHelper::replace($corrupt, $oldPath, 'users/avatars');
            $this->fail('Corrupt replacement should fail.');
        } catch (ValidationException $exception) {
            Storage::disk('public')->assertExists($oldPath);
            $this->assertCount(1, Storage::disk('public')->allFiles('users/avatars'));
        }
    }
}
