<?php

/**
 * Extract INSERT data from a mysqldump SQL file into PHP array files.
 *
 * Usage: php scripts/extract-sql-to-seeder-data.php database/sql/demo_thceramics_20260615_165521.sql database/seeders/data
 */

declare(strict_types=1);

$skipTables = [
    'migrations',
    'sessions',
    'jobs',
    'failed_jobs',
    'cache',
    'cache_locks',
    'password_reset_tokens',
    'personal_access_tokens',
];

if ($argc < 3) {
    fwrite(STDERR, "Usage: php {$argv[0]} <sql-file> <output-dir>\n");
    exit(1);
}

$sqlFile = $argv[1];
$outputDir = rtrim($argv[2], '/\\');

if (! is_file($sqlFile)) {
    fwrite(STDERR, "SQL file not found: {$sqlFile}\n");
    exit(1);
}

if (! is_dir($outputDir) && ! mkdir($outputDir, 0755, true)) {
    fwrite(STDERR, "Cannot create output dir: {$outputDir}\n");
    exit(1);
}

$sql = file_get_contents($sqlFile);
$columns = extractTableColumns($sql);
$inserts = extractInserts($sql);

$written = 0;
foreach ($inserts as $table => $rows) {
    if (in_array($table, $skipTables, true)) {
        continue;
    }

    if (! isset($columns[$table])) {
        fwrite(STDERR, "Warning: no CREATE TABLE for `{$table}`, skipping\n");
        continue;
    }

    $mapped = [];
    $cols = $columns[$table];

    foreach ($rows as $values) {
        if (count($values) !== count($cols)) {
            fwrite(STDERR, "Warning: column count mismatch for `{$table}` (".count($cols).' vs '.count($values).")\n");
            continue;
        }
        $row = array_combine($cols, $values);
        $mapped[] = decodeJsonFields($row);
    }

    $php = exportPhpArray($mapped);
    $outFile = $outputDir.'/'.$table.'.php';
    file_put_contents($outFile, "<?php\n\nreturn {$php};\n");
    $written++;
    echo "Wrote {$outFile} (".count($mapped)." rows)\n";
}

echo "Done: {$written} files written to {$outputDir}\n";

function extractTableColumns(string $sql): array
{
    $result = [];
    preg_match_all(
        '/CREATE TABLE `([^`]+)` \((.*?)\) ENGINE=/s',
        $sql,
        $matches,
        PREG_SET_ORDER
    );

    foreach ($matches as $match) {
        $table = $match[1];
        $body = $match[2];
        $cols = [];
        foreach (preg_split('/\r?\n/', $body) as $line) {
            if (preg_match('/^\s*`([^`]+)`\s+/', $line, $colMatch)) {
                $cols[] = $colMatch[1];
            }
        }
        $result[$table] = $cols;
    }

    return $result;
}

function extractInserts(string $sql): array
{
    $result = [];
    preg_match_all(
        '/INSERT INTO `([^`]+)` VALUES\s*(.*?);/s',
        $sql,
        $matches,
        PREG_SET_ORDER
    );

    foreach ($matches as $match) {
        $table = $match[1];
        $valuesBlock = trim($match[2]);
        $rows = parseInsertRows($valuesBlock);
        $result[$table] = $rows;
    }

    return $result;
}

/** @return list<list<mixed>> */
function parseInsertRows(string $block): array
{
    $rows = [];
    $i = 0;
    $len = strlen($block);

    while ($i < $len) {
        while ($i < $len && ($block[$i] === ',' || $block[$i] === "\n" || $block[$i] === "\r" || $block[$i] === ' ')) {
            $i++;
        }
        if ($i >= $len) {
            break;
        }
        if ($block[$i] !== '(') {
            $i++;
            continue;
        }
        [$row, $i] = parseRow($block, $i);
        $rows[] = $row;
    }

    return $rows;
}

/** @return array{0: list<mixed>, 1: int} */
function parseRow(string $block, int $start): array
{
    $values = [];
    $i = $start + 1; // skip '('
    $len = strlen($block);

    while ($i < $len) {
        while ($i < $len && ($block[$i] === ' ' || $block[$i] === "\n" || $block[$i] === "\r")) {
            $i++;
        }

        if ($i >= $len) {
            break;
        }

        if ($block[$i] === ')') {
            return [$values, $i + 1];
        }

        if ($block[$i] === ',') {
            $i++;
            continue;
        }

        [$value, $i] = parseValue($block, $i);
        $values[] = $value;
    }

    return [$values, $i];
}

/** @return array{0: mixed, 1: int} */
function parseValue(string $block, int $start): array
{
    $i = $start;
    $len = strlen($block);

    if (str_starts_with(substr($block, $i), 'NULL')) {
        return [null, $i + 4];
    }

    if ($block[$i] === "'") {
        $str = '';
        $i++;
        while ($i < $len) {
            $ch = $block[$i];
            if ($ch === '\\' && $i + 1 < $len) {
                $next = $block[$i + 1];
                $str .= match ($next) {
                    'n' => "\n",
                    'r' => "\r",
                    't' => "\t",
                    '\\' => '\\',
                    "'" => "'",
                    '"' => '"',
                    default => $next,
                };
                $i += 2;
                continue;
            }
            if ($ch === "'") {
                return [$str, $i + 1];
            }
            $str .= $ch;
            $i++;
        }
    }

    // numeric
    $num = '';
    while ($i < $len && preg_match('/[0-9.\-]/', $block[$i])) {
        $num .= $block[$i];
        $i++;
    }

    if ($num !== '' && $num !== '-') {
        return [str_contains($num, '.') ? (float) $num : (int) $num, $i];
    }

    return [null, $i + 1];
}

/** @param  array<string, mixed>  $row */
function decodeJsonFields(array $row): array
{
    foreach ($row as $key => $value) {
        if (! is_string($value)) {
            continue;
        }
        $trimmed = ltrim($value);
        if ($trimmed === '' || ($trimmed[0] !== '[' && $trimmed[0] !== '{')) {
            continue;
        }
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $row[$key] = $decoded;
        }
    }

    return $row;
}

function exportPhpArray(array $data): string
{
    return var_export($data, true);
}
