<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\ProductPriority;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductPriorityController extends Controller
{
    public function update(Request $request, string $type): JsonResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['required', 'integer', 'min:1'],
            'category_type' => ['nullable', 'string', 'max:50'],
        ]);

        ProductPriority::reorder($type, $data['ids'], $data['category_type'] ?? null);

        return response()->json(['message' => 'Đã lưu thứ tự ưu tiên sản phẩm.']);
    }
}
