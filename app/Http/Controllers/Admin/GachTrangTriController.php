<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GachTrangTriService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GachTrangTriController extends Controller
{
    public function __construct(private readonly GachTrangTriService $service) {}

    public function index(): View
    {
        $gachTrangTri = $this->service->getFirstRecord();
        return view('admin.gach-trang-tri.edit', compact('gachTrangTri'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          =>['nullable', 'string', 'max:500'],
            'ung_dung_da_dang' => ['nullable', 'array'],
            'ung_dung_da_dang.*.title' => ['nullable', 'string', 'max:255'],
            'ung_dung_da_dang.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'cong_doan_order' => ['nullable', 'array'],
            'cong_doan_order.*' => ['string'],
            'cong_doan_images'   => ['nullable', 'array'],
            'cong_doan_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->update($data);
        return back()->with('success', 'Cập nhật cấu hình thành công.');
    }

    public function destroyCongDoanImage(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate(['image_path' => ['required', 'string']]);
        $this->service->removeImageFromJson($request->input('image_path'));
        return back()->with('success', 'Đã xóa ảnh công đoạn chế tác khỏi danh sách.');
    }
}
