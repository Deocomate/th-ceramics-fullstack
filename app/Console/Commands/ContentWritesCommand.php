<?php

namespace App\Console\Commands;

use App\Http\Middleware\EnsureContentWritesOpen;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ContentWritesCommand extends Command
{
    protected $signature = 'content:writes {state : lock, unlock, or status}';

    protected $description = 'Control admin content writes during final migration reconciliation';

    public function handle(): int
    {
        $path = storage_path(EnsureContentWritesOpen::LOCK_FILE);
        return match ($this->argument('state')) {
            'lock' => $this->lock($path),
            'unlock' => $this->unlock($path),
            'status' => $this->status($path),
            default => self::INVALID,
        };
    }

    private function lock(string $path): int
    {
        File::ensureDirectoryExists(dirname($path));
        file_put_contents($path, now('UTC')->toIso8601String(), LOCK_EX);
        $this->info('Đã khóa thao tác sửa nội dung trong quản trị.');

        return self::SUCCESS;
    }

    private function unlock(string $path): int
    {
        @unlink($path);
        $this->info('Đã mở thao tác sửa nội dung.');

        return self::SUCCESS;
    }

    private function status(string $path): int
    {
        $this->line(is_file($path) ? 'locked' : 'open');

        return self::SUCCESS;
    }
}
