<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'map_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'hotline' => ['nullable', 'string', 'max:50'],
            'zalo_link' => ['nullable', 'string', 'max:500', 'url'],
            'form_title' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'map_image.image' => 'Ảnh bản đồ phải là định dạng ảnh hợp lệ.',
            'map_image.mimes' => 'Ảnh bản đồ phải có định dạng: jpg, jpeg, png, webp.',
            'map_image.max' => 'Ảnh bản đồ không được vượt quá 5MB.',
            'hotline.max' => 'Hotline không được vượt quá 50 ký tự.',
            'zalo_link.url' => 'Link Zalo phải là một URL hợp lệ.',
            'zalo_link.max' => 'Link Zalo không được vượt quá 500 ký tự.',
            'form_title.max' => 'Tiêu đề form không được vượt quá 500 ký tự.',
        ];
    }
}
