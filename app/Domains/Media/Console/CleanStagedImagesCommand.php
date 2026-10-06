<?php

namespace App\Domains\Media\Console;

use App\Domains\Media\Infrastructure\StagedImageStore;
use Illuminate\Console\Command;

class CleanStagedImagesCommand extends Command
{
    protected $signature = 'images:clean-staged';

    protected $description = 'Remove expired temporary image uploads';

    public function handle(StagedImageStore $stagedStore): int
    {
        $removed = $stagedStore->cleanExpired();

        $this->info("Removed {$removed} expired staged image upload(s).");

        return self::SUCCESS;
    }
}
