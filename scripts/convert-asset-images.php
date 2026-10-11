<?php

/**
 * Convert the static images under public/assets/images that are 1 MB or larger, or exceed
 * their preset dimensions, to WebP, and point the tracked source files at the new names.
 *
 *   php scripts/convert-asset-images.php            list what would change
 *   php scripts/convert-asset-images.php --apply    convert, rewrite references, delete originals
 *
 * The originals stay in git history. The printed map is what the data migration embeds to
 * rewrite the same references stored in the database.
 */

use App\Domains\Media\Infrastructure\ImageOptimizerService;
use Illuminate\Contracts\Console\Kernel;

require dirname(__DIR__).'/vendor/autoload.php';
$app = require dirname(__DIR__).'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

const REFERENCE_ROOTS = ['resources', 'database', 'tests', 'app', 'public/assets/css', 'public/assets/js'];

$apply = in_array('--apply', $argv, true);
$root = dirname(__DIR__);
$optimizer = $app->make(ImageOptimizerService::class);
ini_set('memory_limit', '1024M');

// 1. Find the images that break the limits.
$candidates = [];
$duplicates = [];
$planned = [];
$skipped = [];
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root.'/public/assets/images', FilesystemIterator::SKIP_DOTS));
foreach ($files as $file) {
    $extension = strtolower($file->getExtension());
    if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
        continue;
    }

    $path = str_replace('\\', '/', substr($file->getPathname(), strlen($root.'/public/')));
    $preset = $optimizer->presetForFile($path);
    if (! $optimizer->exceedsLimits($file->getPathname(), $preset)) {
        continue;
    }

    $target = $extension === 'webp' ? $path : substr($path, 0, -strlen($extension)).'webp';
    if ($target !== $path && is_file($root.'/public/'.$target)) {
        $skipped[$path] = 'target already exists: '.$target;

        continue;
    }
    if (isset($planned[$target])) {
        // The same picture saved under two extensions shares one WebP file.
        if (md5_file($file->getPathname()) === md5_file($root.'/public/'.$planned[$target])) {
            $duplicates[$path] = $target;
        } else {
            $skipped[$path] = 'target already taken by '.$planned[$target];
        }

        continue;
    }

    $planned[$target] = $path;
    $candidates[$path] = ['target' => $target, 'preset' => $preset, 'bytes' => $file->getSize()];
}
ksort($candidates);

// 2. Convert.
$map = [];
$bytesBefore = 0;
$bytesAfter = 0;
foreach ($candidates as $path => $candidate) {
    $source = $root.'/public/'.$path;
    $target = $root.'/public/'.$candidate['target'];

    if ($apply) {
        try {
            $encoded = $optimizer->encodeWebpUnderLimit($source, $candidate['preset']);
        } catch (Throwable $e) {
            $skipped[$path] = $e->getMessage();

            continue;
        }

        file_put_contents($target, $encoded);
        if ($target !== $source) {
            unlink($source);
        }
        $bytesAfter += strlen($encoded);
    }

    $bytesBefore += $candidate['bytes'];
    if ($path !== $candidate['target']) {
        $map[$path] = $candidate['target'];
    }
    printf("%s  %s (%d KB)\n", $apply ? 'converted' : 'would convert', $path, $candidate['bytes'] / 1024);
}

foreach ($duplicates as $path => $target) {
    if (! isset($map[$planned[$target]])) {
        continue;
    }

    $map[$path] = $target;
    if ($apply) {
        unlink($root.'/public/'.$path);
    }
    printf('%s  %s (same file as %s)
', $apply ? 'removed' : 'would remove', $path, $planned[$target]);
}
ksort($map);

// 3. Rewrite references in tracked source files. Paths appear relative to public/
// ("assets/images/a.png") or relative to a stylesheet ("../images/a.png").
exec('git -C '.escapeshellarg($root).' ls-files -- '.implode(' ', REFERENCE_ROOTS), $tracked);
$patterns = [];
foreach ($map as $old => $new) {
    $relative = preg_quote(substr($old, strlen('assets/')), '~');
    $patterns[] = [
        'needle' => basename($old),
        'pattern' => '~(?<!storage/assets/)(?:(?<=assets/)|(?<=\.\./)|(?<![A-Za-z0-9_\-./]))'.$relative.'(?![A-Za-z0-9]|\.[A-Za-z0-9])~',
        'new' => substr($new, strlen('assets/')),
    ];
}

$changedFiles = [];
$leftovers = [];
foreach ($tracked as $trackedFile) {
    $absolute = $root.'/'.$trackedFile;
    if (! is_file($absolute) || preg_match('~\.(?:jpe?g|png|webp|gif|svg|ico|woff2?|ttf|eot|mp4|pdf)$~i', $trackedFile)) {
        continue;
    }

    $original = file_get_contents($absolute);
    $contents = $original;
    foreach ($patterns as $pattern) {
        if (! str_contains($contents, $pattern['needle'])) {
            continue;
        }

        $contents = preg_replace($pattern['pattern'], $pattern['new'], $contents);
        // A name that still stands on its own may be assembled at runtime; one inside another directory is a different file.
        if (preg_match('~(?<![A-Za-z0-9_\-./])'.preg_quote($pattern['needle'], '~').'~', $contents)) {
            $leftovers[$trackedFile][] = $pattern['needle'];
        }
    }

    if ($contents !== $original) {
        $changedFiles[] = $trackedFile;
        if ($apply) {
            file_put_contents($absolute, $contents);
        }
    }
}

echo "\n", count($changedFiles), $apply ? " source files rewritten:\n" : " source files would be rewritten:\n";
foreach ($changedFiles as $changedFile) {
    echo '  ', $changedFile, "\n";
}

if ($leftovers !== []) {
    echo "\nMentions of a converted file name that were not rewritten (review by hand):\n";
    foreach ($leftovers as $leftoverFile => $names) {
        echo '  ', $leftoverFile, ': ', implode(', ', array_unique($names)), "\n";
    }
}

if ($skipped !== []) {
    echo "\nSkipped:\n";
    foreach ($skipped as $path => $reason) {
        echo '  ', $path, ': ', $reason, "\n";
    }
}

printf("\n%d images, %.1f MB", count($candidates) - count(array_intersect_key($skipped, $candidates)), $bytesBefore / 1048576);
if ($apply) {
    printf(' -> %.1f MB', $bytesAfter / 1048576);
}
echo "\n\nReference map for the data migration:\n";
var_export($map);
echo "\n";
