<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GachCoBatTrangService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GachCoBatTrangController extends Controller
{
    public function __construct(private readonly GachCoBatTrangService $service) {}

    public function index(): View
    {
        $gachCoBatTrang = $this->service->getFirstRecord();
        return view('admin.gach-co-bat-trang.edit', compact('gachCoBatTrang'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          => ['nullable', 'string', 'max:500'],
            'new_images'     => ['nullable', 'array'],
            'new_images.*'   =>['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'cong_doan_images'   => ['nullable', 'array'],
            'cong_doan_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->update($data);
        return back()->with('success', 'Cập nhật cấu hình thành công.');
    }

    public function destroyAnh(int $anhId): RedirectResponse
    {
        $this->service->deleteAnh($anhId);
        return back()->with('success', 'Đã xóa ảnh khỏi thư viện.');
    }

    public function destroyCongDoanImage(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate(['image_path' => ['required', 'string']]);
        $this->service->removeImageFromJson($request->input('image_path'));
        return back()->with('success', 'Đã xóa ảnh công đoạn chế tác khỏi danh sách.');
    }
}