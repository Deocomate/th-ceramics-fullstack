<?php

declare(strict_types=1);

/**
 * Verify the content protection layers of a deployed site with real requests.
 *
 * Usage: php scripts/verify-content-protection.php <base-url>
 *            [--detail-url=] [--video-page=] [--old-pdf-path=] [--old-video-path=]
 *            [--old-image-url=] [--guard=on|off]
 *
 * Only sends read requests and needs no credentials. Exits with 1 when a check fails.
 * The rewrite rules live in .htaccess, so the checks marked with * only mean
 * something on LiteSpeed; `php artisan serve` does not read that file.
 */
const BROWSER_USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36';
const GOOGLEBOT_USER_AGENT = 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';

if (! extension_loaded('curl')) {
    fwrite(STDERR, "Cần phần mở rộng curl của PHP.\n");
    exit(2);
}

$options = ['detail-url' => null, 'video-page' => null, 'old-pdf-path' => null, 'old-video-path' => null, 'old-image-url' => null, 'guard' => null];
$base = null;

foreach (array_slice($argv, 1) as $argument) {
    if (preg_match('~^--([a-z-]+)=(.*)$~', $argument, $match) && array_key_exists($match[1], $options)) {
        $options[$match[1]] = $match[2];
    } elseif ($base === null && preg_match('~^https?://[^/]+~i', $argument)) {
        $base = rtrim($argument, '/');
    } else {
        fwrite(STDERR, "Tham số không hợp lệ: {$argument}\n");
        exit(2);
    }
}

if ($base === null || ! in_array($options['guard'], [null, 'on', 'off'], true)) {
    fwrite(STDERR, "Cách dùng: php scripts/verify-content-protection.php <base-url> [--detail-url=] [--video-page=] [--old-pdf-path=] [--old-video-path=] [--old-image-url=] [--guard=on|off]\n");
    exit(2);
}

$host = (string) parse_url($base, PHP_URL_HOST);
$port = parse_url($base, PHP_URL_PORT);
$origin = parse_url($base, PHP_URL_SCHEME).'://'.$host.($port ? ':'.$port : '');

/** @var list<array{group: string, name: string, expected: string, actual: string, result: string}> $rows */
$rows = [];

/**
 * @param  array<string, string>  $headers  A User-Agent entry replaces the browser one.
 * @return array{status: int, headers: array<string, string>, body: string, error: string}
 */
function request(string $url, array $headers = []): array
{
    $headers += ['User-Agent' => BROWSER_USER_AGENT];
    $responseHeaders = [];

    $handle = curl_init($url);
    curl_setopt_array($handle, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_ENCODING => '',
        CURLOPT_HTTPHEADER => array_map(
            static fn (string $name, string $value): string => $name.': '.$value,
            array_keys($headers),
            $headers,
        ),
        CURLOPT_HEADERFUNCTION => static function ($handle, string $line) use (&$responseHeaders): int {
            if (str_contains($line, ':')) {
                [$name, $value] = explode(':', $line, 2);
                $responseHeaders[strtolower(trim($name))] = trim($value);
            }

            return strlen($line);
        },
    ]);

    $body = curl_exec($handle);
    $response = [
        'status' => (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE),
        'headers' => $responseHeaders,
        'body' => is_string($body) ? $body : '',
        'error' => curl_error($handle),
    ];
    curl_close($handle);

    return $response;
}

/**
 * @param  array{status: int, error: string}  $response
 */
function statusText(array $response): string
{
    return $response['status'] === 0 ? 'lỗi kết nối: '.$response['error'] : (string) $response['status'];
}

function absoluteUrl(string $url, string $origin): string
{
    if (preg_match('~^https?://~i', $url)) {
        return $url;
    }

    return str_starts_with($url, '//')
        ? parse_url($origin, PHP_URL_SCHEME).':'.$url
        : $origin.'/'.ltrim($url, '/');
}

/**
 * HTML with entities and JSON escaping removed, so URLs inside attributes and inline JSON read the same.
 */
function plainMarkup(string $html): string
{
    return str_replace(['\\/', '\\u0026'], ['/', '&'], html_entity_decode($html, ENT_QUOTES | ENT_HTML5));
}

$record = static function (string $group, string $name, string $expected, bool $passed, string $actual) use (&$rows): void {
    $rows[] = ['group' => $group, 'name' => $name, 'expected' => $expected, 'actual' => $actual, 'result' => $passed ? 'PASS' : 'FAIL'];
};

$skip = static function (string $group, string $name, string $reason) use (&$rows): void {
    $rows[] = ['group' => $group, 'name' => $name, 'expected' => '', 'actual' => $reason, 'result' => 'SKIP'];
};

/**
 * @param  list<int>  $allowed
 * @param  array<string, string>  $headers
 */
$expectStatus = static function (string $group, string $name, string $url, array $allowed, array $headers = []) use ($record): array {
    $response = request($url, $headers);
    $record(
        $group,
        $name,
        implode(' hoặc ', $allowed),
        in_array($response['status'], $allowed, true),
        statusText($response),
    );

    return $response;
};

// Trang chủ là nguồn của nhiều phép thử phía sau.
$home = request($origin.'/');
if ($home['status'] !== 200) {
    fwrite(STDERR, "Không tải được trang chủ {$origin}/ (HTTP {$home['status']} {$home['error']}).\n");
    exit(2);
}
$homeMarkup = plainMarkup($home['body']);

// File nhạy cảm
foreach (['/.env', '/.git/config', '/composer.json', '/storage/giam_dung_luong.py', '/assets/js/app.js'] as $path) {
    $expectStatus('File nhạy cảm', $path, $origin.$path, [403, 404]);
}

// Header
$record('Header', 'X-Frame-Options trên trang chủ', 'SAMEORIGIN',
    strtoupper($home['headers']['x-frame-options'] ?? '') === 'SAMEORIGIN',
    $home['headers']['x-frame-options'] ?? 'không có');
$record('Header', 'Content-Security-Policy trên trang chủ', "frame-ancestors 'self'",
    str_contains($home['headers']['content-security-policy'] ?? '', "frame-ancestors 'self'"),
    $home['headers']['content-security-policy'] ?? 'không có');

// Ảnh
$image = null;
if (preg_match_all('~(?:src|data-src|href|content)=["\']([^"\']+\.(?:webp|jpe?g|png))["\']~i', $homeMarkup, $found)) {
    foreach ($found[1] as $candidate) {
        $candidate = absoluteUrl($candidate, $origin);
        if (parse_url($candidate, PHP_URL_HOST) === $host && preg_match('~^/(storage|assets/images)/~', (string) parse_url($candidate, PHP_URL_PATH))) {
            // Ảnh trong storage đi qua cả hai file .htaccess, nên được ưu tiên.
            if ($image === null || (! str_contains($image, '/storage/') && str_contains($candidate, '/storage/'))) {
                $image = $candidate;
            }
        }
    }
}

if ($image === null) {
    $skip('Ảnh', 'Chống hotlink', 'không tìm thấy URL ảnh nào trong HTML trang chủ');
} else {
    $expectStatus('Ảnh', 'Không Referer', $image, [200]);
    $expectStatus('Ảnh', 'Referer cùng host', $image, [200], ['Referer' => $origin.'/']);
    $expectStatus('Ảnh', 'User-Agent Googlebot-Image', $image, [200], ['User-Agent' => 'Googlebot-Image/1.0']);
    $expectStatus('Ảnh', '* Referer https://example.com/', $image, [403], ['Referer' => 'https://example.com/']);
    $expectStatus('Ảnh', '* Sec-Fetch-Site: cross-site, không Referer', $image, [403], ['Sec-Fetch-Site' => 'cross-site']);
    $expectStatus('Ảnh', 'Sec-Fetch-Site: cross-site, Referer Google', $image, [200],
        ['Sec-Fetch-Site' => 'cross-site', 'Referer' => 'https://www.google.com/']);
    $expectStatus('Ảnh', '* Referer giả tên miền trong allowlist', $image, [403], ['Referer' => 'https://google.example.com/']);
}

if ($options['old-image-url'] === null) {
    $skip('Ảnh', 'URL ảnh cũ chuyển hướng tới .webp', 'thiếu --old-image-url');
} else {
    $old = request(absoluteUrl($options['old-image-url'], $origin));
    $location = $old['headers']['location'] ?? '';
    $redirected = $old['status'] === 301 && preg_match('~\.webp$~i', (string) parse_url($location, PHP_URL_PATH)) === 1;
    $record('Ảnh', 'URL ảnh cũ', '301 tới .webp', $redirected, statusText($old).($location !== '' ? ' → '.$location : ''));
    if ($redirected) {
        $expectStatus('Ảnh', 'URL đích của ảnh cũ', absoluteUrl($location, $origin), [200]);
    }
}

// Công cụ sao chép
$expectStatus('Công cụ sao chép', '* User-Agent HTTrack trên /', $origin.'/', [403], ['User-Agent' => 'Mozilla/4.5 (compatible; HTTrack 3.0x; Windows 98)']);
$expectStatus('Công cụ sao chép', '* User-Agent curl/8.0 trên /', $origin.'/', [403], ['User-Agent' => 'curl/8.0']);
$expectStatus('Công cụ sao chép', 'User-Agent curl/8.0 trên /up', $origin.'/up', [200], ['User-Agent' => 'curl/8.0']);

// Catalog
$catalogIndex = request($origin.'/dich-vu/tai-catalog');
if (preg_match('~/dich-vu/tai-catalog/doc/\d+~', plainMarkup($catalogIndex['body']), $reader)) {
    $page = request($origin.$reader[0]);
    $record('Catalog', 'Trang đọc '.$reader[0], '200, không chứa .pdf',
        $page['status'] === 200 && stripos($page['body'], '.pdf') === false,
        $page['status'].(stripos($page['body'], '.pdf') === false ? ', không chứa .pdf' : ', có chứa .pdf'));
} else {
    $skip('Catalog', 'Trang đọc catalog', "không tìm thấy liên kết trang đọc (HTTP {$catalogIndex['status']})");
}

if ($options['old-pdf-path'] === null) {
    $skip('Catalog', 'URL PDF gốc', 'thiếu --old-pdf-path');
} else {
    $expectStatus('Catalog', 'URL PDF gốc', absoluteUrl($options['old-pdf-path'], $origin), [403, 404]);
}

// Video
if ($options['video-page'] === null) {
    $skip('Video', 'URL ký của video', 'thiếu --video-page');
} else {
    $videoPage = request(absoluteUrl($options['video-page'], $origin));
    if (preg_match('~(?:https?://[^"\'\s<>]+)?/media/video/[^"\'\s<>\\\\]+~', plainMarkup($videoPage['body']), $signed)) {
        $video = absoluteUrl($signed[0], $origin);
        $player = ['Referer' => absoluteUrl($options['video-page'], $origin), 'Range' => 'bytes=0-99'];

        $expectStatus('Video', 'URL ký, Referer cùng host, Range', $video, [206], $player);
        $unsigned = (string) preg_replace('~([?&])signature=[^&]*&?~', '$1', $video);
        $expectStatus('Video', 'Bỏ tham số signature', rtrim($unsigned, '?&'), [403], $player);
        $expectStatus('Video', 'Mở trực tiếp (Sec-Fetch-Dest: document)', $video, [403], $player + ['Sec-Fetch-Dest' => 'document']);
    } else {
        $record('Video', 'URL ký của video', 'có URL /media/video/ trong trang', false, 'không tìm thấy ('.statusText($videoPage).')');
    }
}

if ($options['old-video-path'] === null) {
    $skip('Video', 'URL video cũ', 'thiếu --old-video-path');
} else {
    $expectStatus('Video', 'URL video cũ', absoluteUrl($options['old-video-path'], $origin), [403, 404]);
}

// Chống inspect
$guardLoaded = str_contains($home['body'], 'content-protection');
if ($options['guard'] === null) {
    $skip('Chống inspect', 'Script bảo vệ trên trang chủ', 'thiếu --guard=on|off');
} else {
    $record('Chống inspect', 'Trang chủ khi --guard='.$options['guard'],
        $options['guard'] === 'on' ? 'chứa content-protection' : 'không chứa content-protection',
        $guardLoaded === ($options['guard'] === 'on'),
        $guardLoaded ? 'có' : 'không có');
}

$crawlerHome = request($origin.'/', ['User-Agent' => GOOGLEBOT_USER_AGENT]);
$record('Chống inspect', 'Trang chủ với User-Agent Googlebot', '200, không chứa content-protection',
    $crawlerHome['status'] === 200 && ! str_contains($crawlerHome['body'], 'content-protection'),
    $crawlerHome['status'].(str_contains($crawlerHome['body'], 'content-protection') ? ', có script' : ', không có script'));

foreach (['/noi-dung-duoc-bao-ve', '/canh-bao-ban-quyen'] as $path) {
    $notice = request($origin.$path);
    $noindex = preg_match('~<meta[^>]+name=["\']robots["\'][^>]+noindex~i', $notice['body']) === 1;
    $record('Chống inspect', $path, '200, có noindex', $notice['status'] === 200 && $noindex,
        $notice['status'].($noindex ? ', có noindex' : ', không có noindex'));
}

// SEO
$normalize = static fn (string $text): string => trim(str_replace("\r\n", "\n", $text));
$baseline = @file_get_contents(__DIR__.'/../public/robots.txt');
$robots = request($origin.'/robots.txt');
if ($baseline === false) {
    $skip('SEO', '/robots.txt', 'không đọc được public/robots.txt để so sánh');
} else {
    $record('SEO', '/robots.txt', 'giống public/robots.txt',
        $robots['status'] === 200 && $normalize($robots['body']) === $normalize($baseline),
        $robots['status'] === 200 ? ($normalize($robots['body']) === $normalize($baseline) ? 'giống' : 'khác') : (string) $robots['status']);
}

if ($options['detail-url'] === null) {
    $skip('SEO', 'JSON-LD trên trang chi tiết', 'thiếu --detail-url');
} else {
    $detail = request(absoluteUrl($options['detail-url'], $origin));
    $blocks = preg_match_all('~<script[^>]+type=["\']application/ld\+json["\']~i', $detail['body']);
    $record('SEO', 'JSON-LD trên trang chi tiết', '200, có application/ld+json',
        $detail['status'] === 200 && $blocks > 0, statusText($detail).", {$blocks} khối");
}

// Bảng kết quả
$width = static fn (string $key): int => max(array_map(static fn (array $row): int => mb_strlen($row[$key]), $rows));
$pad = static fn (string $text, int $length): string => $text.str_repeat(' ', max(0, $length - mb_strlen($text)));
$widths = ['group' => $width('group'), 'name' => $width('name'), 'expected' => $width('expected')];

echo "Kiểm chứng chống sao chép: {$origin}\n\n";
foreach ($rows as $row) {
    echo $pad($row['result'], 4).'  '.$pad($row['group'], $widths['group']).'  '.$pad($row['name'], $widths['name']).'  '
        .$pad($row['expected'], $widths['expected']).'  '.$row['actual']."\n";
}

$counts = array_count_values(array_column($rows, 'result')) + ['PASS' => 0, 'FAIL' => 0, 'SKIP' => 0];
echo "\nPASS {$counts['PASS']}, FAIL {$counts['FAIL']}, SKIP {$counts['SKIP']}\n";

if ($counts['SKIP'] > 0) {
    echo "Mục SKIP chưa được kiểm; truyền đủ tham số để kiểm toàn bộ.\n";
}
if (stripos($home['headers']['server'] ?? '', 'litespeed') === false) {
    echo "Máy chủ này không phải LiteSpeed: các mục đánh dấu * thuộc luật .htaccess và chỉ có nghĩa trên LiteSpeed.\n";
}

exit($counts['FAIL'] > 0 ? 1 : 0);
