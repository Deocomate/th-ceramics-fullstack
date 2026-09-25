<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Laravel\Facades\Image;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

class ImageOptimizerService
{
    /**
     * Supported raster MIME types for optimization.
     */
    protected const OPTIMIZABLE_MIMES = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp',
        'image/bmp',
        'image/x-ms-bmp',
        'image/avif',
    ];

    /**
     * Check if a file is an optimizable raster image.
     */
    public function isOptimizable(mixed $file): bool
    {
        if ($file instanceof UploadedFile) {
            $mime = $file->getMimeType() ?: $file->getClientMimeType();

            return in_array(strtolower((string) $mime), self::OPTIMIZABLE_MIMES, true);
        }

        if ($file instanceof SymfonyFile) {
            $mime = $file->getMimeType();

            return in_array(strtolower((string) $mime), self::OPTIMIZABLE_MIMES, true);
        }

        if (is_string($file) && file_exists($file)) {
            $mime = @mime_content_type($file);

            return in_array(strtolower((string) $mime), self::OPTIMIZABLE_MIMES, true);
        }

        return false;
    }

    /**
     * Optimize an uploaded image and store it to disk.
     * Optimize raster images to WebP and keep every stored image below 1 MB.
     *
     * @param  UploadedFile  $file  The uploaded file
     * @param  string  $directory  Directory path relative to public disk
     * @param  string|null  $slug  Optional SEO slug
     * @return string Stored file path relative to public disk
     */
    public function optimize(UploadedFile $file, string $directory, ?string $slug = null): string
    {
        $directory = trim($directory, '/');

        // Non-image files (such as PDFs) retain their original content and format.
        if (! $this->isOptimizable($file)) {
            return $this->fallbackStore($file, $directory, $slug);
        }

        try {
            $preset = $this->resolvePreset($directory);
            $filename = $this->generateSeoFilename($file, $slug, 'webp');
            $targetPath = $directory !== '' ? $directory.'/'.$filename : $filename;

            $realPath = $file->getRealPath() ?: $file->getPathname();
            $sourceDimensions = @getimagesize($realPath);
            if ($sourceDimensions === false || ($sourceDimensions[0] * $sourceDimensions[1]) > 60_000_000) {
                throw new \RuntimeException('The uploaded image is invalid or exceeds the supported pixel limit.');
            }
            $image = Image::read($realPath);

            // 1. Auto-orient based on EXIF tag from mobile devices
            if (config('image_optimizer.auto_orient', true) && method_exists($image, 'orient')) {
                $image->orient();
            }

            // 2. Proportional downscale (scaleDown will never upscale smaller images)
            $maxWidth = (int) ($preset['max_width'] ?? 1600);
            $maxHeight = (int) ($preset['max_height'] ?? 1600);
            $image->scaleDown(width: $maxWidth, height: $maxHeight);

            // 3. Reduce quality first, then dimensions, until the SEO size cap is met.
            $quality = min(90, max(45, (int) ($preset['quality'] ?? 82)));
            $encoded = null;
            for ($attempt = 0; $attempt < 40; $attempt++) {
                $encoded = $image->toWebp(quality: $quality);
                if (strlen((string) $encoded) < 1_000_000) {
                    break;
                }

                if ($quality > 50) {
                    $quality = max(50, $quality - 8);

                    continue;
                }

                $width = (int) $image->width();
                $height = (int) $image->height();
                if (max($width, $height) <= 320) {
                    $encoded = null;
                    break;
                }

                $image->scaleDown(
                    width: max(1, (int) round($width * 0.82)),
                    height: max(1, (int) round($height * 0.82)),
                );
                $quality = 74;
            }

            if ($encoded === null || strlen((string) $encoded) >= 1_000_000) {
                throw new \RuntimeException('Unable to encode the image below the 1 MB storage limit.');
            }

            // 4. Save to public disk
            if (! Storage::disk('public')->put($targetPath, (string) $encoded)) {
                throw new \RuntimeException('Unable to write the optimized image to public storage.');
            }

            return $targetPath;
        } catch (\Throwable $e) {
            Log::warning('ImageOptimizerService failed to optimize uploaded image', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'directory' => $directory,
            ]);

            throw ValidationException::withMessages([
                'image' => 'Không thể tối ưu ảnh "'.$file->getClientOriginalName().'" thành WebP dưới 1MB. Hãy chọn ảnh khác hoặc thử lại.',
            ]);
        }
    }

    /**
     * Resolve dimensions and quality preset based on directory.
     *
     * @return array{max_width: int, max_height: int, quality: int}
     */
    public function resolvePreset(string $directory): array
    {
        $default = config('image_optimizer.presets.default', [
            'max_width' => 1600,
            'max_height' => 1600,
            'quality' => 82,
        ]);

        $presets = config('image_optimizer.presets', []);
        $cleanDir = strtolower(trim($directory, '/'));

        foreach ($presets as $key => $preset) {
            if ($key === 'default' || empty($preset['matches'])) {
                continue;
            }

            foreach ((array) $preset['matches'] as $match) {
                if (str_contains($cleanDir, strtolower($match))) {
                    return array_merge($default, $preset);
                }
            }
        }

        return $default;
    }

    /**
     * Generate an SEO-friendly filename.
     */
    public function generateSeoFilename(UploadedFile $file, ?string $slug = null, string $extension = 'webp'): string
    {
        if ($slug && trim($slug) !== '') {
            $base = Str::slug($slug);
        } else {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $base = Str::slug($originalName);
        }

        if ($base === '') {
            $base = Str::random(12);
        }

        $timestamp = time();
        $random = Str::lower(Str::random(6));

        return "{$base}_{$timestamp}_{$random}.".ltrim($extension, '.');
    }

    /**
     * Fallback standard file store when optimization is bypassed or fails.
     */
    protected function fallbackStore(UploadedFile $file, string $directory, ?string $slug = null): string
    {
        $extension = $file->getClientOriginalExtension() ?: 'bin';
        $filename = ($slug ? Str::slug($slug) : Str::random(16)).'_'.time().'.'.$extension;

        return $file->storeAs($directory, $filename, 'public');
    }
}
