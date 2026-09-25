<?php

namespace App\Services;

use App\Models\PhuKienNgoiCt;
use Illuminate\Database\Eloquent\Model;
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

        $modelClass = $config['model'];
        $query = $modelClass::query()->whereIn($config['pk'], $ids);

        if ($modelClass === PhuKienNgoiCt::class) {
            if (! in_array($categoryType, [PhuKienNgoiCt::TYPE_BO_NOC, PhuKienNgoiCt::TYPE_CHU_VAN], true)) {
                throw new InvalidArgumentException('Loại phụ kiện ngói không hợp lệ.');
            }

            $query->where('category_type', $categoryType);
        }

        return DB::transaction(function () use ($query, $ids, $baseName, $config): int {
            $products = $query->lockForUpdate()->get()->keyBy($config['pk']);

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

            foreach ($ids as $id) {
                /** @var Model $product */
                $product = $products->get($id);
                $product->forceFill(['name' => $names[$id]])->save();
            }

            return $total;
        });
    }
}
