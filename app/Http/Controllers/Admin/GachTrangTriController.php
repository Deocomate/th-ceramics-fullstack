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
            'cong_doan_images'   => ['nullable', 'array'],
            'cong_doan_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->update($data);
        return back()->with('success', 'Cập nhật cấu hình thành công.');
    }

    public function storeDauAn(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'background'  =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title'       => ['required', 'string', 'max:255'],
            'location'    => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);
        
        $this->service->addDauAn($data);
        return back()->with('success', 'Thêm Dấu Ấn thành công.');
    }

    public function updateDauAn(Request $request, int $dauAnId): RedirectResponse
    {
        $data = $request->validate([
            'background'  =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title'       =>['required', 'string', 'max:255'],
            'location'    =>['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);
        
        $this->service->updateDauAn($dauAnId, $data);
        return back()->with('success', 'Cập nhật Dấu Ấn thành công.');
    }

    public function destroyDauAn(int $dauAnId): RedirectResponse
    {
        $this->service->deleteDauAn($dauAnId);
        return back()->with('success', 'Đã xóa Dấu Ấn khỏi danh sách.');
    }

    public function destroyCongDoanImage(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate(['image_path' => ['required', 'string']]);
        $this->service->removeImageFromJson($request->input('image_path'));
        return back()->with('success', 'Đã xóa ảnh công đoạn chế tác khỏi danh sách.');
    }
}