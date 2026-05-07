<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FactoryPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hero_banner_desktop' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'hero_banner_mobile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'intro_title' => ['nullable', 'string', 'max:500'],
            'intro_subtitle' => ['nullable', 'string', 'max:500'],
            'intro_description' => ['nullable', 'string'],
            'process_title' => ['nullable', 'string', 'max:500'],
            'process_description' => ['nullable', 'string'],
            'process_bottom_title' => ['nullable', 'string', 'max:500'],
            'process_bottom_desc' => ['nullable', 'string'],
            'process_bottom_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            'new_gallery_1' => ['nullable', 'array'],
            'new_gallery_1.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'delete_gallery_1' => ['nullable', 'array'],
            'delete_gallery_1.*' => ['integer'],

            'new_process_slider' => ['nullable', 'array'],
            'new_process_slider.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'delete_process_slider' => ['nullable', 'array'],
            'delete_process_slider.*' => ['integer'],

            'new_material_slider' => ['nullable', 'array'],
            'new_material_slider.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'delete_material_slider' => ['nullable', 'array'],
            'delete_material_slider.*' => ['integer'],

            'material_steps' => ['nullable', 'array'],
            'material_steps.*.number' => ['nullable', 'string'],
            'material_steps.*.title' => ['nullable', 'string', 'max:500'],
            'material_steps.*.description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'hero_banner_desktop.image' => 'Banner desktop phải là file hình ảnh.',
            'hero_banner_desktop.max' => 'Banner desktop không được vượt quá 5MB.',
            'hero_banner_mobile.image' => 'Banner mobile phải là file hình ảnh.',
            'hero_banner_mobile.max' => 'Banner mobile không được vượt quá 5MB.',
            'intro_title.max' => 'Tiêu đề giới thiệu không được vượt quá 500 ký tự.',
            'intro_subtitle.max' => 'Phụ đề giới thiệu không được vượt quá 500 ký tự.',
            'process_title.max' => 'Tiêu đề quy trình không được vượt quá 500 ký tự.',
            'process_bottom_title.max' => 'Tiêu đề ảnh cuối quy trình không được vượt quá 500 ký tự.',
            'process_bottom_image.image' => 'Ảnh cuối quy trình phải là file hình ảnh.',
            'process_bottom_image.max' => 'Ảnh cuối quy trình không được vượt quá 5MB.',
            'new_gallery_1.*.image' => 'Mỗi file trong thư viện ảnh 1 phải là hình ảnh.',
            'new_gallery_1.*.max' => 'Mỗi file trong thư viện ảnh 1 không được vượt quá 5MB.',
            'new_process_slider.*.image' => 'Mỗi file trong slider quy trình phải là hình ảnh.',
            'new_process_slider.*.max' => 'Mỗi file trong slider quy trình không được vượt quá 5MB.',
            'new_material_slider.*.image' => 'Mỗi file trong slider nguyên liệu phải là hình ảnh.',
            'new_material_slider.*.max' => 'Mỗi file trong slider nguyên liệu không được vượt quá 5MB.',
            'material_steps.*.title.max' => 'Tiêu đề bước nguyên liệu không được vượt quá 500 ký tự.',
        ];
    }
}
