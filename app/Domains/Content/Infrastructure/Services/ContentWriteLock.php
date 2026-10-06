<?php

namespace App\Domains\Content\Infrastructure\Services;

use App\Domains\Content\Http\Middleware\EnsureContentWritesOpen;
use Illuminate\Support\Facades\File;

class ContentWriteLock
{
    public static function lockPath(): string
    {
        return storage_path(EnsureContentWritesOpen::LOCK_FILE);
    }

    public static function isLocked(): bool
    {
        return is_file(self::lockPath());
    }

    public static function currentOwner(): ?string
    {
        if (! self::isLocked()) {
            return null;
        }

        $content = @file_get_contents(self::lockPath());
        if ($content === false) {
            return null;
        }

        $decoded = json_decode($content, true);
        if (is_array($decoded) && isset($decoded['owner'])) {
            return (string) $decoded['owner'];
        }

        return 'manual';
    }

    /**
     * Acquire lock exclusively with an owner token.
     * Fails if already locked.
     */
    public static function acquire(string $token): bool
    {
        $path = self::lockPath();
        File::ensureDirectoryExists(dirname($path));

        $handle = @fopen($path, 'x');
        if ($handle === false) {
            return false;
        }

        $data = json_encode([
            'owner' => $token,
            'locked_at' => now('UTC')->toIso8601String(),
        ], JSON_UNESCAPED_SLASHES);

        fwrite($handle, (string) $data);
        fflush($handle);
        fclose($handle);

        return true;
    }

    /**
     * Release lock if held by this owner token.
     * If owner token does not match, does not release.
     */
    public static function release(string $token): bool
    {
        $path = self::lockPath();
        if (! is_file($path)) {
            return true;
        }

        $owner = self::currentOwner();
        if ($owner === $token) {
            return @unlink($path);
        }

        return false;
    }

    /**
     * Manual lock from Artisan CLI.
     */
    public static function manualLock(): bool
    {
        if (self::currentOwner() === 'manual') {
            return true;
        }

        return self::acquire('manual');
    }

    /**
     * Manual unlock from Artisan CLI.
     */
    public static function manualUnlock(): bool
    {
        $path = self::lockPath();
        if (! is_file($path)) {
            return true;
        }

        return self::release('manual');
    }
}
