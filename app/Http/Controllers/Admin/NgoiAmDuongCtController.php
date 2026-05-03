<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNgoiAmDuongCtRequest;
use App\Http\Requests\UpdateNgoiAmDuongCtRequest;
use App\Services\NgoiAmDuongCtService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class NgoiAmDuongCtController extends Controller
{
    public function __construct(
        private readonly NgoiAmDuongCtService $service
    ) {}

    public function index(Request $request): View
    {
        $status = $request->query('status', 'active');
        $products = $this->service->getAll($status);
        
        return view('admin.ngoi-am-duong-ct.index', compact('products', 'status'));
    }

    public function create(): View
    {
        return view('admin.ngoi-am-duong-ct.create');
    }

    public function store(StoreNgoiAmDuongCtRequest $request): RedirectResponse
    {
        try {
            $this->service->create($request->validated());
            return redirect()->route('admin.ngoi-am-duong-ct.index')
                ->with('success', 'Thêm mới chi tiết Ngói Âm Dương thành công.');
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['code' => $e->getMessage()]);
        }
    }

    public function edit(int $id): View
    {
        $product = $this->service->findById($id);
        return view('admin.ngoi-am-duong-ct.edit', compact('product'));
    }

    public function update(UpdateNgoiAmDuongCtRequest $request, int $id): RedirectResponse
    {
        try {
            $this->service->update($id, $request->validated());
            return back()->with('success', 'Cập nhật sản phẩm thành công.');
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['code' => $e->getMessage()]);
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->deleteProduct($id);
        return back()->with('success', 'Đã tạm ẩn sản phẩm thành công.');
    }

    public function restore(int $id): RedirectResponse
    {
        $this->service->restoreProduct($id);
        return back()->with('success', 'Khôi phục sản phẩm thành công.');
    }

    public function destroyImage(Request $request, int $id): RedirectResponse
    {
        $request->validate(['image_path' => ['required', 'string']]);
        $this->service->removeImageFromJson($id, $request->input('image_path'));
        return back()->with('success', 'Đã xóa ảnh khỏi sản phẩm.');
    }
}