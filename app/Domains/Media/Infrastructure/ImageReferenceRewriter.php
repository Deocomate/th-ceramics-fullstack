<?php

namespace App\Domains\Media\Infrastructure;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Rewrites image paths stored anywhere in the application's own tables.
 *
 * A reference path is either a public asset (`assets/...`, served from `public/`)
 * or a path on the public disk (everything else, served from `/storage/...`).
 */
class ImageReferenceRewriter
{
    public const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    /**
     * Framework and log tables that never hold content image references.
     */
    private const SKIPPED_TABLES = [
        'migrations',
        'jobs',
        'failed_jobs',
        'job_batches',
        'sessions',
        'cache',
        'cache_locks',
        'password_reset_tokens',
        'protection_violations',
    ];

    private const PATH_CHARACTERS = 'A-Za-z0-9_\-.%\~\x80-\xff';

    /**
     * Tables of the current connection's schema that can be rewritten.
     *
     * @return array{scanned: array<string, array{key: string, columns: list<string>}>, skipped: list<string>}
     */
    public function tables(): array
    {
        $scanned = [];
        $skipped = [];

        // Without the schema filter MySQL and MariaDB list every database the account can see.
        foreach (Schema::getTables(Schema::getCurrentSchemaName()) as $table) {
            $name = $table['name'];
            if (in_array($name, self::SKIPPED_TABLES, true)) {
                continue;
            }

            $columns = [];
            foreach (Schema::getColumns($name) as $column) {
                if (preg_match('/char|text|json|clob/i', (string) $column['type_name'])) {
                    $columns[] = $column['name'];
                }
            }
            if ($columns === []) {
                continue;
            }

            $key = null;
            foreach (Schema::getIndexes($name) as $index) {
                if ($index['primary'] && count($index['columns']) === 1) {
                    $key = $index['columns'][0];
                }
            }
            if ($key === null) {
                $skipped[] = $name;

                continue;
            }

            $scanned[$name] = ['key' => $key, 'columns' => array_values(array_diff($columns, [$key]))];
        }

        ksort($scanned);
        sort($skipped);

        return ['scanned' => $scanned, 'skipped' => $skipped];
    }

    /**
     * Replace every occurrence of the mapped paths, in plain and JSON-escaped form.
     *
     * @param  array<string, string>  $map  old reference path => new reference path
     * @param  bool  $apply  false only counts the rows that would change
     * @return array{changes: array<string, int>, skipped: list<string>}
     */
    public function rewrite(array $map, bool $apply = true): array
    {
        $tables = $this->tables();
        $changes = [];
        if ($map === []) {
            return ['changes' => $changes, 'skipped' => $tables['skipped']];
        }

        $replacements = $this->compile($map);
        $extensions = array_values(array_unique(array_map(
            static fn (string $path): string => pathinfo($path, PATHINFO_EXTENSION),
            array_keys($map),
        )));

        $run = function () use ($tables, $replacements, $extensions, $apply, &$changes): void {
            foreach ($tables['scanned'] as $table => $definition) {
                foreach ($this->rows($table, $definition, $extensions, lock: $apply) as $row) {
                    foreach ($definition['columns'] as $column) {
                        $value = $row->{$column};
                        if (! is_string($value) || $value === '') {
                            continue;
                        }

                        $rewritten = $this->replace($value, $replacements);
                        if ($rewritten === $value) {
                            continue;
                        }

                        if ($apply) {
                            DB::table($table)->where($definition['key'], $row->{$definition['key']})->update([$column => $rewritten]);
                        }
                        $changes[$table.'.'.$column] = ($changes[$table.'.'.$column] ?? 0) + 1;
                    }
                }
            }
        };

        $apply ? DB::transaction($run) : $run();
        ksort($changes);

        return ['changes' => $changes, 'skipped' => $tables['skipped']];
    }

    /**
     * Every distinct image reference stored in the scanned tables, with JSON escaping removed.
     *
     * @return list<string>
     */
    public function references(): array
    {
        $references = [];
        $tables = $this->tables();

        foreach ($tables['scanned'] as $table => $definition) {
            foreach ($this->rows($table, $definition, self::IMAGE_EXTENSIONS) as $row) {
                foreach ($definition['columns'] as $column) {
                    if (is_string($row->{$column})) {
                        $this->collect($row->{$column}, $references);
                    }
                }
            }
        }

        ksort($references);

        return array_map('strval', array_keys($references));
    }

    /**
     * @param  array{key: string, columns: list<string>}  $definition
     * @param  list<string>  $extensions
     * @return iterable<object>
     */
    private function rows(string $table, array $definition, array $extensions, bool $lock = false): iterable
    {
        // LIKE is case-sensitive on JSON and binary-collated columns, so both spellings are searched.
        $extensions = array_unique(array_merge(
            $extensions,
            array_map('strtolower', $extensions),
            array_map('strtoupper', $extensions),
        ));

        return DB::table($table)
            ->select([$definition['key'], ...$definition['columns']])
            ->where(function ($query) use ($definition, $extensions): void {
                foreach ($definition['columns'] as $column) {
                    foreach ($extensions as $extension) {
                        $query->orWhere($column, 'like', '%.'.$extension.'%');
                    }
                }
            })
            // Keeps a concurrent admin save from being overwritten with the value read here.
            ->when($lock, fn ($query) => $query->lockForUpdate())
            ->lazyById(500, $definition['key']);
    }

    /**
     * @param  array<string, string>  $map
     * @return list<array{needle: string, pattern: string, new: string, old: string}>
     */
    private function compile(array $map): array
    {
        $replacements = [];

        foreach ($map as $old => $new) {
            $forms = [[$old, $new]];
            // json_encode() stores non-ASCII file names as \uXXXX sequences.
            if (preg_match('/[\x80-\xff]/', $old)) {
                $forms[] = [
                    trim((string) json_encode($old, JSON_UNESCAPED_SLASHES), '"'),
                    trim((string) json_encode($new, JSON_UNESCAPED_SLASHES), '"'),
                ];
            }

            foreach ($forms as [$from, $to]) {
                $segments = array_map(static fn (string $segment): string => preg_quote($segment, '~'), explode('/', $from));
                $replacements[] = [
                    'needle' => basename($from),
                    // Any number of backslashes may precede a slash, depending on how often the value was JSON-encoded.
                    'pattern' => '~(?<!['.self::PATH_CHARACTERS.'])'.implode('\\\\*/', $segments).'(?![A-Za-z0-9_\-/\x80-\xff]|\.[A-Za-z0-9])~',
                    'new' => $to,
                    'old' => $old,
                ];
            }
        }

        return $replacements;
    }

    /**
     * @param  list<array{needle: string, pattern: string, new: string, old: string}>  $replacements
     */
    private function replace(string $value, array $replacements): string
    {
        foreach ($replacements as $replacement) {
            if (! str_contains($value, $replacement['needle'])) {
                continue;
            }

            $result = preg_replace_callback(
                $replacement['pattern'],
                function (array $match) use ($value, $replacement): string {
                    [$text, $offset] = $match[0];
                    if (! $this->startsReference($replacement['old'], substr($value, max(0, $offset - 64), min(64, $offset)))) {
                        return $text;
                    }

                    $separator = preg_match('~\\\\*/~', $text, $found) ? $found[0] : '/';

                    return str_replace('/', $separator, $replacement['new']);
                },
                $value,
                flags: PREG_OFFSET_CAPTURE,
            );

            $value = $result ?? $value;
        }

        return $value;
    }

    /**
     * Whether a matched path is the whole reference rather than the tail of a longer path.
     */
    private function startsReference(string $path, string $before): bool
    {
        $before = (string) preg_replace('~\\\\+/~', '/', $before);
        if (! str_ends_with($before, '/') || str_starts_with($path, 'storage/')) {
            return true;
        }

        preg_match('~(['.self::PATH_CHARACTERS.']*)/$~', $before, $segment);
        $parent = $segment[1] ?? '';

        return str_starts_with($path, 'assets/')
            ? $parent !== 'storage'
            : $parent === 'storage' || $parent === '';
    }

    /**
     * @param  array<string, true>  $references
     */
    private function collect(string $value, array &$references, int $depth = 0): void
    {
        $decoded = $depth < 3 ? json_decode($value, true) : null;
        if (is_array($decoded)) {
            array_walk_recursive($decoded, function (mixed $leaf) use (&$references, $depth): void {
                if (is_string($leaf)) {
                    $this->collect($leaf, $references, $depth + 1);
                }
            });

            return;
        }
        if (is_string($decoded)) {
            $this->collect($decoded, $references, $depth + 1);

            return;
        }

        $extensions = implode('|', self::IMAGE_EXTENSIONS);
        $value = trim($value);

        if (preg_match('~^[^<>"\r\n]+\.(?:'.$extensions.')$~i', $value)) {
            $references[$value] = true;

            return;
        }

        preg_match_all(
            '~(?<!['.self::PATH_CHARACTERS.'/])(?:https?://[^/\s"\'<>]+)?/?(?:storage|assets)/[^\s"\'<>()\\\\]+\.(?:'.$extensions.')(?![A-Za-z0-9])~i',
            $value,
            $matches,
        );
        foreach ($matches[0] as $match) {
            $references[$match] = true;
        }
    }
}
