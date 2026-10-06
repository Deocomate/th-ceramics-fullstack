<?php

namespace App\Domains\Archive;

use App\Domains\Archive\Adapters\LegacyV1ArchiveAdapter;
use App\Domains\Archive\Application\Ports\CatalogArchivePort;
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

    private array $foreignKeys = [];

    private array $tableColumns = [];

    private ?string $cachedSourceId = null;

    public function directory(): string
    {
        $path = storage_path('app/private/content-archives');
        File::ensureDirectoryExists($path);

        return $path;
    }

    public function sourceId(): string
    {
        if ($this->cachedSourceId !== null) {
            return $this->cachedSourceId;
        }
        $path = $this->directory().DIRECTORY_SEPARATOR.'source-id';
        $handle = fopen($path, 'c+');
        if ($handle === false || ! flock($handle, LOCK_EX)) {
            throw new RuntimeException('Không thể khóa định danh nguồn của bản xuất.');
        }
        try {
            rewind($handle);
            $id = trim((string) stream_get_contents($handle));
            if ($id === '') {
                $id = (string) Str::uuid();
                rewind($handle);
                ftruncate($handle, 0);
                fwrite($handle, $id);
                fflush($handle);
            }

            return $this->cachedSourceId = $id;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
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
                'source_schema' => $this->sourceSchema(),
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
            $planned = [];
            foreach ($manifest['tables'] as $table => $expected) {
                if (($manifest['source_schema'] ?? '') === 'legacy' && LegacyV1ArchiveAdapter::isLegacyCatalogTable($table)) {
                    $seen = 0;
                    $this->eachRow($zip, $table, function (array $row) use (&$seen, &$referencedMedia): void {
                        $seen++;
                        $this->collectMedia($row, $referencedMedia);
                    });
                    if ($seen !== $expected) {
                        throw new RuntimeException("Số bản ghi của {$table} không khớp manifest.");
                    }
                    $report['add'] += $seen;

                    continue;
                }

                if (! in_array($table, $this->tables(), true)) {
                    throw new RuntimeException("Bảng {$table} không được hỗ trợ ở schema này.");
                }
                $key = $this->primaryKey($table);
                $seen = 0;
                $this->eachRow($zip, $table, function (array $row) use ($table, $key, $manifest, &$report, &$seen, &$referencedMedia, &$planned): void {
                    $seen++;
                    $this->collectMedia($row, $referencedMedia);
                    if (! array_key_exists($key, $row)) {
                        throw new RuntimeException("Thiếu khóa chính của {$table}.");
                    }
                    [$status, $reason] = $this->classifyRow($table, $key, $row, $manifest, $planned);
                    $report[$status]++;
                    if ($status === 'conflict') {
                        if (count($report['conflicts']) < 100) {
                            $report['conflicts'][] = [
                                'table' => $table,
                                'id' => $row[$key],
                                'reason' => $reason,
                            ];
                        }
                    } else {
                        $planned[$table][(string) $row[$key]] = true;
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
                $planned = [];
                foreach ($manifest['tables'] as $table => $_count) {
                    if (($manifest['source_schema'] ?? '') === 'legacy' && LegacyV1ArchiveAdapter::isLegacyCatalogTable($table)) {
                        app(LegacyV1ArchiveAdapter::class)->importTable(
                            $zip,
                            $table,
                            $manifest,
                            $rewrites,
                            $result,
                            fn (mixed $val, array $rw) => $this->rewriteMedia($val, $rw)
                        );

                        continue;
                    }

                    $key = $this->primaryKey($table);
                    $this->eachRow($zip, $table, function (array $row) use ($table, $key, $manifest, $rewrites, &$result, &$planned): void {
                        $row = $this->rewriteMedia($row, $rewrites);
                        [$status, , $targetId, $row] = $this->classifyRow($table, $key, $row, $manifest, $planned);
                        if ($status === 'conflict') {
                            $result['skipped']++;

                            return;
                        }
                        $sourceId = $row[$key];
                        $row[$key] = $targetId;
                        if ($status === 'unchanged') {
                            $result['skipped']++;
                        } else {
                            DB::table($table)->updateOrInsert([$key => $targetId], $row);
                            $result[$status === 'add' ? 'added' : 'updated']++;
                        }
                        DB::table('content_archive_record_maps')->updateOrInsert([
                            'source_id' => $manifest['source_id'],
                            'table_name' => $table,
                            'source_record_id' => $sourceId,
                        ], ['target_record_id' => $targetId, 'updated_at' => now(), 'created_at' => now()]);
                        $planned[$table][(string) $sourceId] = true;
                    });
                }
                app(CatalogArchivePort::class)->reconcileSequences();
            });

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
            if ($zip->numFiles > config('content_archive.max_files')
                || ($zip->statName('manifest.json')['size'] ?? PHP_INT_MAX) > 20_000_000) {
                throw new RuntimeException('ZIP có quá nhiều file hoặc manifest quá lớn.');
            }
            $raw = $zip->getFromName('manifest.json');
            $manifest = json_decode((string) $raw, true, flags: JSON_THROW_ON_ERROR);
            if (($manifest['format_version'] ?? null) !== self::FORMAT_VERSION
                || ! in_array(($manifest['source_schema'] ?? null), ['legacy', 'hybrid', 'canonical'], true)
                || ! is_array($manifest['tables'] ?? null)
                || ! is_array($manifest['files'] ?? null)
                || ! is_string($manifest['source_id'] ?? null)
                || ! Str::isUuid($manifest['source_id'])) {
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

    /** @return array{string, ?string, int, array} */
    private function classifyRow(string $table, string $key, array $row, array $manifest, array $planned): array
    {
        $sourceRecordId = (int) $row[$key];
        $sourceId = $manifest['source_id'];
        $mapping = DB::table('content_archive_record_maps')
            ->where('source_id', $sourceId)->where('table_name', $table)
            ->where('source_record_id', $sourceRecordId)->first();
        $targetId = (int) ($mapping->target_record_id ?? $sourceRecordId);

        foreach ($this->foreignKeys[$table] ??= Schema::getForeignKeys($table) as $foreignKey) {
            if (count($foreignKey['columns']) !== 1 || count($foreignKey['foreign_columns']) !== 1
                || ! array_key_exists($foreignKey['foreign_table'], $manifest['tables'])) {
                continue;
            }
            $column = $foreignKey['columns'][0];
            $parentTable = $foreignKey['foreign_table'];
            if (($row[$column] ?? null) === null) {
                continue;
            }
            $parentSourceId = (int) $row[$column];
            $parentMapping = DB::table('content_archive_record_maps')
                ->where('source_id', $sourceId)->where('table_name', $parentTable)
                ->where('source_record_id', $parentSourceId)->first();
            if ($parentMapping) {
                $row[$column] = $parentMapping->target_record_id;
            } elseif ($sourceId !== $this->sourceId()
                && ! isset($planned[$parentTable][(string) $parentSourceId])) {
                return ['conflict', 'unmapped_parent', $targetId, $row];
            }
        }
        if ($sourceId !== $this->sourceId()
            && in_array($table, ['product_legacy_ids', 'variant_legacy_ids'], true)
            && isset($row['source_table'], $row['source_id'])
            && array_key_exists($row['source_table'], $manifest['tables'])) {
            $legacyMapping = DB::table('content_archive_record_maps')
                ->where('source_id', $sourceId)->where('table_name', $row['source_table'])
                ->where('source_record_id', $row['source_id'])->exists();
            if (! $legacyMapping && ! isset($planned[$row['source_table']][(string) $row['source_id']])) {
                return ['conflict', 'unmapped_legacy_record', $targetId, $row];
            }
        }

        $existing = DB::table($table)->where($key, $targetId)->first();
        if ($existing && ! $mapping && $sourceId !== $this->sourceId()) {
            return ['conflict', 'different_source_same_id', $targetId, $row];
        }
        $columns = $this->tableColumns[$table] ??= Schema::getColumnListing($table);
        foreach (['slug', 'sku', 'code'] as $naturalKey) {
            if (! in_array($naturalKey, $columns, true) || empty($row[$naturalKey])) {
                continue;
            }
            if (DB::table($table)->where($naturalKey, $row[$naturalKey])
                ->where($key, '!=', $targetId)->exists()) {
                return ['conflict', 'duplicate_'.$naturalKey, $targetId, $row];
            }
        }
        if (! $existing) {
            return ['add', null, $targetId, $row];
        }
        if (isset($existing->updated_at, $row['updated_at']) && $existing->updated_at > $row['updated_at']) {
            return ['conflict', 'destination_newer', $targetId, $row];
        }
        $row[$key] = $targetId;

        return (array) $existing == $row
            ? ['unchanged', null, $targetId, $row]
            : ['update', null, $targetId, $row];
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
        if ($rewrites === []) {
            return $value;
        }
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
            $rewritten = $this->rewriteMedia($decoded, $rewrites);

            return $rewritten === $decoded
                ? $value
                : json_encode($rewritten, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        if (str_contains($value, '<')) {
            return preg_replace_callback('~((?:src|href|poster)\s*=\s*["\'])([^"\']+)(["\'])~i',
                fn ($match) => $match[1].$this->rewriteEmbeddedMedia($match[2], $rewrites).$match[3], $value);
        }

        return $value;
    }

    private function rewriteEmbeddedMedia(string $reference, array $rewrites): string
    {
        $parsed = parse_url($reference);
        $path = ltrim((string) ($parsed['path'] ?? $reference), '/');
        $target = $rewrites[$path] ?? $rewrites[$reference] ?? null;
        if ($target === null) {
            return $reference;
        }
        $suffix = (isset($parsed['query']) ? '?'.$parsed['query'] : '')
            .(isset($parsed['fragment']) ? '#'.$parsed['fragment'] : '');
        $newPath = '/storage/'.$target.$suffix;
        if (isset($parsed['host'])) {
            $base = parse_url((string) config('app.url'));
            if ($parsed['host'] !== ($base['host'] ?? null)) {
                return $reference;
            }

            return ($parsed['scheme'] ?? 'https').'://'.$parsed['host'].$newPath;
        }

        return $newPath;
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
        if (str_contains($value, '<')
            && preg_match_all('~(?:src|href|poster)\s*=\s*["\']([^"\']+)["\']~i', $value, $matches)) {
            foreach ($matches[1] as $reference) {
                $this->collectMedia(html_entity_decode($reference, ENT_QUOTES | ENT_HTML5), $media);
            }

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
            if (($base['host'] ?? null) !== ($url['host'] ?? null)) {
                return;
            }
            $path = ltrim((string) ($url['path'] ?? ''), '/');
        }
        $path = (string) (parse_url($path, PHP_URL_PATH) ?: $path);
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

    private function sourceSchema(): string
    {
        if (! Schema::hasTable('products')) {
            return 'legacy';
        }

        foreach (config('content_archive.legacy_product_tables', []) as $table) {
            if (Schema::hasTable($table)) {
                return 'hybrid';
            }
        }

        return 'canonical';
    }
}
