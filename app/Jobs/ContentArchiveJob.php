<?php

namespace App\Jobs;

use App\Services\ContentArchiveService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class ContentArchiveJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $action, public string $statusPath, public ?string $file = null) {}

    public function handle(ContentArchiveService $archive): void
    {
        try {
            $result = $this->action === 'export' ? $archive->export() : $archive->import((string) $this->file);
            file_put_contents($this->statusPath, json_encode(['state' => 'completed', 'result' => $result], JSON_UNESCAPED_UNICODE));
        } catch (Throwable $error) {
            file_put_contents($this->statusPath, json_encode(['state' => 'failed', 'error' => $error->getMessage()], JSON_UNESCAPED_UNICODE));
            throw $error;
        }
    }
}
