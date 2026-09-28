<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Catalog\Http\Admin\BaseProductVariantController;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PhanLoaiPhuKienNgoiCtController extends BaseProductVariantController
{
    protected string $typeKey = 'phu_kien_ngoi_ct';
    protected string $viewPrefix = 'admin.phan-loai-phu-kien-ngoi-ct';
    protected string $routePrefix = 'admin.phan-loai-phu-kien-ngoi-ct';
    protected string $foreignKey = 'phu_kien_ngoi_ct_id';
    protected string $imageDirectory = 'phu_kien_ngoi_ct/variants';

    public function index(Request $request): View
    {
        $status = $request->query('status', 'active');
        $categoryType = $request->query('category_type') === 'chu_van' ? 'chu_van' : 'ngoi_bo_noc';
        $categoryLabel = $categoryType === 'chu_van' ? 'Bờ Nóc Chữ Vạn' : 'Ngói Bờ Nóc';
        $productId = $request->query('product_id') ? (int) $request->query('product_id') : null;

        $products = Product::query()
            ->with('publicId')
            ->where('type_key', $this->typeKey)
            ->where('category_type', $categoryType)
            ->where('is_delete', false)
            ->get();

        $selectedProduct = $productId ? $this->queryService->find($this->typeKey, $productId) : null;

        $query = ProductVariant::query()
            ->with(['publicId', 'product.publicId'])
            ->where('is_default', false)
            ->whereHas('product', function ($q) use ($categoryType, $selectedProduct) {
                $q->where('type_key', $this->typeKey)
                    ->where('category_type', $categoryType);
                if ($selectedProduct) {
                    $q->whereKey($selectedProduct->id);
                }
            });

        if ($status === 'active') {
            $query->where('is_delete', false);
        } elseif ($status === 'deleted') {
            $query->where('is_delete', true);
        }

        $phanLoais = $query->orderByDesc('id')->get();
        $mauSacs = $phanLoais;

        return view($this->viewPrefix . '.index', compact(
            'phanLoais',
            'mauSacs',
            'products',
            'status',
            'selectedProduct',
            'categoryType',
            'categoryLabel'
        ));
    }
}
