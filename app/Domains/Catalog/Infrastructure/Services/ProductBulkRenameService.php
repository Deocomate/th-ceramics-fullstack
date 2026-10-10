<?php

namespace App\Domains\Catalog\Infrastructure\Services;

use App\Domains\Catalog\Domain\PhuKienNgoiCategory;
use App\Domains\Catalog\Infrastructure\Models\Product;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ProductBulkRenameService
{
    public function __construct(private readonly ProductCopyService $productCopyService) {}

    /**
     * @param  array<int, int>  $ids  Product IDs in display order.
     */
    public function rename(string $type, array $ids, string $baseName, ?string $categoryType = null): int
    {
        $config = $this->productCopyService->getTypeConfig($type);
        if (! $config) {
            throw new InvalidArgumentException('Loại sản phẩm không hợp lệ.');
        }

        $baseName = trim($baseName);
        if ($baseName === '') {
            throw new InvalidArgumentException('Tên sản phẩm không được để trống.');
        }

        $typeKey = $config['type_key'];

        if ($typeKey === 'phu_kien_ngoi_ct') {
            if (! in_array($categoryType, [PhuKienNgoiCategory::TYPE_BO_NOC, PhuKienNgoiCategory::TYPE_CHU_VAN], true)) {
                throw new InvalidArgumentException('Loại phụ kiện ngói không hợp lệ.');
            }
        }

        return DB::transaction(function () use ($typeKey, $ids, $baseName, $categoryType): int {
            $products = Product::query()
                ->with('publicId')
                ->where('type_key', $typeKey)
                ->where(function ($q) use ($typeKey, $ids) {
                    $q->whereHas('publicId', fn ($p) => $p->where('type_key', $typeKey)->whereIn('public_id', $ids))
                        ->orWhereIn('id', $ids);
                })
                ->when($categoryType !== null, fn ($q) => $q->where('category_type', $categoryType))
                ->lockForUpdate()
                ->get()
                ->keyBy(fn (Product $p) => (int) $p->public_id);

            if ($products->count() !== count($ids)) {
                throw new InvalidArgumentException('Một hoặc nhiều sản phẩm không tồn tại trong danh sách này.');
            }

            $names = [];
            $total = count($ids);
            foreach ($ids as $index => $id) {
                $name = $baseName.' '.($index + 1);
                if (mb_strlen($name) > 255) {
                    throw new InvalidArgumentException('Tên sản phẩm sau khi thêm số thứ tự không được vượt quá 255 ký tự.');
                }
                $names[$id] = $name;
            }

            foreach ($names as $id => $name) {
                $product = $products->get($id);
                if ($product) {
                    $product->update(['name' => $name]);
                }
            }

            return count($names);
        });
    }
}
