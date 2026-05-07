<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category' => ['required', 'string', 'in:sản-phẩm,báo-giá,vận-chuyển,lắp-đặt,đổi-trả'],
            'question' => ['required', 'string', 'max:1000'],
            'answer' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'category.required' => 'Danh mục FAQ là bắt buộc.',
            'category.in' => 'Danh mục FAQ không hợp lệ.',
            'question.required' => 'Câu hỏi là bắt buộc.',
            'question.max' => 'Câu hỏi không được vượt quá 1000 ký tự.',
            'answer.required' => 'Câu trả lời là bắt buộc.',
            'sort_order.integer' => 'Thứ tự sắp xếp phải là số nguyên.',
            'sort_order.min' => 'Thứ tự sắp xếp không được âm.',
        ];
    }
}
