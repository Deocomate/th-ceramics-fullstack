<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGachHoaThongGioCtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return[
            'code'       => ['required', 'string', 'max:50'],
            'name'       => ['required', 'string', 'max:255'],
            'price'      =>['required', 'integer', 'min:0'],
            'size'       => ['nullable', 'string', 'max:255'],
            'des'        => ['nullable', 'array'],
            'des.*'      =>['nullable', 'string', 'max:500'],
            'images'     => ['required', 'array', 'min:1'],
            'images.*'   =>['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'size_image' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return[
            'code.required'   => 'Mã sản phẩm là bắt buộc.',
            'code.max'        => 'Mã sản phẩm không được vượt quá 50 ký tự.',
            'name.required'   => 'Tên sản phẩm là bắt buộc.',
            'name.max'        => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'price.required'  => 'Giá sản phẩm là bắt buộc.',
            'images.required' => 'Vui lòng tải lên ít nhất một hình ảnh sản phẩm.',
            'images.*.image'  => 'File tải lên phải là định dạng hình ảnh.',
            'images.*.mimes'  => 'Hình ảnh phải có định dạng: jpg, jpeg, png, webp.',
            'images.*.max'    => 'Mỗi hình ảnh không được vượt quá 5MB.',
            'size_image.image'=> 'Ảnh kích thước phải là định dạng hình ảnh.',
            'size_image.max'  => 'Ảnh kích thước không được vượt quá 5MB.',
        ];
    }
}