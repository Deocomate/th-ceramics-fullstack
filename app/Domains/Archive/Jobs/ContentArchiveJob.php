<?php

namespace App\Domains\Archive\Jobs;

use App\Domains\Archive\ContentArchiveService;
use App\Domains\Content\Infrastructure\Services\ContentWriteLock;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class ContentArchiveJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $action, public string $statusPath, public ?string $file = null) {}

    public function handle(ContentArchiveService $archive): void
    {
        $lockToken = null;
        $lockAcquired = false;

        try {
            if ($this->action === 'import') {
                $lockToken = (string) Str::uuid();
                $lockAcquired = ContentWriteLock::acquire($lockToken);
                if (! $lockAcquired) {
                    throw new RuntimeException('Không thể khóa thao tác sửa nội dung để nhập archive. Hệ thống đang bị khóa bởi tiến trình khác.');
                }
            }

            $result = $this->action === 'export' ? $archive->export() : $archive->import((string) $this->file);
            file_put_contents($this->statusPath, json_encode(['state' => 'completed', 'result' => $result], JSON_UNESCAPED_UNICODE));
        } catch (Throwable $error) {
            file_put_contents($this->statusPath, json_encode(['state' => 'failed', 'error' => $error->getMessage()], JSON_UNESCAPED_UNICODE));
            throw $error;
        } finally {
            if ($lockAcquired && $lockToken !== null) {
                ContentWriteLock::release($lockToken);
            }
        }
    }
}
