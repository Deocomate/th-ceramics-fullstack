<?php

function builtAssetsRoot(): string
{
    return dirname(__DIR__, 3);
}

function builtAssetsManifest(): array
{
    $path = builtAssetsRoot().'/public/assets/dist/manifest.json';
    expect(is_file($path))->toBeTrue('Missing manifest. Run: composer run build:assets');

    return json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
}

function sourceFilesUnder(string $directory, string $extension): array
{
    $root = builtAssetsRoot().'/'.$directory;
    if (! is_dir($root)) {
        return [];
    }

    $files = [];
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)) as $file) {
        if ($file->getExtension() === $extension) {
            $files[] = $directory.'/'.str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
        }
    }
    sort($files);

    return $files;
}

test('every built asset matches the sources it was built from', function () {
    $assets = builtAssetsManifest()['assets'];

    expect($assets)->not->toBeEmpty();

    foreach ($assets as $name => $entry) {
        $output = builtAssetsRoot().'/public/assets/dist/'.$name;

        expect(is_file($output))->toBeTrue("Missing built asset {$name}. Run: composer run build:assets")
            ->and(hash_file('sha256', $output))->toBe($entry['sha256'], "{$name} differs from the manifest. Run: composer run build:assets")
            ->and($entry['sources'])->not->toBeEmpty();

        foreach ($entry['sources'] as $source => $hash) {
            $path = builtAssetsRoot().'/'.$source;

            expect(is_file($path))->toBeTrue("Source {$source} of {$name} is missing. Run: composer run build:assets")
                ->and(hash_file('sha256', $path))->toBe($hash, "{$source} changed after the last build. Run: composer run build:assets");
        }
    }
});

test('every source file is covered by a built asset', function () {
    $covered = [];
    foreach (builtAssetsManifest()['assets'] as $entry) {
        $covered = [...$covered, ...array_keys($entry['sources'])];
    }
    sort($covered);

    expect(array_values(array_unique($covered)))->toBe([
        ...sourceFilesUnder('resources/css', 'css'),
        ...sourceFilesUnder('resources/js', 'js'),
    ]);
});

test('the dist directory holds only the built assets listed in the manifest', function () {
    $files = array_map('basename', glob(builtAssetsRoot().'/public/assets/dist/*'));
    $expected = [...array_keys(builtAssetsManifest()['assets']), 'manifest.json'];
    sort($files);
    sort($expected);

    expect($files)->toBe($expected);
});

test('built assets carry no source map reference', function () {
    foreach (array_keys(builtAssetsManifest()['assets']) as $name) {
        expect(file_get_contents(builtAssetsRoot().'/public/assets/dist/'.$name))
            ->not->toContain('sourceMappingURL');
    }
});

test('no hand-written source is left in the public directory', function () {
    $scripts = array_filter(
        sourceFilesUnder('public/assets/js', 'js'),
        fn (string $file) => ! str_starts_with($file, 'public/assets/js/vendor/'),
    );

    expect(array_values($scripts))->toBe([])
        ->and(sourceFilesUnder('public/assets/css', 'css'))->toBe([]);
});

test('the flipbook build keeps the global the page calls', function () {
    expect(file_get_contents(builtAssetsRoot().'/public/assets/dist/catalog-flipbook.min.js'))
        ->toContain('initCatalogFlipbook');
});
