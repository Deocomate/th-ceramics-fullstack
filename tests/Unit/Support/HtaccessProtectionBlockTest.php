<?php

function htaccessLines(string $file): array
{
    return array_map('trim', file(dirname(__DIR__, 3).'/'.$file, FILE_IGNORE_NEW_LINES));
}

function htaccessLine(array $lines, string $line): int
{
    $index = array_search($line, $lines, true);
    expect($index)->not->toBeFalse("Missing line: {$line}");

    return $index;
}

function protectionBlock(array $lines): array
{
    $begin = htaccessLine($lines, '# BEGIN content-protection');
    $end = htaccessLine($lines, '# END content-protection');

    return array_slice($lines, $begin, $end - $begin + 1);
}

test('the protection block is identical in the root and public htaccess files', function () {
    $root = protectionBlock(htaccessLines('.htaccess'));

    expect($root)->toBe(protectionBlock(htaccessLines('public/.htaccess')))
        ->and(count(array_filter($root, fn (string $line) => $line === 'RewriteRule ^ - [F,L]')))->toBe(3);
});

test('the protection block is the first rule after the rewrite engine is enabled', function (string $file) {
    $lines = array_values(array_filter(htaccessLines($file), fn (string $line) => $line !== ''));

    expect(htaccessLine($lines, '# BEGIN content-protection'))->toBe(htaccessLine($lines, 'RewriteEngine On') + 1);
})->with(['.htaccess', 'public/.htaccess']);

test('the root block runs before requests are handed to the public directory', function () {
    $lines = htaccessLines('.htaccess');

    expect(htaccessLine($lines, '# END content-protection'))
        ->toBeLessThan(htaccessLine($lines, 'RewriteRule ^(.*)$ /public/$1 [L,QSA]'));
});

test('the public block runs before the front controller rule', function () {
    $lines = htaccessLines('public/.htaccess');

    expect(htaccessLine($lines, '# END content-protection'))
        ->toBeLessThan(htaccessLine($lines, 'RewriteRule ^ index.php [L]'));
});

test('the referer allowlist does not accept look-alike hosts', function (string $referer, bool $allowed) {
    $conditions = array_values(array_filter(
        protectionBlock(htaccessLines('.htaccess')),
        fn (string $line) => str_contains($line, 'google'),
    ));

    expect($conditions)->toHaveCount(2)
        ->and($conditions[0])->toBe($conditions[1])
        ->and(preg_match('~^RewriteCond %\{HTTP_REFERER\} !(\S+) \[NC\]$~', $conditions[0], $match))->toBe(1)
        ->and(preg_match('~'.$match[1].'~i', $referer) === 1)->toBe($allowed);
})->with([
    ['https://www.google.com/', true],
    ['https://www.google.com.vn/search?q=gom', true],
    ['https://images.google.co.uk/', true],
    ['https://l.facebook.com/', true],
    ['https://chat.zalo.me/', true],
    ['https://google.example.com/', false],
    ['https://google.com.example.org/', false],
    ['https://notgoogle.com/', false],
    ['https://example.com/?from=google.com/', false],
]);

test('the same-host rule keeps its backreference and an empty referer is allowed', function () {
    $block = protectionBlock(htaccessLines('.htaccess'));

    expect($block)->toContain('RewriteCond %{HTTP_HOST}@@%{HTTP_REFERER} !^([^@]+)@@https?://\1(/|$) [NC]')
        ->toContain('RewriteCond %{HTTP_REFERER} !^$')
        ->toContain('RewriteCond %{REQUEST_URI} !^/(public/)?up$');
});
