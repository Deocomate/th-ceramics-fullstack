<?php

namespace App\Domains\Media\Console;

use App\Domains\Media\Infrastructure\ImageOptimizerService;
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

        $savedBytes = 0;
        $optimizedCount = 0;
        $failedCount = 0;

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($candidates as $c) {
            $path = $c['path'];
            $fullPath = $disk->path($path);
            $dir = dirname($path);
            $filename = pathinfo($path, PATHINFO_FILENAME);
            $preset = $optimizer->resolvePreset($dir);

            try {
                $image = Image::read($fullPath);

                if (config('image_optimizer.auto_orient', true) && method_exists($image, 'orient')) {
                    $image->orient();
                }

                $maxWidth = (int) ($preset['max_width'] ?? 1600);
                $maxHeight = (int) ($preset['max_height'] ?? 1600);
                $image->scaleDown(width: $maxWidth, height: $maxHeight);

                $quality = min(90, max(45, (int) ($preset['quality'] ?? 82)));
                $encoded = null;

                if ($convertToWebp) {
                    for ($attempt = 0; $attempt < 40; $attempt++) {
                        $encoded = $image->toWebp(quality: $quality);
                        if (strlen((string) $encoded) < 1_000_000) {
                            break;
                        }

                        if ($quality > 50) {
                            $quality = max(50, $quality - 8);

                            continue;
                        }

                        $width = (int) $image->width();
                        $height = (int) $image->height();
                        if (max($width, $height) <= 320) {
                            $encoded = null;
                            break;
                        }

                        $image->scaleDown(
                            width: max(1, (int) round($width * 0.82)),
                            height: max(1, (int) round($height * 0.82)),
                        );
                        $quality = 74;
                    }

                    if ($encoded === null || strlen((string) $encoded) >= 1_000_000) {
                        $failedCount++;
                        $bar->advance();

                        continue;
                    }

                    $newPath = ($dir !== '.' ? $dir.'/' : '').$filename.'.webp';
                    $newSize = strlen((string) $encoded);

                    // If converting extension, save new and delete old if path changed
                    $disk->put($newPath, (string) $encoded);
                    if ($newPath !== $path) {
                        $disk->delete($path);
                    }

                    $savedBytes += max(0, $c['size'] - $newSize);
                    $optimizedCount++;
                } else {
                    $encoded = match ($c['extension']) {
                        'png' => $image->toPng(),
                        default => $image->toJpeg(quality: $quality),
                    };

                    $disk->put($path, (string) $encoded);
                    $newSize = strlen((string) $encoded);
                    $savedBytes += max(0, $c['size'] - $newSize);
                    $optimizedCount++;
                }
            } catch (\Throwable $e) {
                $failedCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $savedMb = number_format($savedBytes / 1048576, 2);
        $this->info("Hoàn tất tối ưu: Đã xử lý thành công {$optimizedCount}/{$count} hình ảnh.");
        $this->info("Dung lượng đã tiết kiệm: {$savedMb} MB.");

        if ($failedCount > 0) {
            $this->warn("Có {$failedCount} hình ảnh bị bỏ qua do lỗi hoặc vượt quá giới hạn tối ưu.");
        }

        return self::SUCCESS;
    }
}
