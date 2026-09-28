<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Catalog\Http\Admin\BaseProductItemController;
use App\Domains\Media\FileUploadHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use InvalidArgumentException;

class PhuKienNgoiCtController extends BaseProductItemController
{
    public const TYPE_BO_NOC = 'ngoi_bo_noc';
    public const TYPE_CHU_VAN = 'chu_van';

    protected string $typeKey = 'phu_kien_ngoi_ct';
    protected string $viewPrefix = 'admin.phu-kien-ngoi-ct';
    protected string $routePrefix = 'admin.phu-kien-ngoi-ct';
    protected string $itemLabel = 'Phụ Kiện Ngói';
    protected string $imageDirectory = 'phu_kien_ngoi_ct';
    protected string $sizeDirectory = 'phu_kien_ngoi_ct/sizes';

    public static function categoryLabel(string $type): string
    {
        return $type === self::TYPE_CHU_VAN ? 'Bờ Nóc Chữ Vạn' : 'Ngói Bờ Nóc';
    }

    public static function categoryCodePrefix(?string $type): string
    {
        return $type === self::TYPE_CHU_VAN ? 'BNCV-' : 'NBN-';
    }

    public function index(Request $request): View
    {
        $status = $request->query('status', 'active');
        $categoryType = $this->categoryType($request);
        $products = $this->queryService->all($this->typeKey, $status, $categoryType);
        $categoryLabel = self::categoryLabel($categoryType);

        return view('admin.phu-kien-ngoi-ct.index', compact('products', 'status', 'categoryType', 'categoryLabel'));
    }

    public function create(Request $request): View
    {
        $categoryType = $this->categoryType($request);
        $categoryLabel = self::categoryLabel($categoryType);

        $copiedProduct = null;
        if ($request->filled('copy_from')) {
            $copiedProduct = $this->copyService->getProductDetailForCopy('phu-kien-ngoi-ct', (int) $request->query('copy_from'));
        }

        return view('admin.phu-kien-ngoi-ct.create', compact('categoryType', 'categoryLabel', 'copiedProduct'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $this->validateStore($request);

            if ($request->hasFile('size_image')) {
                $data['size_image'] = FileUploadHelper::upload($request->file('size_image'), $this->sizeDirectory);
            }

            $product = $this->writer->create($this->typeKey, $data);
            $this->storeUploadsOnProduct($request, $product);

            $categoryType = $data['category_type'] ?? self::TYPE_BO_NOC;

            return redirect()
                ->route('admin.phu-kien-ngoi-ct.index', ['category_type' => $categoryType])
                ->with('success', 'Thêm mới '.self::categoryLabel($categoryType).' thành công.');
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['code' => $e->getMessage()]);
        }
    }

    public function edit(int $id): View
    {
        $product = $this->queryService->find($this->typeKey, $id);
        $categoryType = $product->category_type ?? self::TYPE_BO_NOC;
        $categoryLabel = self::categoryLabel($categoryType);

        return view('admin.phu-kien-ngoi-ct.edit', compact('product', 'categoryType', 'categoryLabel'));
    }

    protected function customStoreRules(Request $request): array
    {
        return [
            'category_type' => ['required', Rule::in([self::TYPE_BO_NOC, self::TYPE_CHU_VAN])],
        ];
    }

    private function categoryType(Request $request): string
    {
        $categoryType = $request->query('category_type', self::TYPE_BO_NOC);

        return $categoryType === self::TYPE_CHU_VAN ? self::TYPE_CHU_VAN : self::TYPE_BO_NOC;
    }
}
