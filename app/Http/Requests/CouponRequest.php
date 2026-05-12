<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $couponId = $this->route('coupon');

        return [
            'title'                    => ['required', 'string', 'max:255'],
            'description'              => ['nullable', 'string'],
            'code'                     => ['required', 'string', 'max:50', 'unique:coupons,code,' . $couponId],
            'discount_type'            => ['required', 'in:percent,fixed'],
            'discount_value'           => ['required', 'numeric', 'min:0.1'],
            'max_discount_amount'      => ['nullable', 'integer', 'min:0'],
            'min_order_value'          => ['nullable', 'integer', 'min:0'],
            'applicable_product_types' => ['nullable', 'array'],
            'applicable_product_types.*' => ['string', 'in:ngoi_am_duong_ct,ngoi_hai_van_mieu_ct,ngoi_hai_co_ct,gach_hoa_thong_gio_ct,gach_trang_tri_ct,gach_co_bat_trang_ct,linh_vat_phong_thuy_ct,lan_can_gom_xu,den_gom_su,phu_kien_ngoi'],
            'usage_limit'              => ['nullable', 'integer', 'min:1'],
            'start_date'               => ['required', 'date'],
            'end_date'                 => ['nullable', 'date', 'after_or_equal:start_date'],
            'banner_image'             => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'show_banner'              => ['boolean'],
            'is_active'                => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'              => 'Tiêu đề mã giảm giá là bắt buộc.',
            'title.max'                   => 'Tiêu đề không được vượt quá 255 ký tự.',
            'code.required'               => 'Mã giảm giá là bắt buộc.',
            'code.max'                    => 'Mã giảm giá không được vượt quá 50 ký tự.',
            'code.unique'                 => 'Mã giảm giá này đã tồn tại.',
            'discount_type.required'      => 'Loại giảm giá là bắt buộc.',
            'discount_type.in'            => 'Loại giảm giá không hợp lệ.',
            'discount_value.required'     => 'Giá trị giảm là bắt buộc.',
            'discount_value.numeric'      => 'Giá trị giảm phải là số.',
            'discount_value.min'          => 'Giá trị giảm phải lớn hơn 0.',
            'max_discount_amount.integer' => 'Giảm tối đa phải là số nguyên.',
            'max_discount_amount.min'     => 'Giảm tối đa không được âm.',
            'min_order_value.integer'     => 'Giá trị đơn hàng tối thiểu phải là số nguyên.',
            'min_order_value.min'         => 'Giá trị đơn hàng tối thiểu không được âm.',
            'applicable_product_types.array'   => 'Danh sách loại sản phẩm không hợp lệ.',
            'applicable_product_types.*.in'     => 'Loại sản phẩm áp dụng không hợp lệ.',
            'usage_limit.integer'          => 'Giới hạn lượt dùng phải là số nguyên.',
            'usage_limit.min'              => 'Giới hạn lượt dùng phải ít nhất là 1.',
            'start_date.required'          => 'Ngày bắt đầu là bắt buộc.',
            'start_date.date'              => 'Ngày bắt đầu không hợp lệ.',
            'end_date.date'                => 'Ngày kết thúc không hợp lệ.',
            'end_date.after_or_equal'      => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'banner_image.image'           => 'File tải lên phải là hình ảnh.',
            'banner_image.mimes'           => 'Ảnh banner phải có định dạng jpeg, png, jpg hoặc webp.',
            'banner_image.max'             => 'Ảnh banner không được vượt quá 5MB.',
        ];
    }
}
