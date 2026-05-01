<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PhuKienNgoiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PhuKienNgoiController extends Controller
{
    public function __construct(private readonly PhuKienNgoiService $service) {}

    public function index(): View
    {
        $phuKienNgoi = $this->service->getFirstRecord();
        return view('admin.phu-kien-ngoi.edit', compact('phuKienNgoi'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'new_images'     => ['nullable', 'array'],
            'new_images.*'   =>['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->update($data);
        return back()->with('success', 'Cập nhật Phụ Kiện Ngói thành công.');
    }

    public function destroyImage(Request $request): RedirectResponse
    {
        $request->validate(['image_path' => ['required', 'string']]);
        $this->service->removeImageFromJson($request->input('image_path'));
        return back()->with('success', 'Đã xóa ảnh khỏi danh sách.');
    }
}