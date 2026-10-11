<?php

namespace App\Domains\Media\Console;

use App\Domains\Media\Infrastructure\ImageOptimizerService;
use App\Domains\Media\Infrastructure\ImageReferenceRewriter;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class ConvertLegacyImagesCommand extends Command
{
    private const VAULT = 'media-originals';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:convert-webp
                            {--dry-run : Chỉ liệt kê ảnh sẽ chuyển và số dòng DB sẽ sửa, không ghi gì}
                            {--force : Thực thi mà không yêu cầu xác nhận}
                            {--include-seeders : Xử lý cả thư mục seeders/ của public storage}
                            {--verify= : Kiểm chứng một lần chạy theo manifest (đường dẫn trên disk local hoặc mã lần chạy)}
                            {--restore= : Khôi phục file và DB của một lần chạy theo manifest}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chuyển ảnh cũ trên public storage sang WebP dưới 1MB, viết lại tham chiếu trong DB và giữ bản gốc trên disk riêng tư.';

    public function __construct(
        private readonly ImageOptimizerService $optimizer,
        private readonly ImageReferenceRewriter $rewriter,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->option('verify')) {
            return $this->verify((string) $this->option('verify'));
        }

        if ($this->option('restore')) {
            return $this->restore((string) $this->option('restore'));
        }

        return $this->convert();
    }

    private function convert(): int
    {
        $public = Storage::disk('public');
        $local = Storage::disk('local');

        ['candidates' => $candidates, 'skipped' => $skipped] = $this->plan($public);
        $count = count($candidates);
        $this->info("Tìm thấy {$count} ảnh trên 1MB hoặc vượt trần độ phân giải.");
        $this->reportSkipped($skipped);

        if ($this->option('dry-run')) {
            $this->table(
                ['Ảnh nguồn', 'File đích', 'Dung lượng'],
                array_map(static fn (array $candidate): array => [
                    $candidate['source'],
                    $candidate['target'].($candidate['retired'] ? ' (đã có sẵn)' : ''),
                    number_format($candidate['bytes'] / 1024).' KB',
                ], $candidates),
            );

            $tables = $this->rewriter->tables();
            $this->line('Bảng sẽ quét: '.implode(', ', array_keys($tables['scanned'])));
            $this->reportRewrite($this->rewriter->rewrite($this->referenceMap($candidates), apply: false), 'sẽ sửa');
            $this->info('[DRY-RUN] Chưa ghi file hay DB.');

            return self::SUCCESS;
        }

        if ($count === 0) {
            return self::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm("Chuyển {$count} ảnh sang WebP và viết lại tham chiếu trong DB?")) {
            $this->warn('Đã hủy thao tác.');

            return self::SUCCESS;
        }

        $this->raiseMemoryLimit();

        $run = now()->format('Ymd-His');
        $vault = self::VAULT.'/'.$run;
        $manifestPath = $vault.'/manifest.json';
        $manifest = [
            'run' => $run,
            'status' => 'pending',
            'include_seeders' => (bool) $this->option('include-seeders'),
            'missing_before' => $this->missingReferences($public),
            'entries' => [],
            'skipped' => $skipped,
            'changes' => [],
        ];

        // A run that dies part-way must still be restorable, so the manifest exists before any file does.
        if (! $local->put($manifestPath, $this->encodeManifest($manifest))) {
            $this->error('Không ghi được manifest trên disk local.');

            return self::FAILURE;
        }

        // 1. Write the WebP files; originals stay in place until the database points at the new names.
        $this->withProgressBar($candidates, function (array $candidate) use ($public, $local, $vault, $manifestPath, &$manifest): void {
            $source = $candidate['source'];
            $target = $candidate['target'];

            if ($candidate['retired']) {
                $manifest['entries'][] = [
                    'source' => $source,
                    'target' => $target,
                    'original' => $vault.'/'.$source,
                    'retired' => true,
                    'bytes_before' => $candidate['bytes'],
                    'bytes_after' => 0,
                ];
                if (! $local->put($manifestPath, $this->encodeManifest($manifest))) {
                    throw new \RuntimeException("Không ghi được manifest. Khôi phục bằng --restore={$manifest['run']}.");
                }

                return;
            }

            try {
                $encoded = $this->optimizer->encodeWebpUnderLimit(
                    $public->path($source),
                    $this->optimizer->presetForFile($source),
                );
            } catch (\Throwable $e) {
                $manifest['skipped'][] = ['path' => $source, 'reason' => 'Không mã hóa được: '.$e->getMessage()];

                return;
            }

            if ($source === $target) {
                $this->copyBetween($public, $source, $local, $vault.'/'.$source);
            }

            // Recorded before the file is written, so no generated file can exist without an entry.
            $manifest['entries'][] = [
                'source' => $source,
                'target' => $target,
                'original' => $vault.'/'.$source,
                'retired' => false,
                'bytes_before' => $candidate['bytes'],
                'bytes_after' => strlen($encoded),
            ];
            if (! $local->put($manifestPath, $this->encodeManifest($manifest)) || ! $public->put($target, $encoded)) {
                throw new \RuntimeException("Không ghi được {$target}. Khôi phục bằng --restore={$manifest['run']}.");
            }
        });
        $this->newLine(2);

        // 2. Rewrite the database in one transaction.
        try {
            $rewrite = $this->rewriter->rewrite($this->referenceMap($manifest['entries']));
        } catch (\Throwable $e) {
            $this->restoreFiles($manifest['entries'], $public, $local);
            $local->deleteDirectory($vault);
            $this->error('Viết lại DB thất bại, đã trả file về trạng thái cũ: '.$e->getMessage());

            return self::FAILURE;
        }

        // 3. Move the originals out of the public disk.
        try {
            foreach ($manifest['entries'] as $entry) {
                if ($entry['source'] === $entry['target']) {
                    continue;
                }

                $this->copyBetween($public, $entry['source'], $local, $entry['original']);
                if (! $public->delete($entry['source'])) {
                    throw new \RuntimeException("Không xóa được {$entry['source']}.");
                }
            }

            $manifest['status'] = 'complete';
            $manifest['changes'] = $rewrite['changes'];
            if (! $local->put($manifestPath, $this->encodeManifest($manifest))) {
                throw new \RuntimeException('Không ghi được manifest.');
            }
        } catch (\Throwable $e) {
            $this->error("DB đã được viết lại nhưng chưa chuyển hết bản gốc: {$e->getMessage()} Khôi phục bằng --restore={$run}.");

            return self::FAILURE;
        }

        // 4. Rendered pages and cached queries may still hold the old names.
        $this->callSilently('cache:clear');

        $before = array_sum(array_column($manifest['entries'], 'bytes_before'));
        $after = array_sum(array_column($manifest['entries'], 'bytes_after'));
        $this->info('Đã chuyển '.count($manifest['entries'])."/{$count} ảnh: "
            .number_format($before / 1048576, 1).' MB còn '.number_format($after / 1048576, 1).' MB.');
        $this->reportRewrite($rewrite, 'đã sửa');
        $this->reportSkipped(array_slice($manifest['skipped'], count($skipped)));
        $this->info("Manifest: {$manifestPath} (disk local). Kiểm chứng bằng --verify={$run}, khôi phục bằng --restore={$run}.");

        return self::SUCCESS;
    }

    private function verify(string $reference): int
    {
        $public = Storage::disk('public');
        $manifest = $this->loadManifest($reference);
        if ($manifest === null) {
            return self::FAILURE;
        }

        $failures = [];

        if (($manifest['status'] ?? null) !== 'complete') {
            $failures[] = 'Lần chạy chưa hoàn tất (trạng thái: '.($manifest['status'] ?? 'không rõ').')';
        }

        foreach ($this->rewriter->rewrite($this->referenceMap($manifest['entries']), apply: false)['changes'] as $column => $rows) {
            $failures[] = "{$column}: {$rows} dòng còn chứa đường dẫn cũ";
        }

        foreach ($manifest['entries'] as $entry) {
            if (! $public->exists($entry['target'])) {
                $failures[] = "Thiếu file đã chuyển: {$entry['target']}";
            }
        }

        foreach (array_diff($this->missingReferences($public), $manifest['missing_before']) as $missing) {
            $failures[] = "Tham chiếu trong DB trỏ tới file không tồn tại: {$missing}";
        }

        if ($failures !== []) {
            foreach ($failures as $failure) {
                $this->error($failure);
            }

            return self::FAILURE;
        }

        $this->info('Đạt: '.count($manifest['entries']).' ảnh đã chuyển, không còn đường dẫn cũ trong DB, không có tham chiếu thiếu file mới.');

        return self::SUCCESS;
    }

    private function restore(string $reference): int
    {
        $public = Storage::disk('public');
        $local = Storage::disk('local');
        $manifest = $this->loadManifest($reference);
        if ($manifest === null) {
            return self::FAILURE;
        }

        if (($manifest['status'] ?? null) === 'restored') {
            $this->error('Lần chạy này đã được khôi phục.');

            return self::FAILURE;
        }

        $count = count($manifest['entries']);
        if (! $this->option('force') && ! $this->confirm("Khôi phục {$count} ảnh gốc và viết lại DB theo chiều ngược?")) {
            $this->warn('Đã hủy thao tác.');

            return self::SUCCESS;
        }

        // Originals first, then the database, then the generated files: no reference ever points at a missing file.
        $restored = [];
        $lost = [];
        foreach ($manifest['entries'] as $entry) {
            $this->restoreOriginal($entry, $public, $local);
            $public->exists($entry['source']) ? $restored[] = $entry : $lost[] = $entry['source'];
        }

        $rewrite = $this->rewriter->rewrite(array_flip($this->referenceMap($restored, forRestore: true)));
        $this->deleteGenerated($restored, $public);

        $manifest['status'] = 'restored';
        $local->put(self::VAULT.'/'.$manifest['run'].'/manifest.json', $this->encodeManifest($manifest));
        $this->callSilently('cache:clear');

        $this->info('Đã khôi phục '.count($restored)."/{$count} ảnh gốc.");
        $this->reportRewrite($rewrite, 'đã sửa');

        if ($lost !== []) {
            $this->error('Không tìm thấy bản gốc, giữ nguyên file WebP và tham chiếu: '.implode(', ', $lost));

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    /**
     * @return array{candidates: list<array{source: string, target: string, bytes: int}>, skipped: list<array{path: string, reason: string}>}
     */
    private function plan(Filesystem $public): array
    {
        $candidates = [];
        $skipped = [];
        $targets = [];

        foreach ($public->allFiles() as $path) {
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if (! in_array($extension, ImageReferenceRewriter::IMAGE_EXTENSIONS, true)) {
                continue;
            }
            if (! $this->option('include-seeders') && str_starts_with($path, 'seeders/')) {
                continue;
            }

            $absolute = $public->path($path);
            if (! $this->optimizer->exceedsLimits($absolute, $this->optimizer->presetForFile($path))) {
                continue;
            }

            $dimensions = @getimagesize($absolute);
            if ($dimensions === false || $dimensions[0] * $dimensions[1] > ImageOptimizerService::MAX_PIXELS) {
                $skipped[] = ['path' => $path, 'reason' => 'GD không đọc được hoặc ảnh trên 60 triệu điểm ảnh'];

                continue;
            }

            $target = $extension === 'webp' ? $path : substr($path, 0, -strlen($extension)).'webp';
            if ($target !== $path && isset($targets[$target])) {
                $skipped[] = ['path' => $path, 'reason' => "File đích đã được ảnh khác dùng: {$target}"];

                continue;
            }

            // An earlier optimisation left a WebP beside the original: keep that file and only retire the original.
            $retired = $target !== $path && $public->exists($target);
            if ($retired) {
                $existing = @getimagesize($public->path($target));
                if ($existing === false || abs($existing[0] / $existing[1] - $dimensions[0] / $dimensions[1]) > 0.02) {
                    $skipped[] = ['path' => $path, 'reason' => "File .webp cùng tên là ảnh khác: {$target}"];

                    continue;
                }
            } else {
                $targets[$target] = true;
            }

            $candidates[] = ['source' => $path, 'target' => $target, 'bytes' => $public->size($path), 'retired' => $retired];
        }

        return ['candidates' => $candidates, 'skipped' => $skipped];
    }

    /**
     * Old => new reference paths for the entries whose file name changed.
     *
     * Restoring leaves out retired originals: their WebP file existed before the run and
     * may have had references of its own, so those references cannot be told apart.
     *
     * @param  list<array{source: string, target: string, retired?: bool}>  $entries
     * @return array<string, string>
     */
    private function referenceMap(array $entries, bool $forRestore = false): array
    {
        $map = [];
        foreach ($entries as $entry) {
            if ($forRestore && ($entry['retired'] ?? false)) {
                continue;
            }
            if ($entry['source'] !== $entry['target']) {
                $map[$this->referencePath($entry['source'])] = $this->referencePath($entry['target']);
            }
        }

        return $map;
    }

    /**
     * A bare `assets/...` reference means a file under public/, so disk files there need the URL prefix.
     */
    private function referencePath(string $diskPath): string
    {
        return str_starts_with($diskPath, 'assets/') ? 'storage/'.$diskPath : $diskPath;
    }

    /**
     * Image references in the database whose file exists neither on the public disk nor under public/.
     *
     * @return list<string>
     */
    private function missingReferences(Filesystem $public): array
    {
        $missing = [];

        foreach ($this->rewriter->references() as $reference) {
            $path = $reference;
            if (preg_match('~^https?://[^/]+(/.*)$~i', $path, $url)) {
                $path = $url[1];
                if (! preg_match('~^/(storage|assets)/~', $path)) {
                    continue;
                }
            }
            $path = ltrim((string) preg_replace('~[?#].*$~', '', $path), '/');

            $exists = false;
            foreach (array_unique([$path, rawurldecode($path)]) as $candidate) {
                $exists = $exists || (str_starts_with($candidate, 'assets/')
                    ? is_file(public_path($candidate))
                    : $public->exists((string) preg_replace('~^storage/~', '', $candidate)));
            }

            if (! $exists) {
                $missing[] = $reference;
            }
        }

        return $missing;
    }

    /**
     * Put originals back and drop the generated files of a run whose database rewrite did not happen.
     *
     * @param  list<array{source: string, target: string, original: string}>  $entries
     */
    private function restoreFiles(array $entries, Filesystem $public, Filesystem $local): void
    {
        foreach ($entries as $entry) {
            $this->restoreOriginal($entry, $public, $local);
        }

        $this->deleteGenerated($entries, $public);
    }

    /**
     * @param  array{source: string, original: string}  $entry
     */
    private function restoreOriginal(array $entry, Filesystem $public, Filesystem $local): void
    {
        if ($local->exists($entry['original'])) {
            $this->copyBetween($local, $entry['original'], $public, $entry['source']);
            $local->delete($entry['original']);
        }
    }

    /**
     * @param  list<array{source: string, target: string}>  $entries
     */
    private function deleteGenerated(array $entries, Filesystem $public): void
    {
        foreach ($entries as $entry) {
            if ($entry['source'] !== $entry['target'] && ! ($entry['retired'] ?? false) && $public->exists($entry['source'])) {
                $public->delete($entry['target']);
            }
        }
    }

    private function copyBetween(Filesystem $from, string $source, Filesystem $to, string $target): void
    {
        $stream = $from->readStream($source);
        if (! is_resource($stream) || ! $to->writeStream($target, $stream)) {
            throw new \RuntimeException("Không sao chép được {$source}.");
        }
        fclose($stream);
    }

    /**
     * @return array{run: string, status: string, missing_before: list<string>, entries: list<array{source: string, target: string, original: string}>}|null
     */
    private function loadManifest(string $reference): ?array
    {
        $local = Storage::disk('local');
        $path = $local->exists($reference) ? $reference : self::VAULT.'/'.$reference.'/manifest.json';
        $manifest = $local->exists($path) ? json_decode((string) $local->get($path), true) : null;

        if (! is_array($manifest) || ! isset($manifest['run'], $manifest['entries'])) {
            $this->error("Không đọc được manifest: {$reference}");

            return null;
        }

        return $manifest;
    }

    /**
     * @param  array<string, mixed>  $manifest
     */
    private function encodeManifest(array $manifest): string
    {
        return json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }

    /**
     * @param  array{changes: array<string, int>, skipped: list<string>}  $rewrite
     */
    private function reportRewrite(array $rewrite, string $verb): void
    {
        $this->info('Dòng DB '.$verb.': '.array_sum($rewrite['changes']).'.');
        foreach ($rewrite['changes'] as $column => $rows) {
            $this->line("  {$column}: {$rows}");
        }
        if ($rewrite['skipped'] !== []) {
            $this->warn('Bảng bị bỏ qua vì không có khóa chính một cột: '.implode(', ', $rewrite['skipped']));
        }
    }

    /**
     * @param  list<array{path: string, reason: string}>  $skipped
     */
    private function reportSkipped(array $skipped): void
    {
        if ($skipped === []) {
            return;
        }

        $this->warn('Bỏ qua '.count($skipped).' ảnh:');
        foreach ($skipped as $item) {
            $this->line("  {$item['path']}: {$item['reason']}");
        }
    }

    /**
     * GD holds a decoded image at four bytes per pixel, several copies at a time while resizing.
     */
    private function raiseMemoryLimit(): void
    {
        $limit = ini_parse_quantity((string) ini_get('memory_limit'));
        if ($limit !== -1 && $limit < 1024 * 1024 * 1024) {
            ini_set('memory_limit', '1024M');
        }
    }
}
