<?php

namespace App\Domains\Media\Console;

use App\Domains\Media\Infrastructure\MediaDisk;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PrivatizeMediaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:privatize
                            {--dry-run : Chỉ liệt kê file sẽ chuyển, không ghi gì}
                            {--reverse : Chuyển file riêng tư ngược về public storage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chuyển các file media riêng tư (file catalog gốc, video mp4/webm) từ public storage sang disk local, giữ nguyên đường dẫn.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        [$from, $to] = $this->option('reverse') ? ['local', 'public'] : ['public', 'local'];
        $source = Storage::disk($from);
        $target = Storage::disk($to);

        $paths = array_values(array_filter(
            $source->allFiles(),
            static fn (string $path): bool => MediaDisk::forPath($path) === 'local',
        ));

        $this->info('Tìm thấy '.count($paths)." file cần chuyển từ disk {$from} sang disk {$to}.");

        if ($this->option('dry-run')) {
            foreach ($paths as $path) {
                $this->line($path);
            }
            $this->info('[DRY-RUN] Chưa chuyển file nào.');

            return self::SUCCESS;
        }

        $failed = 0;
        foreach ($paths as $path) {
            $hash = hash_file('sha256', $source->path($path));

            if ($target->exists($path)) {
                // Never overwrite: the same bytes mean an earlier run was interrupted before the delete.
                if (hash_file('sha256', $target->path($path)) !== $hash) {
                    $this->error("Bỏ qua {$path}: disk {$to} đã có file khác nội dung.");
                    $failed++;

                    continue;
                }
            } else {
                $stream = $source->readStream($path);
                $written = is_resource($stream) && $target->writeStream($path, $stream);
                if (is_resource($stream)) {
                    fclose($stream);
                }

                if (! $written || hash_file('sha256', $target->path($path)) !== $hash) {
                    $target->delete($path);
                    $this->error("Không sao chép được {$path}; file nguồn được giữ nguyên.");
                    $failed++;

                    continue;
                }
            }

            $source->delete($path);
            $this->line("Đã chuyển {$path}");
        }

        if ($failed > 0) {
            $this->error("{$failed} file chưa được chuyển.");

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
