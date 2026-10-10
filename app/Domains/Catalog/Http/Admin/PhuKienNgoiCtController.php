<?php

namespace App\Domains\Catalog\Http\Admin;

use App\Domains\Catalog\Domain\PhuKienNgoiCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use InvalidArgumentException;

class PhuKienNgoiCtController extends BaseProductItemController
{
    public const TYPE_BO_NOC = PhuKienNgoiCategory::TYPE_BO_NOC;

    public const TYPE_CHU_VAN = PhuKienNgoiCategory::TYPE_CHU_VAN;

    protected string $typeKey = 'phu_kien_ngoi_ct';

    protected string $viewPrefix = 'admin.catalog.phu-kien-ngoi-ct';

    protected string $routePrefix = 'admin.phu-kien-ngoi-ct';

    protected string $itemLabel = 'Phụ Kiện Ngói';

    protected string $imageDirectory = 'phu_kien_ngoi_ct';

    protected string $sizeDirectory = 'phu_kien_ngoi_ct/sizes';

    public static function categoryLabel(string $type): string
    {
        return PhuKienNgoiCategory::label($type);
    }

    public static function categoryCodePrefix(?string $type): string
    {
        return PhuKienNgoiCategory::codePrefix($type);
    }

    public function index(Request $request): View
    {
        $status = $request->query('status', 'active');
        $categoryType = $this->categoryType($request);
        $products = $this->queryService->all($this->typeKey, $status, $categoryType);
        $categoryLabel = self::categoryLabel($categoryType);

        return view('admin.catalog.phu-kien-ngoi-ct.index', compact('products', 'status', 'categoryType', 'categoryLabel'));
    }

    public function create(Request $request): View
    {
        $categoryType = $this->categoryType($request);
        $categoryLabel = self::categoryLabel($categoryType);

        $copiedProduct = null;
        if ($request->filled('copy_from')) {
            $copiedProduct = $this->copyService->getProductDetailForCopy('phu-kien-ngoi-ct', (int) $request->query('copy_from'));
        }

        return view('admin.catalog.phu-kien-ngoi-ct.create', compact('categoryType', 'categoryLabel', 'copiedProduct'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $this->validateStore($request);
            $categoryType = $this->categoryType($request);
            $data['category_type'] = $categoryType;

            $product = $this->writer->create($this->typeKey, $data);
            $this->storeUploadsOnProduct($request, $product);

            return redirect()
                ->route("{$this->routePrefix}.index", ['category_type' => $categoryType])
                ->with('success', "Thêm {$this->itemLabel} thành công.");
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(int $id): View
    {
        $product = $this->queryService->find($this->typeKey, $id);
        $categoryType = $product->category_type ?? self::TYPE_BO_NOC;
        $categoryLabel = self::categoryLabel($categoryType);

        return view("{$this->viewPrefix}.edit", compact('product', 'categoryType', 'categoryLabel'));
    }

    protected function customStoreRules(Request $request): array
    {
        return [
            'category_type' => ['nullable', 'string', Rule::in([self::TYPE_BO_NOC, self::TYPE_CHU_VAN])],
        ];
    }

    private function categoryType(Request $request): string
    {
        return $request->query('category_type') === self::TYPE_CHU_VAN || $request->input('category_type') === self::TYPE_CHU_VAN
            ? self::TYPE_CHU_VAN
            : self::TYPE_BO_NOC;
    }
}
