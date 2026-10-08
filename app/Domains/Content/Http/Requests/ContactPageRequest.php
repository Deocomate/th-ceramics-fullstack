<?php

namespace App\Domains\Content\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'map_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'hotline' => ['required', 'string', 'max:50'],
            'zalo_link' => ['nullable', 'url', 'max:255'],
            'form_title' => ['required', 'string', 'max:255'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'map_image.image' => 'Ảnh bản đồ phải là một tệp hình ảnh.',
            'map_image.mimes' => 'Ảnh bản đồ phải có định dạng: jpg, jpeg, png, webp.',
            'map_image.max' => 'Ảnh bản đồ không được vượt quá 5MB.',
            'hotline.required' => 'Hotline là bắt buộc.',
            'hotline.max' => 'Hotline không được vượt quá 50 ký tự.',
            'zalo_link.url' => 'Link Zalo phải là một URL hợp lệ.',
            'zalo_link.max' => 'Link Zalo không được vượt quá 255 ký tự.',
            'form_title.required' => 'Tiêu đề form liên hệ là bắt buộc.',
            'form_title.max' => 'Tiêu đề form không được vượt quá 255 ký tự.',
        ];
    }
}
