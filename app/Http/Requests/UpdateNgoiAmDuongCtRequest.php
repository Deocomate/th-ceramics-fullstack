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
            'code' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:0'],
            'size' => ['nullable', 'string', 'max:255'],

            'des' => ['nullable', 'array'],
            'des.*' => ['nullable', 'string', 'max:500'],

            // Edit không bắt buộc upload ảnh mới
            'new_images' => ['nullable', 'array'],
            'new_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            'size_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Mã sản phẩm là bắt buộc.',
            'code.max' => 'Mã sản phẩm không được vượt quá 50 ký tự.',
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'price.required' => 'Giá sản phẩm là bắt buộc.',
            'price.integer' => 'Giá sản phẩm phải là số hợp lệ.',
            'price.min' => 'Giá sản phẩm không được nhỏ hơn 0.',
            'new_images.*.image' => 'File tải lên phải là định dạng hình ảnh.',
            'new_images.*.mimes' => 'Hình ảnh phải có định dạng: jpg, jpeg, png, webp.',
            'new_images.*.max' => 'Mỗi hình ảnh không được vượt quá 5MB.',
            'size_image.image' => 'Ảnh kích thước phải là định dạng hình ảnh.',
            'size_image.mimes' => 'Ảnh kích thước phải có định dạng: jpg, jpeg, png, webp.',
            'size_image.max' => 'Ảnh kích thước không được vượt quá 5MB.',
            'des.*.max' => 'Mỗi dòng mô tả không được vượt quá 500 ký tự.',
        ];
    }
}
