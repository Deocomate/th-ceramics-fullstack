<?php

namespace App\Support;

use Illuminate\Support\Str;

class AssetPath
{
    public static function url(mixed $path, mixed $fallback = null): string
    {
        $candidate = self::normalize($path) ?: self::normalize($fallback);

        if ($candidate === '') {
            return asset('');
        }

        if (Str::startsWith($candidate, ['http://', 'https://'])) {
            return $candidate;
        }

        if (Str::startsWith($candidate, 'assets/')) {
            return asset($candidate);
        }

        return asset('storage/'.ltrim($candidate, '/'));
    }

    private static function normalize(mixed $path): string
    {
        $candidate = $path;

        for ($i = 0; $i < 2; $i++) {
            if (is_array($candidate)) {
                $candidate = collect($candidate)->filter()->first();

                continue;
            }

            if (! is_string($candidate)) {
                break;
            }

            $trimmed = trim($candidate);
            if ($trimmed === '') {
                return '';
            }

            $decoded = json_decode($trimmed, true);
            if (json_last_error() !== JSON_ERROR_NONE || $decoded === $trimmed) {
                return $trimmed;
            }

            $candidate = $decoded;
        }

        return trim((string) $candidate);
    }
}
