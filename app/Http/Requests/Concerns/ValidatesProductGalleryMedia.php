<?php

namespace App\Http\Requests\Concerns;

use App\Rules\YoutubeUrl;

trait ValidatesProductGalleryMedia
{
    /**
     * @return array<string, mixed>
     */
    protected function galleryImageStoreRules(): array
    {
        return [
            'cover_image' => ['nullable', 'required_without:images', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'images' => ['nullable', 'required_without:cover_image', 'array', 'min:1'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function galleryImageUpdateRules(): array
    {
        return [
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'new_images' => ['nullable', 'array'],
            'new_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'gallery_order' => ['nullable', 'array'],
            'gallery_order.*' => ['string'],
        ];
    }

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
    protected function galleryImageMessages(): array
    {
        return [
            'cover_image.required_without' => 'Vui lòng tải ảnh bìa hoặc ít nhất một ảnh gallery.',
            'cover_image.image' => 'Ảnh bìa phải là định dạng hình ảnh.',
            'cover_image.mimes' => 'Ảnh bìa phải có định dạng: jpg, jpeg, png, webp.',
            'cover_image.max' => 'Ảnh bìa không được vượt quá 5MB.',
            'images.required_without' => 'Vui lòng tải ảnh bìa hoặc ít nhất một ảnh gallery.',
            'images.min' => 'Vui lòng tải lên ít nhất một hình ảnh sản phẩm.',
            'images.*.image' => 'File tải lên phải là định dạng hình ảnh.',
            'images.*.mimes' => 'Hình ảnh phải có định dạng: jpg, jpeg, png, webp.',
            'images.*.max' => 'Mỗi hình ảnh không được vượt quá 5MB.',
            'new_images.*.image' => 'File tải lên phải là định dạng hình ảnh.',
            'new_images.*.mimes' => 'Hình ảnh phải có định dạng: jpg, jpeg, png, webp.',
            'new_images.*.max' => 'Mỗi hình ảnh không được vượt quá 5MB.',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function galleryMediaMessages(): array
    {
        return array_merge($this->galleryImageMessages(), [
            'video.url' => 'Link YouTube hành trình không hợp lệ.',
            'video.max' => 'Link YouTube hành trình không được vượt quá 500 ký tự.',
            'video_urls.*.url' => 'Link YouTube không hợp lệ.',
            'video_urls.*.max' => 'Link YouTube không được vượt quá 500 ký tự.',
            'new_video_urls.*.url' => 'Link YouTube không hợp lệ.',
            'new_video_urls.*.max' => 'Link YouTube không được vượt quá 500 ký tự.',
        ]);
    }
}
