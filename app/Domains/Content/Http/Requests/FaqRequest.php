<?php

namespace App\Domains\Content\Http\Requests;

use App\Domains\Content\Domain\FaqCategory;
use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $categories = implode(',', array_keys(FaqCategory::ALL));

        return [
            'category' => ['required', 'string', 'in:'.$categories],
            'question' => ['required', 'string', 'max:1000'],
            'answer' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /** @return array<string, string> */
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
