<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNgoiAmDuongCtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'           =>['required', 'string', 'max:50'],
            'name'           =>['required', 'string', 'max:255'],
            'price'          =>['required', 'integer', 'min:0'],
            'size'           => ['nullable', 'string', 'max:255'],
            'des'            => ['nullable', 'array'],
            'des.*'          => ['nullable', 'string', 'max:500'],
            'new_images'     => ['nullable', 'array'],
            'new_images.*'   =>['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'size_image'     =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}