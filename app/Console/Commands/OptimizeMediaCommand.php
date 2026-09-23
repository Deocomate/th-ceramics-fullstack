<?php

namespace App\Console\Commands;

use App\Services\ImageOptimizerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class OptimizeMediaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:optimize 
                            {directory? : Thư mục cần quét trong public storage (ví dụ: ngoi_am_duong_ct)}
                            {--dry-run : Chỉ quét và báo cáo ước tính dung lượng tiết kiệm, không sửa file}
                            {--min-size=300 : Dung lượng tối thiểu (KB) để xem xét tối ưu}
                            {--convert-webp : Chuyển đổi sang định dạng .webp}
                            {--force : Thực thi mà không yêu cầu xác nhận}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quét và tối ưu hóa hình ảnh chuẩn SEO trên public storage.';

    /**
     * Execute the console command.
     */
    public function handle(ImageOptimizerService $optimizer): int
    {
        $directory = $this->argument('directory') ? trim((string) $this->argument('directory'), '/') : '';
        $isDryRun = (bool) $this->option('dry-run');
        $minSizeKb = (float) $this->option('min-size');
        $convertToWebp = (bool) $this->option('convert-webp') || config('image_optimizer.format', 'webp') === 'webp';
        $minSizeBytes = (int) ($minSizeKb * 1024);

        $disk = Storage::disk('public');
        $this->info("Đang quét thư mục: storage/app/public/{$directory} (Kích thước >= {$minSizeKb} KB)...");

        $allFiles = $disk->allFiles($directory);
        $candidates = [];

        foreach ($allFiles as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'bmp'], true)) {
                continue;
            }

            $size = $disk->size($file);
            if ($size >= $minSizeBytes) {
                $candidates[] = [
                    'path' => $file,
                    'size' => $size,
                    'extension' => $ext,
                ];
            }
        }

        if (empty($candidates)) {
            $this->info('Không tìm thấy hình ảnh nào cần tối ưu thỏa mãn điều kiện.');

            return self::SUCCESS;
        }

        $totalOriginalBytes = array_sum(array_column($candidates, 'size'));
        $count = count($candidates);

        $this->info("Tìm thấy {$count} hình ảnh cần tối ưu (Tổng dung lượng: ".number_format($totalOriginalBytes / 1048576, 2).' MB).');

        if ($isDryRun) {
            $rows = [];
            foreach (array_slice($candidates, 0, 15) as $c) {
                $rows[] = [
                    $c['path'],
                    number_format($c['size'] / 1024, 1).' KB',
                ];
            }

            $this->table(['Đường dẫn', 'Dung lượng hiện tại'], $rows);

            if ($count > 15) {
                $this->comment('... và '.($count - 15).' file khác.');
            }

            $this->info('[DRY-RUN] Dự kiến tiết kiệm 40% - 75% dung lượng khi chạy lệnh không có cờ --dry-run.');

            return self::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm("Bạn có chắc chắn muốn tối ưu {$count} hình ảnh trên?")) {
            $this->warn('Đã hủy thao tác.');

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $optimizedCount = 0;
        $totalSavedBytes = 0;

        foreach ($candidates as $c) {
            $relativePath = $c['path'];
            $fullPath = $disk->path($relativePath);
            $dir = pathinfo($relativePath, PATHINFO_DIRNAME);
            $preset = $optimizer->resolvePreset($dir === '.' ? '' : $dir);

            try {
                $image = Image::read($fullPath);

                if (config('image_optimizer.auto_orient', true) && method_exists($image, 'orient')) {
                    $image->orient();
                }

                $maxWidth = (int) ($preset['max_width'] ?? 1600);
                $maxHeight = (int) ($preset['max_height'] ?? 1600);
                $image->scaleDown(width: $maxWidth, height: $maxHeight);

                $quality = (int) ($preset['quality'] ?? 82);

                if ($convertToWebp) {
                    $encodedWebp = $image->toWebp(quality: $quality);
                    $newPath = preg_replace('/\.[^.]+$/', '.webp', $relativePath);
                    $disk->put($newPath, (string) $encodedWebp);
                }

                // Always optimize original file in-place so existing DB references never 404
                $encodedOriginal = match ($c['extension']) {
                    'png' => $image->toPng(),
                    'webp' => $image->toWebp(quality: $quality),
                    default => $image->toJpeg(quality: $quality),
                };
                $disk->put($relativePath, (string) $encodedOriginal);
                $newSize = $disk->size($relativePath);

                $saved = max(0, $c['size'] - $newSize);
                $totalSavedBytes += $saved;
                $optimizedCount++;
            } catch (\Throwable $e) {
                // Ignore single file error, continue
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $savedMb = number_format($totalSavedBytes / 1048576, 2);
        $percent = $totalOriginalBytes > 0 ? round(($totalSavedBytes / $totalOriginalBytes) * 100, 1) : 0;

        $this->info("Đã tối ưu thành công {$optimizedCount}/{$count} hình ảnh.");
        $this->info("Tổng dung lượng tiết kiệm: {$savedMb} MB ({$percent}%).");

        return self::SUCCESS;
    }
}
