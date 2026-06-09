<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NgoiHaiCoCt;
use App\Services\MauSacNgoiHaiCoCtService;
use Illuminate\Http\Request;
use InvalidArgumentException;

class MauSacNgoiHaiCoCtController extends Controller
{
    public function __construct(private readonly MauSacNgoiHaiCoCtService $service) {}

    public function index(Request $request)
    {
        $status = $request->query('status', 'active');
        $productId = $request->query('product_id');

        $mauSacs = $this->service->getAll($status, $productId);
        $products = NgoiHaiCoCt::query()->where('is_delete', 0)->get();
        $selectedProduct = $productId ? NgoiHaiCoCt::query()->find($productId) : null;

        return view('admin.mau-sac-ngoi-hai-co-ct.index', compact('mauSacs', 'products', 'status', 'selectedProduct'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ngoi_hai_co_ct_id' => ['required', 'exists:ngoi_hai_co_ct,ngoi_hai_co_ct_id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50'],
            'price' => ['required', 'integer', 'min:0'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        try {
            $this->service->create($data);

            return back()->with('success', 'Đã thêm biến thể  Màu sắc thành công.');
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['code' => $e->getMessage()]);
        }
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'ngoi_hai_co_ct_id' => ['required', 'exists:ngoi_hai_co_ct,ngoi_hai_co_ct_id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50'],
            'price' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        try {
            $this->service->update($id, $data);

            return back()->with('success', 'Cập nhật Màu sắc thành công.');
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['code' => $e->getMessage()]);
        }
    }

    public function destroy(int $id)
    {
        $this->service->toggleStatus($id, 1);

        return back()->with('success', 'Đã tạm ẩn màu sắc.');
    }

    public function restore(int $id)
    {
        $this->service->toggleStatus($id, 0);

        return back()->with('success', 'Khôi phục màu sắc thành công.');
    }
}
