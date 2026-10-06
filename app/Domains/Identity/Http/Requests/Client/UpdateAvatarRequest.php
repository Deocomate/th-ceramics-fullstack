<?php

namespace App\Domains\Identity\Http\Requests\Client;

use App\Domains\Identity\Domain\UserProfileRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAvatarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:'.UserProfileRules::MAX_AVATAR_KILOBYTES],
        ];
    }

    public function messages(): array
    {
        return [
            'avatar.required' => 'Vui lòng chọn ảnh.',
            'avatar.image' => 'File tải lên phải là hình ảnh.',
            'avatar.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'avatar.max' => 'Kích thước ảnh tối đa là 2MB.',
        ];
    }
}
