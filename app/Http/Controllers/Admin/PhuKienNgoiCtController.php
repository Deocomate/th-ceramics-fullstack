<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhuKienNgoiCt;
use App\Services\PhuKienNgoiCtService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PhuKienNgoiCtController extends Controller
{
    public function __construct(private readonly PhuKienNgoiCtService $service) {}

    public function index(Request $request)
    {
        $status = $request->query('status', 'active');
        $categoryType = $this->categoryType($request);
        $products = $this->service->getAll($status, $categoryType);
        $categoryLabel = PhuKienNgoiCt::categoryLabel($categoryType);

        return view('admin.phu-kien-ngoi-ct.index', compact('products', 'status', 'categoryType', 'categoryLabel'));
    }

    public function create(Request $request)
    {
        $categoryType = $this->categoryType($request);
        $categoryLabel = PhuKienNgoiCt::categoryLabel($categoryType);

        return view('admin.phu-kien-ngoi-ct.create', compact('categoryType', 'categoryLabel'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedProductData($request, true);
        $this->service->create($data);

        return redirect()
            ->route('admin.phu-kien-ngoi-ct.index', ['category_type' => $data['category_type']])
            ->with('success', 'Thêm mới '.PhuKienNgoiCt::categoryLabel($data['category_type']).' thành công.');
    }

    public function edit(int $id)
    {
        $product = $this->service->findById($id);
        $categoryType = $product->category_type;
        $categoryLabel = PhuKienNgoiCt::categoryLabel($categoryType);

        return view('admin.phu-kien-ngoi-ct.edit', compact('product', 'categoryType', 'categoryLabel'));
    }

    public function update(Request $request, int $id)
    {
        $product = $this->service->findById($id);
        $data = $this->validatedProductData($request, false, $product->category_type);
        $this->service->update($id, $data);

        return back()->with('success', 'Cập nhật thành công.');
    }

    public function destroy(int $id)
    {
        $this->service->toggleStatus($id, 1);

        return back()->with('success', 'Đã tạm ẩn sản phẩm.');
    }

    public function restore(int $id)
    {
        $this->service->toggleStatus($id, 0);

        return back()->with('success', 'Khôi phục sản phẩm thành công.');
    }

    public function destroyImage(Request $request, int $id)
    {
        $request->validate(['image_path' => ['required', 'string']]);
        $this->service->removeImageFromJson($id, $request->input('image_path'));

        return back()->with('success', 'Đã xóa ảnh khỏi sản phẩm.');
    }

    private function validatedProductData(Request $request, bool $create, ?string $fallbackCategoryType = null): array
    {
        return $request->validate([
            'category_type' => ['required', Rule::in([PhuKienNgoiCt::TYPE_BO_NOC, PhuKienNgoiCt::TYPE_CHU_VAN])],
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:100'],
            'size' => ['nullable', 'string', 'max:255'],
            'des' => ['nullable', 'array'],
            'des.*' => ['nullable', 'string', 'max:500'],
            'size_des' => ['nullable', 'array'],
            'size_des.*' => ['nullable', 'string', 'max:500'],
            $create ? 'images' : 'new_images' => [$create ? 'required' : 'nullable', 'array'],
            ($create ? 'images' : 'new_images').'.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'size_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]) + ['category_type' => $fallbackCategoryType ?? $request->input('category_type')];
    }

    private function categoryType(Request $request): string
    {
        $categoryType = $request->query('category_type', PhuKienNgoiCt::TYPE_BO_NOC);

        return $categoryType === PhuKienNgoiCt::TYPE_CHU_VAN ? PhuKienNgoiCt::TYPE_CHU_VAN : PhuKienNgoiCt::TYPE_BO_NOC;
    }
}
