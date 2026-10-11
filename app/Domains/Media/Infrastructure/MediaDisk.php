<?php

namespace App\Domains\Media\Infrastructure;

class MediaDisk
{
    /**
     * Directories whose files must never be reachable through a public URL.
     */
    private const PRIVATE_PREFIXES = [
        'catalog/files/',
    ];

    /**
     * Self-hosted video formats, served only through expiring signed URLs.
     */
    private const PRIVATE_EXTENSIONS = ['mp4', 'webm'];

    /**
     * Disk that owns a stored media path. The path itself is the same on either disk,
     * so database references never change when a file becomes private.
     */
    public static function forPath(string $path): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');

        foreach (self::PRIVATE_PREFIXES as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return 'local';
            }
        }

        if (in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), self::PRIVATE_EXTENSIONS, true)) {
            return 'local';
        }

        return 'public';
    }
}
