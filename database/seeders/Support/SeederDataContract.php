<?php

namespace Database\Seeders\Support;

final class SeederDataContract
{
    public const MIN_GALLERY_IMAGES = 5;

    public const MAX_GALLERY_IMAGES = 10;

    /** @param  string[]  $paths */
    public static function assertGallery(array $paths, string $context): void
    {
        $count = count(array_filter($paths));
        if ($count < self::MIN_GALLERY_IMAGES || $count > self::MAX_GALLERY_IMAGES) {
            throw new \InvalidArgumentException("{$context}: expected 5–10 images, got {$count}");
        }
    }

    public static function assertNotNull(mixed $value, string $field, string $table): void
    {
        if ($value === null || $value === '') {
            throw new \InvalidArgumentException("{$table}.{$field} must not be null/empty");
        }
    }

    public static function assertTitleLength(string $value, int $max, string $field): void
    {
        if (mb_strlen($value) > $max) {
            throw new \InvalidArgumentException("{$field} too long ({$max} max): {$value}");
        }
    }

    /**
     * Expand a gallery to at least MIN_GALLERY_IMAGES using a deterministic pool rotation.
     *
     * @param  string[]  $paths
     * @param  string[]  $pool
     * @return string[]
     */
    public static function expandGallery(array $paths, array $pool, int $target = 7): array
    {
        $paths = array_values(array_filter($paths));
        $pool = array_values(array_filter($pool ?: $paths));

        if ($pool === []) {
            return $paths;
        }

        $merged = $paths;
        $offset = 0;

        while (count($merged) < self::MIN_GALLERY_IMAGES) {
            $merged[] = $pool[$offset % count($pool)];
            $offset++;
        }

        $target = max(self::MIN_GALLERY_IMAGES, min($target, self::MAX_GALLERY_IMAGES));

        return array_slice($merged, 0, $target);
    }

    /**
     * Rotate a pool deterministically and return exactly $count images.
     *
     * @param  string[]  $pool
     * @return string[]
     */
    public static function rotateGallery(array $pool, int $startIndex, int $count = 7): array
    {
        if ($pool === []) {
            return [];
        }

        $count = max(self::MIN_GALLERY_IMAGES, min($count, self::MAX_GALLERY_IMAGES));
        $rotated = array_merge(
            array_slice($pool, $startIndex % count($pool)),
            array_slice($pool, 0, $startIndex % count($pool))
        );

        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $result[] = $rotated[$i % count($rotated)];
        }

        return $result;
    }
}
