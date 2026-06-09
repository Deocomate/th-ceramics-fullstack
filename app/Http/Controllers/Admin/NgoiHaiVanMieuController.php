<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NgoiHaiVanMieuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NgoiHaiVanMieuController extends Controller
{
    public function __construct(private readonly NgoiHaiVanMieuService $service) {}

    public function index(): View
    {
        $ngoiHaiVanMieu = $this->service->getFirstRecord();

        return view('admin.ngoi-hai-van-mieu.edit', compact('ngoiHaiVanMieu'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title1' => ['required', 'string', 'max:50'],
            'thumbnail1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title2' => ['required', 'string', 'max:50'],
            'thumbnail2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title3' => ['required', 'string', 'max:50'],
            'thumbnail3' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video' => ['nullable', 'string', 'max:500'],
            'cong_doan_images' => ['nullable', 'array'],
            'cong_doan_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->update($data);

        return back()->with('success', 'Cập nhật thông tin Ngói Hài Văn Miếu thành công.');
    }

    public function destroyCongDoanImage(Request $request): RedirectResponse
    {
        $request->validate(['image_path' => ['required', 'string']]);
        $this->service->removeImageFromJson($request->input('image_path'));

        return back()->with('success', 'Đã xóa ảnh công đoạn chế tác khỏi danh sách.');
    }
}
