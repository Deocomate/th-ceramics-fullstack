<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
     * Fallbacks safely to standard upload if optimization fails.
     *
     * @param  UploadedFile  $file  The uploaded file
     * @param  string  $directory  Directory path relative to public disk
     * @param  string|null  $slug  Optional SEO slug
     * @return string Stored file path relative to public disk
     */
    public function optimize(UploadedFile $file, string $directory, ?string $slug = null): string
    {
        $directory = trim($directory, '/');

        // If optimization is disabled or file is non-image, store normally
        if (! config('image_optimizer.enabled', true) || ! $this->isOptimizable($file)) {
            return $this->fallbackStore($file, $directory, $slug);
        }

        try {
            $preset = $this->resolvePreset($directory);
            $targetFormat = strtolower((string) config('image_optimizer.format', 'webp'));

            $extension = $targetFormat === 'original'
                ? strtolower($file->getClientOriginalExtension() ?: 'jpg')
                : 'webp';

            $filename = $this->generateSeoFilename($file, $slug, $extension);
            $targetPath = $directory !== '' ? $directory.'/'.$filename : $filename;

            $realPath = $file->getRealPath() ?: $file->getPathname();
            $image = Image::read($realPath);

            // 1. Auto-orient based on EXIF tag from mobile devices
            if (config('image_optimizer.auto_orient', true) && method_exists($image, 'orient')) {
                $image->orient();
            }

            // 2. Proportional downscale (scaleDown will never upscale smaller images)
            $maxWidth = (int) ($preset['max_width'] ?? 1600);
            $maxHeight = (int) ($preset['max_height'] ?? 1600);
            $image->scaleDown(width: $maxWidth, height: $maxHeight);

            // 3. Encode image according to format and quality
            $quality = (int) ($preset['quality'] ?? 82);
            $encoded = match ($extension) {
                'png' => $image->toPng(),
                'jpg', 'jpeg' => $image->toJpeg(quality: $quality),
                'avif' => $image->toAvif(quality: $quality),
                default => $image->toWebp(quality: $quality),
            };

            // 4. Save to public disk
            Storage::disk('public')->put($targetPath, (string) $encoded);

            return $targetPath;
        } catch (\Throwable $e) {
            Log::warning('ImageOptimizerService failed, falling back to standard upload', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'directory' => $directory,
            ]);

            return $this->fallbackStore($file, $directory, $slug);
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

        return "{$base}_{$timestamp}_{$random}." . ltrim($extension, '.');
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
