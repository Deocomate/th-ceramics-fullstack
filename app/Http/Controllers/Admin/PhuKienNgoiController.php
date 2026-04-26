<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhuKienNgoi;
use App\Services\PhuKienNgoiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PhuKienNgoiController extends Controller
{
    public function __construct(private readonly PhuKienNgoiService $service) {}

    public function index(): View
    {
        $records = $this->service->getAllPaginated();
        return view('admin.phu-kien-ngoi.index', compact('records'));
    }

    public function create(): View
    {
        return view('admin.phu-kien-ngoi.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'images'         => ['nullable', 'array'],
            'images.*'       =>['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->create($data);
        return redirect()->route('admin.phu-kien-ngoi.index')->with('success', 'Thêm mới thành công.');
    }

    public function edit(PhuKienNgoi $phuKienNgoi): View
    {
        return view('admin.phu-kien-ngoi.edit', compact('phuKienNgoi'));
    }

    public function update(Request $request, PhuKienNgoi $phuKienNgoi): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'new_images'     => ['nullable', 'array'],
            'new_images.*'   =>['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->update($phuKienNgoi->phu_kien_ngoi_id, $data);
        return back()->with('success', 'Cập nhật thành công.');
    }

    public function destroy(PhuKienNgoi $phuKienNgoi): RedirectResponse
    {
        $this->service->delete($phuKienNgoi->phu_kien_ngoi_id);
        return redirect()->route('admin.phu-kien-ngoi.index')->with('success', 'Đã xóa bản ghi.');
    }

    public function destroyImage(Request $request, PhuKienNgoi $phuKienNgoi): RedirectResponse
    {
        $request->validate(['image_path' => ['required', 'string']]);
        $this->service->removeImageFromJson($phuKienNgoi->phu_kien_ngoi_id, $request->input('image_path'));
        return back()->with('success', 'Đã xóa ảnh khỏi thư viện.');
    }
}