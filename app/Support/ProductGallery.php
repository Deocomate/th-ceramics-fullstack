<?php

namespace App\Support;

use App\Helpers\FileUploadHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class ProductGallery
{
    public const TYPE_IMAGE = 'image';

    public const TYPE_VIDEO = 'video';

    /**
     * @return Collection<int, array{type: string, path?: string, url?: string, youtube_id?: string|null, thumb_url?: string|null, embed_url?: string|null}>
     */
    public static function normalize(mixed $images): Collection
    {
        if (is_string($images)) {
            $decoded = json_decode($images, true);
            $images = is_array($decoded) ? $decoded : [];
        }

        if (! is_array($images)) {
            return collect();
        }

        return collect($images)
            ->map(fn (mixed $item) => self::normalizeItem($item))
            ->filter()
            ->values();
    }

    public static function firstImagePath(mixed $images): ?string
    {
        $item = self::normalize($images)->firstWhere('type', self::TYPE_IMAGE);

        return is_array($item) ? ($item['path'] ?? null) : null;
    }

    public static function extractYoutubeId(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        if (preg_match(
            '~(?:youtube\.com/(?:watch\?v=|embed/|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{6,})~i',
            $url,
            $matches
        )) {
            return $matches[1];
        }

        return null;
    }

    public static function embedUrl(string $id, array $params = []): string
    {
        $query = array_merge([
            'enablejsapi' => '1',
            'rel' => '0',
            'playsinline' => '1',
            'modestbranding' => '1',
        ], $params);

        return 'https://www.youtube.com/embed/'.$id.'?'.http_build_query($query);
    }

    public static function thumbUrl(string $id): string
    {
        return 'https://img.youtube.com/vi/'.$id.'/hqdefault.jpg';
    }

    /**
     * @param  array<int, mixed>  $current
     * @param  array<int, mixed>  $files
     * @return array<int, mixed>
     */
    public static function appendUploadedImages(array $current, array $files, string $directory): array
    {
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $current[] = FileUploadHelper::upload($file, $directory);
            }
        }

        return array_values($current);
    }

    /**
     * @param  array<int, mixed>  $current
     * @param  array<int, mixed>  $urls
     * @return array<int, mixed>
     */
    public static function appendVideoUrls(array $current, array $urls): array
    {
        foreach ($urls as $url) {
            if (! is_string($url)) {
                continue;
            }

            $trimmed = trim($url);
            if ($trimmed === '' || self::extractYoutubeId($trimmed) === null) {
                continue;
            }

            $current[] = [
                'type' => self::TYPE_VIDEO,
                'url' => $trimmed,
            ];
        }

        return array_values($current);
    }

    /**
     * @param  array<int, mixed>  $current
     * @return array<int, mixed>
     */
    public static function removeImagePath(array $current, string $path): array
    {
        $filtered = array_filter($current, function (mixed $item) use ($path) {
            if (is_string($item)) {
                return $item !== $path;
            }

            if (is_array($item) && ($item['type'] ?? null) === self::TYPE_IMAGE) {
                return ($item['path'] ?? null) !== $path;
            }

            return true;
        });

        return array_values($filtered);
    }

    /**
     * @param  array<int, mixed>  $current
     * @return array<int, mixed>
     */
    public static function removeVideoUrl(array $current, string $url): array
    {
        $targetId = self::extractYoutubeId($url);

        $filtered = array_filter($current, function (mixed $item) use ($url, $targetId) {
            if (! is_array($item) || ($item['type'] ?? null) !== self::TYPE_VIDEO) {
                return true;
            }

            $itemUrl = (string) ($item['url'] ?? '');
            if ($itemUrl === $url) {
                return false;
            }

            if ($targetId !== null && self::extractYoutubeId($itemUrl) === $targetId) {
                return false;
            }

            return true;
        });

        return array_values($filtered);
    }

    /**
     * @return array{type: string, path?: string, url?: string, youtube_id?: string|null, thumb_url?: string|null, display_url?: string|null}|null
     */
    private static function normalizeItem(mixed $item): ?array
    {
        if (is_string($item)) {
            $path = trim($item);
            if ($path === '') {
                return null;
            }

            return [
                'type' => self::TYPE_IMAGE,
                'path' => $path,
            ];
        }

        if (! is_array($item)) {
            return null;
        }

        $type = $item['type'] ?? null;

        if ($type === self::TYPE_VIDEO || (isset($item['url']) && ! isset($item['path']))) {
            $url = trim((string) ($item['url'] ?? ''));
            $youtubeId = self::extractYoutubeId($url);
            if ($url === '' || $youtubeId === null) {
                return null;
            }

            return [
                'type' => self::TYPE_VIDEO,
                'url' => $url,
                'youtube_id' => $youtubeId,
                'thumb_url' => self::thumbUrl($youtubeId),
                'embed_url' => self::embedUrl($youtubeId),
            ];
        }

        if ($type === self::TYPE_IMAGE || isset($item['path'])) {
            $path = trim((string) ($item['path'] ?? ''));
            if ($path === '') {
                return null;
            }

            return [
                'type' => self::TYPE_IMAGE,
                'path' => $path,
            ];
        }

        return null;
    }
}
