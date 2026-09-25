<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanStagedImagesCommand extends Command
{
    protected $signature = 'images:clean-staged';

    protected $description = 'Remove expired temporary image uploads';

    public function handle(): int
    {
        $disk = Storage::disk('local');
        $now = now()->timestamp;
        $removed = 0;

        foreach ($disk->allFiles('staged-images') as $path) {
            if (! str_ends_with($path, '/metadata.json')) {
                continue;
            }

            $metadata = json_decode($disk->get($path), true);
            if (! is_array($metadata) || (int) ($metadata['expires_at'] ?? 0) < $now) {
                $disk->deleteDirectory(dirname($path));
                $removed++;
            }
        }

        foreach ($disk->allFiles('image-upload-chunks') as $path) {
            if (! str_ends_with($path, '/_meta.json')) {
                continue;
            }

            $metadata = json_decode($disk->get($path), true);
            if (! is_array($metadata) || (int) ($metadata['created_at'] ?? 0) < $now - 3600) {
                $disk->deleteDirectory(dirname($path));
                $removed++;
            }
        }

        $this->info("Removed {$removed} expired staged image upload(s).");

        return self::SUCCESS;
    }
}
