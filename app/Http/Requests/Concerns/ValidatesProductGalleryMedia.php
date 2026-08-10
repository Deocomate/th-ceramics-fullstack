<?php

namespace App\Http\Requests\Concerns;

use App\Rules\YoutubeUrl;

trait ValidatesProductGalleryMedia
{
    /**
     * @return array<string, mixed>
     */
    protected function galleryStoreRules(): array
    {
        return [
            'video' => ['nullable', 'string', 'max:500', 'url', new YoutubeUrl],
            'video_urls' => ['nullable', 'array'],
            'video_urls.*' => ['nullable', 'string', 'max:500', 'url', new YoutubeUrl],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function galleryUpdateRules(): array
    {
        return [
            'video' => ['nullable', 'string', 'max:500', 'url', new YoutubeUrl],
            'new_video_urls' => ['nullable', 'array'],
            'new_video_urls.*' => ['nullable', 'string', 'max:500', 'url', new YoutubeUrl],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function galleryMediaMessages(): array
    {
        return [
            'video.url' => 'Link YouTube hành trình không hợp lệ.',
            'video.max' => 'Link YouTube hành trình không được vượt quá 500 ký tự.',
            'video_urls.*.url' => 'Link YouTube không hợp lệ.',
            'video_urls.*.max' => 'Link YouTube không được vượt quá 500 ký tự.',
            'new_video_urls.*.url' => 'Link YouTube không hợp lệ.',
            'new_video_urls.*.max' => 'Link YouTube không được vượt quá 500 ký tự.',
        ];
    }
}
