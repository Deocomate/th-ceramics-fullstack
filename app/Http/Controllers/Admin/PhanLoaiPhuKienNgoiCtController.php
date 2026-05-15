<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhuKienNgoiCt;
use App\Services\PhanLoaiPhuKienNgoiCtService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class PhanLoaiPhuKienNgoiCtController extends Controller
{
    public function __construct(private readonly PhanLoaiPhuKienNgoiCtService $service) {}

    public function index(Request $request)
    {
        $status = $request->query('status', 'active');
        $categoryType = $this->categoryType($request);
        $productId = $request->integer('product_id') ?: null;

        $phanLoais = $this->service->getAll($status, $productId, $categoryType);
        $products = PhuKienNgoiCt::query()
            ->where('category_type', $categoryType)
            ->where('is_delete', 0)
            ->get();
        $selectedProduct = $productId ? PhuKienNgoiCt::query()
            ->where('category_type', $categoryType)
            ->find($productId) : null;
        $categoryLabel = PhuKienNgoiCt::categoryLabel($categoryType);

        return view('admin.phan-loai-phu-kien-ngoi-ct.index', compact(
            'phanLoais',
            'products',
            'status',
            'selectedProduct',
            'categoryType',
            'categoryLabel'
        ));
    }

    public function store(Request $request)
    {
        $categoryType = $this->categoryType($request);
        $data = $this->validatedVariantData($request, $categoryType);

        try {
            $this->service->create($data);

            return back()->with('success', 'Đã thêm phân loại thành công.');
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['code' => $e->getMessage()]);
        }
    }

    public function update(Request $request, int $id)
    {
        $categoryType = $this->categoryType($request);
        $data = $this->validatedVariantData($request, $categoryType);

        try {
            $this->service->update($id, $data);

            return back()->with('success', 'Cập nhật phân loại thành công.');
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['code' => $e->getMessage()]);
        }
    }

    public function destroy(int $id)
    {
        $this->service->toggleStatus($id, 1);

        return back()->with('success', 'Đã tạm ẩn phân loại.');
    }

    public function restore(int $id)
    {
        $this->service->toggleStatus($id, 0);

        return back()->with('success', 'Khôi phục phân loại thành công.');
    }

    private function validatedVariantData(Request $request, string $categoryType): array
    {
        return $request->validate([
            'category_type' => ['required', Rule::in([PhuKienNgoiCt::TYPE_BO_NOC, PhuKienNgoiCt::TYPE_CHU_VAN])],
            'phu_kien_ngoi_ct_id' => [
                'required',
                Rule::exists('phu_kien_ngoi_ct', 'phu_kien_ngoi_ct_id')
                    ->where('category_type', $categoryType),
            ],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50'],
            'price' => ['required', 'integer', 'min:0'],
        ]);
    }

    private function categoryType(Request $request): string
    {
        $categoryType = $request->input('category_type', $request->query('category_type', PhuKienNgoiCt::TYPE_BO_NOC));

        return $categoryType === PhuKienNgoiCt::TYPE_CHU_VAN ? PhuKienNgoiCt::TYPE_CHU_VAN : PhuKienNgoiCt::TYPE_BO_NOC;
    }
}
