<?php

namespace App\Support;

use Illuminate\Support\Str;

class AssetPath
{
    public static function url(?string $path, ?string $fallback = null): string
    {
        $candidate = trim((string) ($path ?: $fallback));

        if ($candidate === '') {
            return asset('');
        }

        if (Str::startsWith($candidate, ['http://', 'https://'])) {
            return $candidate;
        }

        if (Str::startsWith($candidate, 'assets/')) {
            return asset($candidate);
        }

        return asset('storage/' . ltrim($candidate, '/'));
    }
}
