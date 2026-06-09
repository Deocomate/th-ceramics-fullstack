<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GiaiThuongThanhTuuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GiaiThuongThanhTuuController extends Controller
{
    public function __construct(private readonly GiaiThuongThanhTuuService $service) {}

    public function index(): View
    {
        $giaiThuong = $this->service->getAll();

        return view('admin.giai-thuong-thanh-tuu.index', compact('giaiThuong'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'des' => ['required', 'string'],
        ], [
            'image.max' => 'Hình ảnh không được vượt quá 5MB.',
            'des.required' => 'Mô tả là bắt buộc.',
        ]);

        $this->service->store($data);

        return back()->with('success', 'Thêm Giải thưởng / Thành tựu thành công.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'des' => ['required', 'string'],
        ], [
            'image.max' => 'Hình ảnh không được vượt quá 5MB.',
            'des.required' => 'Mô tả là bắt buộc.',
        ]);

        $this->service->update($id, $data);

        return back()->with('success', 'Cập nhật Giải thưởng / Thành tựu thành công.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->destroy($id);

        return back()->with('success', 'Đã xóa Giải thưởng / Thành tựu khỏi danh sách.');
    }
}
