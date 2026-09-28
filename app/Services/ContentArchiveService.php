<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class ContentArchiveService
{
    public const FORMAT_VERSION = 1;

    public function directory(): string
    {
        $path = storage_path('app/private/content-archives');
        File::ensureDirectoryExists($path);

        return $path;
    }

    public function sourceId(): string
    {
        $path = $this->directory().DIRECTORY_SEPARATOR.'source-id';
        if (! is_file($path)) {
            file_put_contents($path, (string) Str::uuid(), LOCK_EX);
        }

        return trim((string) file_get_contents($path));
    }

    /** @return list<string> */
    public function tables(): array
    {
        $tables = config('content_archive.tables', []);
        $excluded = config('content_archive.excluded_tables', []);
        if (array_intersect($tables, $excluded)) {
            throw new RuntimeException('Danh sách bảng nội dung chứa bảng bị cấm.');
        }

        return array_values(array_filter($tables, fn (string $table) => Schema::hasTable($table)));
    }

    public function export(): string
    {
        $name = 'database-'.now('Asia/Ho_Chi_Minh')->format('Ymd-HisO').'.zip';
        $destination = $this->directory().DIRECTORY_SEPARATOR.$name;
        $stage = $this->directory().DIRECTORY_SEPARATOR.'stage-'.Str::random(24);
        File::ensureDirectoryExists($stage.DIRECTORY_SEPARATOR.'data');
        $media = [];
        $counts = [];
        $files = [];
        $sql = fopen($stage.DIRECTORY_SEPARATOR.'database.sql', 'wb');
        if ($sql === false) {
            throw new RuntimeException('Không thể tạo file SQL.');
        }

        try {
            DB::transaction(function () use ($stage, $sql, &$media, &$counts): void {
                fwrite($sql, "-- Content archive; restore only to the same schema version.\nSET FOREIGN_KEY_CHECKS=0;\n");
                foreach ($this->tables() as $table) {
                    $path = $stage.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.$table.'.ndjson';
                    $handle = fopen($path, 'wb');
                    if ($handle === false) {
                        throw new RuntimeException("Không thể tạo dữ liệu cho {$table}.");
                    }
                    try {
                        fwrite($sql, "\n-- {$table}\nDROP TABLE IF EXISTS `{$table}`;\n".$this->createTableSql($table).";\n");
                        $count = 0;
                        $key = $this->primaryKey($table);
                        foreach (DB::table($table)->orderBy($key)->cursor() as $row) {
                            $values = (array) $row;
                            fwrite($handle, json_encode($values, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n");
                            fwrite($sql, $this->insertSql($table, $values).";\n");
                            $this->collectMedia($values, $media);
                            $count++;
                        }
                        $counts[$table] = $count;
                    } finally {
                        fclose($handle);
                    }
                }
                fwrite($sql, "SET FOREIGN_KEY_CHECKS=1;\n");
            }, 1);
            fclose($sql);

            foreach ($media as $path => $source) {
                if (! is_file($source)) {
                    throw new RuntimeException("Media được tham chiếu nhưng không tồn tại: {$path}");
                }
                $target = $stage.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, 'media/'.$path);
                File::ensureDirectoryExists(dirname($target));
                if (! copy($source, $target)) {
                    throw new RuntimeException("Không thể sao chép media: {$path}");
                }
            }

            foreach (File::allFiles($stage) as $file) {
                $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($stage) + 1));
                $files[$relative] = ['sha256' => hash_file('sha256', $file->getPathname()), 'bytes' => $file->getSize()];
            }
            $manifest = [
                'format_version' => self::FORMAT_VERSION,
                'source_schema' => Schema::hasTable('products') ? 'hybrid' : 'legacy',
                'source_id' => $this->sourceId(),
                'exported_at_utc' => now('UTC')->toIso8601String(),
                'export_timezone' => 'Asia/Ho_Chi_Minh',
                'tables' => $counts,
                'files' => $files,
            ];
            file_put_contents($stage.DIRECTORY_SEPARATOR.'manifest.json', json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));

            $zip = new ZipArchive;
            if ($zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new RuntimeException('Không thể tạo ZIP.');
            }
            foreach (File::allFiles($stage) as $file) {
                $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($stage) + 1));
                $zip->addFile($file->getPathname(), $relative);
            }
            if (! $zip->close()) {
                throw new RuntimeException('Không thể hoàn tất ZIP.');
            }

            return $destination;
        } catch (\Throwable $error) {
            if (is_resource($sql)) {
                fclose($sql);
            }
            @unlink($destination);
            throw $error;
        } finally {
            File::deleteDirectory($stage);
        }
    }

    /** @return array<string, mixed> */
    public function preview(string $path): array
    {
        $zip = $this->openVerified($path);
        try {
            $manifest = json_decode((string) $zip->getFromName('manifest.json'), true, flags: JSON_THROW_ON_ERROR);
            $report = ['manifest' => $manifest, 'add' => 0, 'update' => 0, 'conflict' => 0, 'unchanged' => 0, 'conflicts' => [], 'missing_media' => []];
            $referencedMedia = [];
            foreach ($manifest['tables'] as $table => $expected) {
                if (! in_array($table, $this->tables(), true)) {
                    throw new RuntimeException("Bảng {$table} không được hỗ trợ ở schema này.");
                }
                $key = $this->primaryKey($table);
                $seen = 0;
                $this->eachRow($zip, $table, function (array $row) use ($table, $key, $manifest, &$report, &$seen, &$referencedMedia): void {
                    $seen++;
                    $this->collectMedia($row, $referencedMedia);
                    if (! array_key_exists($key, $row)) {
                        throw new RuntimeException("Thiếu khóa chính của {$table}.");
                    }
                    $existing = DB::table($table)->where($key, $row[$key])->first();
                    if (! $existing) {
                        $report['add']++;
                    } elseif ($manifest['source_id'] !== $this->sourceId()
                        || (isset($existing->updated_at, $row['updated_at']) && $existing->updated_at > $row['updated_at'])) {
                        $report['conflict']++;
                        if (count($report['conflicts']) < 100) {
                            $report['conflicts'][] = [
                                'table' => $table,
                                'id' => $row[$key],
                                'reason' => $manifest['source_id'] !== $this->sourceId() ? 'different_source_same_id' : 'destination_newer',
                            ];
                        }
                    } elseif ((array) $existing == $row) {
                        $report['unchanged']++;
                    } else {
                        $report['update']++;
                    }
                });
                if ($seen !== $expected) {
                    throw new RuntimeException("Số bản ghi của {$table} không khớp manifest.");
                }
            }

            foreach (array_keys($referencedMedia) as $mediaPath) {
                if (! isset($manifest['files']['media/'.$mediaPath])) {
                    $report['missing_media'][] = $mediaPath;
                }
            }

            return $report;
        } finally {
            $zip->close();
        }
    }

    /** @return array{added: int, updated: int, skipped: int} */
    public function import(string $path): array
    {
        $report = $this->preview($path);
        if ($report['missing_media'] !== []) {
            throw new RuntimeException('ZIP thiếu media được nội dung tham chiếu: '.implode(', ', array_slice($report['missing_media'], 0, 20)));
        }
        $zip = $this->openVerified($path);
        $manifest = $report['manifest'];
        $result = ['added' => 0, 'updated' => 0, 'skipped' => 0];
        try {
            $rewrites = $this->restoreMedia($zip, $manifest);
            DB::transaction(function () use ($zip, $manifest, $rewrites, &$result): void {
                foreach ($manifest['tables'] as $table => $_count) {
                    $key = $this->primaryKey($table);
                    $this->eachRow($zip, $table, function (array $row) use ($table, $key, $manifest, $rewrites, &$result): void {
                        $row = $this->rewriteMedia($row, $rewrites);
                        $existing = DB::table($table)->where($key, $row[$key])->first();
                        if ($existing && ($manifest['source_id'] !== $this->sourceId()
                            || (isset($existing->updated_at, $row['updated_at']) && $existing->updated_at > $row['updated_at']))) {
                            $result['skipped']++;

                            return;
                        }
                        if ($existing && (array) $existing == $row) {
                            $result['skipped']++;

                            return;
                        }
                        DB::table($table)->updateOrInsert([$key => $row[$key]], $row);
                        $result[$existing ? 'updated' : 'added']++;
                    });
                }
            });
            if ($manifest['source_schema'] === 'legacy' && Schema::hasTable('products')) {
                app(ProductBackfillService::class)->backfill();
            }

            return $result;
        } finally {
            $zip->close();
        }
    }

    private function openVerified(string $path): ZipArchive
    {
        $zip = new ZipArchive;
        if ($zip->open($path) !== true) {
            throw new RuntimeException('ZIP không hợp lệ.');
        }
        try {
            if ($zip->numFiles > 10000 || ($zip->statName('manifest.json')['size'] ?? PHP_INT_MAX) > 1_000_000) {
                throw new RuntimeException('ZIP có quá nhiều file hoặc manifest quá lớn.');
            }
            $raw = $zip->getFromName('manifest.json');
            $manifest = json_decode((string) $raw, true, flags: JSON_THROW_ON_ERROR);
            if (($manifest['format_version'] ?? null) !== self::FORMAT_VERSION
                || ! in_array(($manifest['source_schema'] ?? null), ['legacy', 'hybrid'], true)
                || ! is_array($manifest['tables'] ?? null)
                || ! is_array($manifest['files'] ?? null)
                || ! is_string($manifest['source_id'] ?? null)) {
                throw new RuntimeException('Phiên bản archive không được hỗ trợ.');
            }
            $total = 0;
            $seenNames = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                $name = $stat['name'];
                if (str_contains($name, '\\') || str_starts_with($name, '/')
                    || str_contains('/'.$name, '/../') || str_contains($name, ':')
                    || str_ends_with($name, '/') || isset($seenNames[$name])) {
                    throw new RuntimeException('ZIP chứa đường dẫn không an toàn.');
                }
                $seenNames[$name] = true;
                $total += $stat['size'];
                if ($total > config('content_archive.max_uncompressed_bytes')) {
                    throw new RuntimeException('ZIP vượt quá giới hạn giải nén.');
                }
                if ($name === 'manifest.json') {
                    continue;
                }
                $expected = $manifest['files'][$name] ?? null;
                if (! $expected || $expected['bytes'] !== $stat['size']) {
                    throw new RuntimeException("File {$name} không có trong manifest.");
                }
                $stream = $zip->getStream($name);
                if (! $stream) {
                    throw new RuntimeException("Không đọc được {$name}.");
                }
                $hash = hash_init('sha256');
                hash_update_stream($hash, $stream);
                fclose($stream);
                if (! hash_equals($expected['sha256'], hash_final($hash))) {
                    throw new RuntimeException("Checksum không khớp: {$name}");
                }
            }
            if (count($manifest['files']) !== $zip->numFiles - 1) {
                throw new RuntimeException('Danh sách file trong ZIP không khớp manifest.');
            }
            if (! isset($seenNames['database.sql']) || ! isset($seenNames['manifest.json'])) {
                throw new RuntimeException('ZIP thiếu SQL hoặc manifest.');
            }
            foreach ($manifest['tables'] as $table => $_count) {
                if (! preg_match('/^[a-z][a-z0-9_]*$/', (string) $table)
                    || ! isset($seenNames['data/'.$table.'.ndjson'])) {
                    throw new RuntimeException("ZIP thiếu dữ liệu bảng {$table}.");
                }
            }

            return $zip;
        } catch (\Throwable $error) {
            $zip->close();
            throw $error;
        }
    }

    private function eachRow(ZipArchive $zip, string $table, callable $callback): void
    {
        $stream = $zip->getStream("data/{$table}.ndjson");
        if (! $stream) {
            throw new RuntimeException("Thiếu dữ liệu bảng {$table}.");
        }
        try {
            while (($line = fgets($stream)) !== false) {
                $row = json_decode($line, true, flags: JSON_THROW_ON_ERROR);
                if (! is_array($row)) {
                    throw new RuntimeException("Dữ liệu bảng {$table} không hợp lệ.");
                }
                $callback($row);
            }
        } finally {
            fclose($stream);
        }
    }

    private function restoreMedia(ZipArchive $zip, array $manifest): array
    {
        $rewrites = [];
        foreach ($manifest['files'] as $name => $info) {
            if (! str_starts_with($name, 'media/')) {
                continue;
            }
            $original = substr($name, 6);
            $local = str_starts_with($original, 'assets/')
                ? public_path($original)
                : Storage::disk('public')->path(substr($original, 8));
            if (is_file($local) && hash_file('sha256', $local) === $info['sha256']) {
                continue;
            }
            $conflict = is_file($local) || str_starts_with($original, 'assets/');
            $target = $conflict
                ? 'imports/'.$info['sha256'].'/'.basename($original)
                : substr($original, 8);
            if (! Storage::disk('public')->exists($target)) {
                $stream = $zip->getStream($name);
                if (! $stream) {
                    throw new RuntimeException("Thiếu media {$original}.");
                }
                Storage::disk('public')->put($target, $stream);
                fclose($stream);
            }
            if ($conflict) {
                $rewrites[$original] = $target;
                if (str_starts_with($original, 'storage/')) {
                    $rewrites[substr($original, 8)] = $target;
                }
            }
        }

        return $rewrites;
    }

    private function rewriteMedia(mixed $value, array $rewrites): mixed
    {
        if (is_array($value)) {
            return array_map(fn ($item) => $this->rewriteMedia($item, $rewrites), $value);
        }
        if (! is_string($value)) {
            return $value;
        }
        if (isset($rewrites[$value])) {
            return $rewrites[$value];
        }
        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return json_encode($this->rewriteMedia($decoded, $rewrites), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return $value;
    }

    private function collectMedia(mixed $value, array &$media): void
    {
        if (is_array($value)) {
            foreach ($value as $item) {
                $this->collectMedia($item, $media);
            }

            return;
        }
        if (! is_string($value)) {
            return;
        }
        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            $this->collectMedia($decoded, $media);

            return;
        }
        if (is_string($decoded) && $decoded !== $value) {
            $this->collectMedia($decoded, $media);

            return;
        }
        $path = ltrim(str_replace('\\', '/', trim($value)), '/');
        if (preg_match('~^https?://~i', $path)) {
            $base = parse_url((string) config('app.url'));
            $url = parse_url($path);
            if (($base['host'] ?? null) === ($url['host'] ?? null)) {
                $path = ltrim((string) ($url['path'] ?? ''), '/');
            }
        }
        if (str_starts_with($path, 'assets/') || str_starts_with($path, 'storage/')) {
            if (str_contains('/'.$path, '/../')) {
                throw new RuntimeException('Đường dẫn media không an toàn.');
            }
            $media[$path] = str_starts_with($path, 'assets/')
                ? public_path($path)
                : Storage::disk('public')->path(substr($path, 8));
        } elseif (preg_match('~^(?!https?://|data:|//)[a-zA-Z0-9_-]+/[a-zA-Z0-9_./-]+\.(?:jpe?g|png|webp|gif|svg|mp4|webm|pdf)$~i', $path)) {
            if (str_contains('/'.$path, '/../')) {
                throw new RuntimeException('Đường dẫn media không an toàn.');
            }
            $media['storage/'.$path] = Storage::disk('public')->path($path);
        }
    }

    private function createTableSql(string $table): string
    {
        if (DB::getDriverName() === 'sqlite') {
            $row = DB::selectOne("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ?", [$table]);

            return $row->sql;
        }
        $row = (array) DB::selectOne('SHOW CREATE TABLE `'.$table.'`');

        return $row['Create Table'];
    }

    private function primaryKey(string $table): string
    {
        foreach (Schema::getIndexes($table) as $index) {
            if ($index['primary'] ?? false) {
                return $index['columns'][0];
            }
        }

        return Schema::getColumnListing($table)[0];
    }

    private function insertSql(string $table, array $row): string
    {
        $columns = implode(', ', array_map(fn ($name) => '`'.$name.'`', array_keys($row)));
        $values = implode(', ', array_map(function ($value) {
            if ($value === null) {
                return 'NULL';
            }

            return DB::connection()->getPdo()->quote((string) $value);
        }, array_values($row)));

        return "INSERT INTO `{$table}` ({$columns}) VALUES ({$values})";
    }
}
