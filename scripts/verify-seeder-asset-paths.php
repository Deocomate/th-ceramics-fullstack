<?php

declare(strict_types=1);

/**
 * Verify image paths referenced in seeder data files exist on disk.
 *
 * Usage: php scripts/verify-seeder-asset-paths.php
 */

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$dataDir = __DIR__.'/../database/seeders/data';
$missing = [];

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($dataDir)
);

foreach ($iterator as $file) {
    if ($file->getExtension() !== 'php') {
        continue;
    }

    /** @var array<int, array<string, mixed>> $rows */
    $rows = require $file->getPathname();
    collectPaths($rows, $missing);
}

if ($missing === []) {
    echo "OK: all seeder asset paths exist\n";
    exit(0);
}

echo 'MISSING ('.count($missing)."):\n";
foreach (array_slice($missing, 0, 50) as $path) {
    echo " - {$path}\n";
}
if (count($missing) > 50) {
    echo ' ... and '.(count($missing) - 50)." more\n";
}
exit(1);

/** @param  array<int|string, mixed>  $value */
function collectPaths(mixed $value, array &$missing): void
{
    if (is_string($value)) {
        if (str_starts_with($value, 'assets/images/')) {
            $full = public_path($value);
            if (! is_file($full)) {
                $missing[] = $value;
            }
        } elseif (str_starts_with($value, 'seeders/')) {
            $full = storage_path('app/public/'.$value);
            if (! is_file($full)) {
                $missing[] = $value;
            }
        }

        return;
    }

    if (! is_array($value)) {
        return;
    }

    foreach ($value as $item) {
        collectPaths($item, $missing);
    }
}
