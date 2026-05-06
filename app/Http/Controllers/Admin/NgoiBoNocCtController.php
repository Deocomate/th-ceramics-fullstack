<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NgoiBoNocCtService;
use Illuminate\Http\Request;

class NgoiBoNocCtController extends Controller
{
    public function __construct(private readonly NgoiBoNocCtService $service) {}

    public function index(Request $request)
    {
        $status = $request->query('status', 'active');
        $products = $this->service->getAll($status);
        return view('admin.ngoi-bo-noc-ct.index', compact('products', 'status'));
    }

    public function create()
    {
        return view('admin.ngoi-bo-noc-ct.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       =>['required', 'string', 'max:255'],
            'size'       =>['nullable', 'string', 'max:255'],
            'des'        => ['nullable', 'array'],
            'des.*'      =>['nullable', 'string', 'max:500'],
            'size_des'   =>['nullable', 'array'],
            'size_des.*' =>['nullable', 'string', 'max:500'],
            'images'     => ['required', 'array'],
            'images.*'   =>['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'size_image' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->create($data);
        return redirect()->route('admin.ngoi-bo-noc-ct.index')->with('success', 'Thêm mới Ngói Bò Nóc thành công.');
    }

    public function edit(int $id)
    {
        $product = $this->service->findById($id);
        return view('admin.ngoi-bo-noc-ct.edit', compact('product'));
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'size'         =>['nullable', 'string', 'max:255'],
            'des'          =>['nullable', 'array'],
            'des.*'        =>['nullable', 'string', 'max:500'],
            'size_des'     => ['nullable', 'array'],
            'size_des.*'   => ['nullable', 'string', 'max:500'],
            'new_images'   =>['nullable', 'array'],
            'new_images.*' =>['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'size_image'   =>['nullable', 'image', 'max:5120'],
        ]);

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
        $this->service->removeImageFromJson($id, $request->input('image_path'));
        return back()->with('success', 'Đã xóa ảnh khỏi sản phẩm.');
    }
}