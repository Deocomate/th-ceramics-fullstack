<?php

namespace App\Domains\Commerce\Http\Requests\Cart;

use App\Domains\Catalog\Domain\ProductTypeRegistry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_type' => ['required', 'string', Rule::in(ProductTypeRegistry::keys())],
            'product_id' => ['required', 'integer', 'min:1'],
            'variant_id' => ['nullable', 'integer'],
            'qty' => ['required', 'integer', 'min:1', 'max:99999'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_type.required' => 'Vui lòng chọn loại sản phẩm.',
            'product_type.in' => 'Loại sản phẩm không hợp lệ.',
            'product_id.required' => 'Vui lòng chọn sản phẩm.',
            'qty.required' => 'Vui lòng nhập số lượng.',
            'qty.min' => 'Số lượng tối thiểu là 1.',
            'qty.max' => 'Số lượng tối đa là 99999.',
        ];
    }
}
