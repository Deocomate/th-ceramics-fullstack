<?php

namespace App\Domains\Catalog\Http\Admin;

use App\Domains\Catalog\Domain\RoofTileAccessoryCategory;
use App\Domains\Catalog\Infrastructure\Services\ProductBulkRenameService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class ProductBulkRenameController extends Controller
{
    public function __construct(private readonly ProductBulkRenameService $service) {}

    public function update(Request $request, string $type): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'min:1', 'distinct'],
            'base_name' => ['required', 'string', 'max:255'],
            'category_type' => [Rule::requiredIf($type === 'phu-kien-ngoi-ct'), 'nullable', Rule::in([
                RoofTileAccessoryCategory::TYPE_BO_NOC,
                RoofTileAccessoryCategory::TYPE_CHU_VAN,
            ])],
        ], [
            'ids.required' => 'Vui lòng chọn ít nhất một sản phẩm.',
            'ids.min' => 'Vui lòng chọn ít nhất một sản phẩm.',
            'ids.*.distinct' => 'Danh sách sản phẩm có ID trùng lặp.',
            'base_name.required' => 'Vui lòng nhập tên sản phẩm.',
            'base_name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
        ]);

        try {
            $count = $this->service->rename($type, array_map('intval', $data['ids']), $data['base_name'], $data['category_type'] ?? null);
        } catch (InvalidArgumentException $exception) {
            return back()->withInput()->withErrors(['base_name' => $exception->getMessage()]);
        }

        return back()->with('success', "Đã đổi tên {$count} sản phẩm thành công.");
    }
}
