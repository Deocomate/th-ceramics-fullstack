<?php

namespace App\Domains\Media\Infrastructure;

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

    protected const MAX_BYTES = 1_000_000;

    public const MAX_PIXELS = 60_000_000;

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

            $encoded = $this->encodeWebpUnderLimit($file->getRealPath() ?: $file->getPathname(), $preset);

            if (! Storage::disk('public')->put($targetPath, $encoded)) {
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
     * Preset for an existing image file, honouring the configured dimension exceptions.
     *
     * @return array{max_width: int, max_height: int, quality: int}
     */
    public function presetForFile(string $path): array
    {
        $preset = $this->resolvePreset(dirname($path));
        $withoutExtension = preg_replace('~\.[^./]+$~', '', $path);

        if (in_array($withoutExtension, (array) config('image_optimizer.keep_dimensions', []), true)) {
            $preset['max_width'] = $preset['max_height'] = PHP_INT_MAX;
        }

        return $preset;
    }

    /**
     * Whether a stored image is at or above the size cap or larger than its preset dimensions.
     *
     * @param  array{max_width?: int, max_height?: int}  $preset
     */
    public function exceedsLimits(string $path, array $preset): bool
    {
        if (filesize($path) >= self::MAX_BYTES) {
            return true;
        }

        $dimensions = @getimagesize($path);

        return $dimensions !== false
            && ($dimensions[0] > (int) ($preset['max_width'] ?? 1600) || $dimensions[1] > (int) ($preset['max_height'] ?? 1600));
    }

    /**
     * Encode an image file as WebP within the preset dimensions and below the size cap.
     *
     * @param  array{max_width?: int, max_height?: int, quality?: int}  $preset
     *
     * @throws \RuntimeException when the image is unreadable, too large to decode, or cannot fit the cap
     */
    public function encodeWebpUnderLimit(string $sourcePath, array $preset): string
    {
        $sourceDimensions = @getimagesize($sourcePath);
        if ($sourceDimensions === false || ($sourceDimensions[0] * $sourceDimensions[1]) > self::MAX_PIXELS) {
            throw new \RuntimeException('The image is invalid or exceeds the supported pixel limit.');
        }
        $image = Image::read($sourcePath);

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
            if (strlen((string) $encoded) < self::MAX_BYTES) {
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

        if ($encoded === null || strlen((string) $encoded) >= self::MAX_BYTES) {
            throw new \RuntimeException('Unable to encode the image below the 1 MB storage limit.');
        }

        return (string) $encoded;
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
