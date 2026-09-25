<?php

namespace Tests\Unit\Services;

use App\Services\ImageOptimizerService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ImageOptimizerServiceTest extends TestCase
{
    protected ImageOptimizerService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ImageOptimizerService;
        Storage::fake('public');
    }

    public function test_it_identifies_optimizable_mime_types(): void
    {
        $jpeg = UploadedFile::fake()->image('photo.jpg');
        $png = UploadedFile::fake()->image('graphic.png');
        $webp = UploadedFile::fake()->image('banner.webp');
        $pdf = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');
        $svg = UploadedFile::fake()->create('vector.svg', 10, 'image/svg+xml');

        $this->assertTrue($this->service->isOptimizable($jpeg));
        $this->assertTrue($this->service->isOptimizable($png));
        $this->assertTrue($this->service->isOptimizable($webp));
        $this->assertFalse($this->service->isOptimizable($pdf));
        $this->assertFalse($this->service->isOptimizable($svg));
    }

    public function test_it_resolves_presets_matching_directories(): void
    {
        $avatarPreset = $this->service->resolvePreset('users/avatars');
        $this->assertEquals(500, $avatarPreset['max_width']);
        $this->assertEquals(500, $avatarPreset['max_height']);

        $bannerPreset = $this->service->resolvePreset('ve_chung_toi/banner');
        $this->assertEquals(2560, $bannerPreset['max_width']);
        $this->assertEquals(1440, $bannerPreset['max_height']);

        $productPreset = $this->service->resolvePreset('ngoi_am_duong_ct/gallery');
        $this->assertEquals(2000, $productPreset['max_width']);
        $this->assertEquals(2000, $productPreset['max_height']);

        $articlePreset = $this->service->resolvePreset('tin_tuc/blocks');
        $this->assertEquals(1600, $articlePreset['max_width']);
        $this->assertEquals(1600, $articlePreset['max_height']);

        $defaultPreset = $this->service->resolvePreset('other_random_dir');
        $this->assertEquals(2000, $defaultPreset['max_width']);
        $this->assertEquals(2000, $defaultPreset['max_height']);
    }

    public function test_it_generates_clean_seo_filename(): void
    {
        $file = UploadedFile::fake()->image('Ảnh Gốm Sứ Bát Tràng Đẹp 2026.jpg');

        $filename = $this->service->generateSeoFilename($file, 'Ngói Âm Dương Men Cổ', 'webp');
        $this->assertStringStartsWith('ngoi-am-duong-men-co_', $filename);
        $this->assertStringEndsWith('.webp', $filename);

        $defaultFilename = $this->service->generateSeoFilename($file, null, 'webp');
        $this->assertStringStartsWith('anh-gom-su-bat-trang-dep-2026_', $defaultFilename);
        $this->assertStringEndsWith('.webp', $defaultFilename);
    }

    public function test_it_proportionally_downscales_large_images(): void
    {
        // Create 3000x1500 image (2:1 aspect ratio)
        $file = UploadedFile::fake()->image('large-product.jpg', 3000, 1500);

        // ngoi_am_duong_ct preset max is 2000x2000
        $stored = $this->service->optimize($file, 'ngoi_am_duong_ct/images', 'ngoi-test');

        Storage::disk('public')->assertExists($stored);
        $this->assertStringEndsWith('.webp', $stored);

        $fullPath = Storage::disk('public')->path($stored);
        $dimensions = getimagesize($fullPath);

        $this->assertEquals(2000, $dimensions[0]);
        $this->assertEquals(1000, $dimensions[1]); // 2:1 aspect ratio preserved!
        $this->assertEquals('image/webp', $dimensions['mime']);
        $this->assertLessThan(1_000_000, filesize($fullPath));
    }

    public function test_it_does_not_upscale_small_images(): void
    {
        // Create 300x200 image
        $file = UploadedFile::fake()->image('small-icon.jpg', 300, 200);

        $stored = $this->service->optimize($file, 'ngoi_am_duong_ct/images', 'small-test');
        Storage::disk('public')->assertExists($stored);

        $fullPath = Storage::disk('public')->path($stored);
        $dimensions = getimagesize($fullPath);

        $this->assertEquals(300, $dimensions[0]);
        $this->assertEquals(200, $dimensions[1]);
    }

    public function test_it_preserves_transparency_when_converting_png_to_webp(): void
    {
        $temporaryPath = tempnam(sys_get_temp_dir(), 'transparent-png-');
        $image = imagecreatetruecolor(8, 8);
        imagealphablending($image, false);
        imagesavealpha($image, true);
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $transparent);
        imagepng($image, $temporaryPath);
        imagedestroy($image);

        try {
            $file = new UploadedFile($temporaryPath, 'transparent.png', 'image/png', UPLOAD_ERR_OK, true);
            $stored = $this->service->optimize($file, 'products', 'transparent');
            $optimized = imagecreatefromwebp(Storage::disk('public')->path($stored));

            $this->assertSame(127, (imagecolorat($optimized, 0, 0) >> 24) & 0x7F);
            imagedestroy($optimized);
        } finally {
            @unlink($temporaryPath);
        }
    }

    public function test_it_rejects_corrupted_images_instead_of_storing_the_original(): void
    {
        $corrupt = UploadedFile::fake()->create('corrupted.jpg', 10, 'image/jpeg');

        try {
            $this->service->optimize($corrupt, 'test_dir', 'corrupt-test');
            $this->fail('Corrupt images should be rejected.');
        } catch (ValidationException $exception) {
            $this->assertStringContainsString('corrupted.jpg', $exception->errors()['image'][0]);
            $this->assertSame([], Storage::disk('public')->allFiles('test_dir'));
        }
    }
}
