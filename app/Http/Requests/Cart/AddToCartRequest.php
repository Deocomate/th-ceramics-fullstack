<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_type' => ['required', 'string', 'in:ngoi_am_duong_ct,ngoi_hai_van_mieu_ct,gach_hoa_thong_gio_ct,gach_trang_tri_ct,gach_co_bat_trang_ct,linh_vat_phong_thuy_ct,lan_can_gom_xu,den_gom_su,phu_kien_ngoi'],
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
