<?php

namespace App\Helpers;

use App\Services\ImageOptimizerService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadHelper
{
    /**
     * Upload a file to the specified directory under storage/app/public.
     * Automatically optimizes and resizes images to SEO standards.
     * Non-image files (PDFs, Videos, etc.) are stored unmodified.
     *
     * @param  UploadedFile  $file  The uploaded file.
     * @param  string  $directory  Sub-directory inside public disk (e.g. 'uploads/avatars').
     * @param  string|null  $slug  Optional slug for naming the file.
     * @return string The stored path relative to public disk.
     */
    public static function upload(UploadedFile $file, string $directory, ?string $slug = null): string
    {
        /** @var ImageOptimizerService $optimizer */
        $optimizer = app(ImageOptimizerService::class);

        return $optimizer->optimize($file, $directory, $slug);
    }

    /**
     * Delete a file from public disk if it exists.
     *
     * @param  string|null  $path  Path relative to public disk.
     */
    public static function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Replace an old file with a new upload. Deletes old file first.
     *
     * @param  UploadedFile  $file  New file.
     * @param  string|null  $oldPath  Old file path to delete.
     * @param  string  $directory  Upload directory.
     * @param  string|null  $slug  Optional slug for naming.
     * @return string New stored path.
     */
    public static function replace(UploadedFile $file, ?string $oldPath, string $directory, ?string $slug = null): string
    {
        $newPath = static::upload($file, $directory, $slug);
        static::delete($oldPath);

        return $newPath;
    }
}
