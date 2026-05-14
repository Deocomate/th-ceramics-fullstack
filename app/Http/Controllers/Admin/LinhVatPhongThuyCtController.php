<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LinhVatPhongThuyCtService;
use Illuminate\Http\Request;
use InvalidArgumentException;

class LinhVatPhongThuyCtController extends Controller
{
    public function __construct(private readonly LinhVatPhongThuyCtService $service) {}

    public function index(Request $request)
    {
        $status = $request->query('status', 'active');
        $products = $this->service->getAll($status);

        return view('admin.linh-vat-phong-thuy-ct.index', compact('products', 'status'));
    }

    public function create()
    {
        return view('admin.linh-vat-phong-thuy-ct.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:0'],
            'size' => ['nullable', 'string', 'max:255'],
            'des' => ['nullable', 'array'],
            'des.*' => ['nullable', 'string', 'max:500'],
            'size_des' => ['nullable', 'array'],
            'size_des.*' => ['nullable', 'string', 'max:500'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'size_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        try {
            $this->service->create($data);

            return redirect()->route('admin.linh-vat-phong-thuy-ct.index')
                ->with('success', 'Thêm mới Linh Vật Phong Thủy thành công.');
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['code' => $e->getMessage()]);
        }
    }

    public function edit(int $id)
    {
        $product = $this->service->findById($id);

        return view('admin.linh-vat-phong-thuy-ct.edit', compact('product'));
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:0'],
            'size' => ['nullable', 'string', 'max:255'],
            'des' => ['nullable', 'array'],
            'des.*' => ['nullable', 'string', 'max:500'],
            'size_des' => ['nullable', 'array'],
            'size_des.*' => ['nullable', 'string', 'max:500'],
            'new_images' => ['nullable', 'array'],
            'new_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'size_image' => ['nullable', 'image', 'max:5120'],
        ]);

        try {
            $this->service->update($id, $data);

            return back()->with('success', 'Cập nhật sản phẩm thành công.');
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['code' => $e->getMessage()]);
        }
    }

    public function destroy(int $id)
    {
        $this->service->toggleStatus($id, 1);

        return back()->with('success', 'Đã tạm ẩn sản phẩm thành công.');
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
