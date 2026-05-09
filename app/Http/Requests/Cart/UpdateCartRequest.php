<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'row_id' => ['required', 'string'],
            'qty' => ['required', 'integer', 'min:1', 'max:99999'],
        ];
    }

    public function messages(): array
    {
        return [
            'row_id.required' => 'Vui lòng chọn sản phẩm cần cập nhật.',
            'qty.required' => 'Vui lòng nhập số lượng.',
            'qty.min' => 'Số lượng tối thiểu là 1.',
            'qty.max' => 'Số lượng tối đa là 99999.',
        ];
    }
}
