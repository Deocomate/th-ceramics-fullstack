<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'banner_image.image' => 'Ảnh banner phải là định dạng ảnh hợp lệ.',
            'banner_image.mimes' => 'Ảnh banner phải có định dạng: jpg, jpeg, png, webp.',
            'banner_image.max' => 'Ảnh banner không được vượt quá 5MB.',
        ];
    }
}
