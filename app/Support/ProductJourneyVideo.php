<?php

namespace App\Support;

class ProductJourneyVideo
{
    /**
     * Prefer product-specific journey video; otherwise fall back to category/global config.
     */
    public static function resolve(?string $productVideo, mixed $config = null): ?string
    {
        $productVideo = is_string($productVideo) ? trim($productVideo) : '';
        if ($productVideo !== '') {
            return $productVideo;
        }

        if ($config === null) {
            return null;
        }

        foreach (['video', 'video_url'] as $key) {
            $value = is_object($config) ? ($config->{$key} ?? null) : ($config[$key] ?? null);
            $value = is_string($value) ? trim($value) : '';
            if ($value !== '') {
                return $value;
            }
        }

        return null;
    }
}
