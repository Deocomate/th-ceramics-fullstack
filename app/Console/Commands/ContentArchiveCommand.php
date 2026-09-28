<?php

namespace App\Console\Commands;

use App\Domains\Archive\ContentArchiveService;
use Illuminate\Console\Command;
use Throwable;

class ContentArchiveCommand extends Command
{
    protected $signature = 'content:archive {action : export, preview, or import} {file? : ZIP path for preview/import}';

    protected $description = 'Export or import website content and its media';

    public function handle(ContentArchiveService $archive): int
    {
        $action = $this->argument('action');
        $file = $this->argument('file');
        try {
            if ($action === 'export') {
                $this->info($archive->export());

                return self::SUCCESS;
            }
            if (! is_string($file) || ! is_file($file)) {
                $this->error('Cần cung cấp đường dẫn ZIP hợp lệ.');

                return self::FAILURE;
            }
            if ($action === 'preview') {
                $this->line(json_encode($archive->preview($file), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                return self::SUCCESS;
            }
            if ($action === 'import') {
                $this->line(json_encode($archive->import($file), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                return self::SUCCESS;
            }
            $this->error('Action chỉ nhận export, preview hoặc import.');

            return self::INVALID;
        } catch (Throwable $error) {
            $this->error($error->getMessage());

            return self::FAILURE;
        }
    }
}
