<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNgoiHaiVanMieuCtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return[
            'name'       => ['required', 'string', 'max:255'],
            'size'       => ['nullable', 'string', 'max:255'],
            'des'        =>['nullable', 'array'],
            'des.*'      =>['nullable', 'string', 'max:500'],
            'images'     => ['required', 'array', 'min:1'],
            'images.*'   =>['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'size_image' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return[
            'name.required'   => 'Tên sản phẩm là bắt buộc.',
            'name.max'        => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'images.required' => 'Vui lòng tải lên ít nhất một hình ảnh sản phẩm.',
            'images.min'      => 'Vui lòng tải lên ít nhất một hình ảnh sản phẩm.',
            'images.*.image'  => 'File tải lên phải là định dạng hình ảnh.',
            'images.*.mimes'  => 'Hình ảnh phải có định dạng: jpg, jpeg, png, webp.',
            'images.*.max'    => 'Mỗi hình ảnh không được vượt quá 5MB.',
            'size_image.image'=> 'Ảnh kích thước phải là định dạng hình ảnh.',
            'size_image.mimes'=> 'Ảnh kích thước phải có định dạng: jpg, jpeg, png, webp.',
            'size_image.max'  => 'Ảnh kích thước không được vượt quá 5MB.',
            'des.*.max'       => 'Mỗi dòng mô tả không được vượt quá 500 ký tự.',
        ];
    }
}