<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GachHoaThongGioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GachHoaThongGioController extends Controller
{
    public function __construct(private readonly GachHoaThongGioService $service) {}

    public function index(): View
    {
        $gachHoaThongGio = $this->service->getFirstRecord();
        return view('admin.gach-hoa-thong-gio.edit', compact('gachHoaThongGio'));
    }


    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video' => ['nullable', 'string', 'max:500'],
            'new_images'   => ['nullable', 'array'],
            'new_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'cong_doan_images'   => ['nullable', 'array'],
            'cong_doan_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->update($data);
        return back()->with('success', 'Cập nhật thành công.');
    }

    // --- Sub-routes: Ảnh ---
    public function storeAnh(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']
        ]);
        $this->service->addAnh($data);
        return back()->with('success', 'Thêm ảnh vào thư viện thành công.');
    }

    public function destroyAnh(int $anhId): RedirectResponse
    {
        $this->service->deleteAnh($anhId);
        return back()->with('success', 'Đã xóa ảnh khỏi thư viện.');
    }

    // --- Sub-routes: Giá Trị ---
    public function storeGiaTri(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'background'   => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image'        => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title'        => ['required', 'string', 'max:50'],
            'desscription' => ['required', 'string'],
        ]);
        $this->service->addGiaTri($data);
        return back()->with('success', 'Thêm giá trị thành công.');
    }

    public function updateGiaTri(Request $request, int $giaTriId): RedirectResponse
    {
        $data = $request->validate([
            'background'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title'        => ['required', 'string', 'max:50'],
            'desscription' => ['required', 'string'],
        ]);
        $this->service->updateGiaTri($giaTriId, $data);
        return back()->with('success', 'Cập nhật giá trị thành công.');
    }

    public function destroyGiaTri(int $giaTriId): RedirectResponse
    {
        $this->service->deleteGiaTri($giaTriId);
        return back()->with('success', 'Đã xóa giá trị.');
    }

    public function destroyCongDoanImage(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate(['image_path' => ['required', 'string']]);
        $this->service->removeImageFromJson($request->input('image_path'));
        return back()->with('success', 'Đã xóa ảnh công đoạn chế tác khỏi danh sách.');
    }
}
