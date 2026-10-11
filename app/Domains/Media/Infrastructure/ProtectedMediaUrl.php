<?php

namespace App\Domains\Media\Infrastructure;

use Illuminate\Support\Facades\URL;

class ProtectedMediaUrl
{
    /**
     * Expiring signed URL for a privately stored video. The signature is relative, so it
     * stays valid when HTTPS ends in front of PHP. Only sign paths read from the database,
     * never a path taken from a request.
     */
    public static function video(string $path): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');

        if (! app()->bound('url')) {
            // Unit tests may run without a booted application; an unsigned URL is refused anyway.
            return '/media/video/'.$path;
        }

        return URL::temporarySignedRoute(
            'client.media.video',
            now()->addMinutes((int) config('content_protection.video_url_ttl_minutes', 240)),
            ['path' => $path],
            absolute: false,
        );
    }
}
