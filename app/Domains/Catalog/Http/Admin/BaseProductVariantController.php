<?php

namespace App\Domains\Catalog\Http\Admin;

use App\Domains\Catalog\Infrastructure\Models\Product;
use App\Domains\Catalog\Infrastructure\Models\ProductVariant;
use App\Domains\Catalog\ProductWriter;
use App\Domains\Catalog\Services\CatalogQueryService;
use App\Domains\Media\Infrastructure\FileUploadHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

abstract class BaseProductVariantController extends Controller
{
    protected string $typeKey;

    protected string $viewPrefix;

    protected string $routePrefix;

    protected string $foreignKey;

    protected string $imageDirectory;

    public function __construct(
        protected readonly CatalogQueryService $queryService,
        protected readonly ProductWriter $writer,
    ) {}

    public function index(Request $request): View
    {
        $status = $request->query('status', 'active');
        $productId = $request->query('product_id') ? (int) $request->query('product_id') : null;

        $products = Product::query()
            ->with('publicId')
            ->where('type_key', $this->typeKey)
            ->where('is_delete', false)
            ->get();

        $selectedProduct = $productId ? $this->queryService->find($this->typeKey, $productId) : null;

        $query = ProductVariant::query()
            ->with(['publicId', 'product.publicId'])
            ->where('is_default', false)
            ->whereHas('product', function ($q) use ($selectedProduct) {
                $q->where('type_key', $this->typeKey);
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

        return view("{$this->viewPrefix}.index", compact('phanLoais', 'mauSacs', 'products', 'status', 'selectedProduct'));
    }

    public function store(Request $request): RedirectResponse
    {
        $productId = (int) $request->input($this->foreignKey, $request->input('product_id'));
        $product = $this->queryService->find($this->typeKey, $productId);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50'],
            'price' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];

        $data = $request->validate($rules);
        $data['sku'] = $data['code'];

        if ($request->hasFile('image')) {
            $data['image'] = FileUploadHelper::upload($request->file('image'), $this->imageDirectory);
        }

        try {
            $this->writer->saveVariant($product, $data);

            return back()->with('success', 'Đã thêm phân loại thành công.');
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['code' => $e->getMessage()]);
        }
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $variant = $this->findVariant($id);

        $product = $variant->product;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50'],
            'price' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];

        $data = $request->validate($rules);
        $data['sku'] = $data['code'];

        if ($request->hasFile('image')) {
            if ($variant->image) {
                FileUploadHelper::delete($variant->image);
            }
            $data['image'] = FileUploadHelper::upload($request->file('image'), $this->imageDirectory);
        }

        try {
            $this->writer->saveVariant($product, $data, $variant);

            return back()->with('success', 'Cập nhật phân loại thành công.');
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['code' => $e->getMessage()]);
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $variant = $this->findVariant($id);

        $this->writer->deleteVariant($variant);

        return back()->with('success', 'Đã tạm ẩn phân loại.');
    }

    public function restore(int $id): RedirectResponse
    {
        $variant = $this->findVariant($id);

        $variant->update(['is_delete' => false]);

        return back()->with('success', 'Khôi phục phân loại thành công.');
    }

    protected function findVariant(int $publicId): ProductVariant
    {
        return ProductVariant::query()
            ->whereHas('product', fn ($q) => $q->where('type_key', $this->typeKey))
            ->whereHas('publicId', fn ($q) => $q->where('type_key', $this->typeKey)->where('public_id', $publicId))
            ->firstOrFail();
    }
}
