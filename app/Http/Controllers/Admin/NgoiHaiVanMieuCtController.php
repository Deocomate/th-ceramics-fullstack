<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNgoiHaiVanMieuCtRequest;
use App\Http\Requests\UpdateNgoiHaiVanMieuCtRequest;
use App\Services\NgoiHaiVanMieuCtService;
use Illuminate\Http\Request;

class NgoiHaiVanMieuCtController extends Controller
{
    public function __construct(private readonly NgoiHaiVanMieuCtService $service) {}

    public function index(Request $request)
    {
        $status = $request->query('status', 'active');
        $products = $this->service->getAll($status);

        return view('admin.ngoi-hai-van-mieu-ct.index', compact('products', 'status'));
    }

    public function create()
    {
        return view('admin.ngoi-hai-van-mieu-ct.create');
    }

    public function store(StoreNgoiHaiVanMieuCtRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.ngoi-hai-van-mieu-ct.index')
            ->with('success', 'Thêm mới Ngói Hài Văn Miếu thành công.');
    }

    public function edit(int $id)
    {
        $product = $this->service->findById($id);

        return view('admin.ngoi-hai-van-mieu-ct.edit', compact('product'));
    }

    public function update(UpdateNgoiHaiVanMieuCtRequest $request, int $id)
    {
        $this->service->update($id, $request->validated());

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
}
