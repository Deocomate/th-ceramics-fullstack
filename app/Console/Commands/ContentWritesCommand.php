<?php

namespace App\Console\Commands;

use App\Domains\Content\Infrastructure\Services\ContentWriteLock;
use Illuminate\Console\Command;

class ContentWritesCommand extends Command
{
    protected $signature = 'content:writes {state : lock, unlock, or status}';

    protected $description = 'Control admin content writes during final migration reconciliation';

    public function handle(): int
    {
        return match ($this->argument('state')) {
            'lock' => $this->lock(),
            'unlock' => $this->unlock(),
            'status' => $this->status(),
            default => self::INVALID,
        };
    }

    private function lock(): int
    {
        if (! ContentWriteLock::manualLock()) {
            $this->error('Không thể khóa: thao tác sửa nội dung đang bị khóa bởi tiến trình khác.');

            return self::FAILURE;
        }
        $this->info('Đã khóa thao tác sửa nội dung trong quản trị.');

        return self::SUCCESS;
    }

    private function unlock(): int
    {
        if (! ContentWriteLock::manualUnlock()) {
            $this->error('Không thể mở khóa: khóa đang thuộc về tiến trình khác.');

            return self::FAILURE;
        }
        $this->info('Đã mở thao tác sửa nội dung.');

        return self::SUCCESS;
    }

    private function status(): int
    {
        $this->line(ContentWriteLock::isLocked() ? 'locked' : 'open');

        return self::SUCCESS;
    }
}
