<?php

namespace App\Domains\Commerce\Http\Requests\Cart;

use App\Domains\Catalog\Domain\ProductTypeRegistry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductCartOptionsRequest extends FormRequest
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
        ];
    }
}
