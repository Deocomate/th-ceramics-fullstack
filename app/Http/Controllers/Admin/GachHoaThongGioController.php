<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GachHoaThongGio;
use App\Services\GachHoaThongGioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GachHoaThongGioController extends Controller
{
    public function __construct(private readonly GachHoaThongGioService $service) {}

    public function index(): View
    {
        $records = $this->service->getAllPaginated();
        return view('admin.gach-hoa-thong-gio.index', compact('records'));
    }

    public function create(): View
    {
        return view('admin.gach-hoa-thong-gio.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'image' =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video' => ['nullable', 'string', 'max:500'],
        ]);

        $this->service->create($data);
        return redirect()->route('admin.gach-hoa-thong-gio.index')->with('success', 'Thêm mới thành công.');
    }

    public function edit(GachHoaThongGio $gachHoaThongGio): View
    {
        $gachHoaThongGio->load(['anh', 'giaTri']);
        return view('admin.gach-hoa-thong-gio.edit', compact('gachHoaThongGio'));
    }

    public function update(Request $request, GachHoaThongGio $gachHoaThongGio): RedirectResponse
    {
        $data = $request->validate([
            'image' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video' => ['nullable', 'string', 'max:500'],
        ]);

        $this->service->update($gachHoaThongGio->gach_hoa_thong_gio_id, $data);
        return back()->with('success', 'Cập nhật thành công.');
    }

    public function destroy(GachHoaThongGio $gachHoaThongGio): RedirectResponse
    {
        $this->service->delete($gachHoaThongGio->gach_hoa_thong_gio_id);
        return redirect()->route('admin.gach-hoa-thong-gio.index')->with('success', 'Đã xóa bản ghi.');
    }

    // --- Sub-routes: Ảnh ---
    public function storeAnh(Request $request, GachHoaThongGio $gachHoaThongGio): RedirectResponse
    {
        $data = $request->validate(['image' =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']]);
        $this->service->addAnh($gachHoaThongGio->gach_hoa_thong_gio_id, $data);
        return back()->with('success', 'Thêm ảnh thành công.');
    }

    public function destroyAnh(int $anhId): RedirectResponse
    {
        $this->service->deleteAnh($anhId);
        return back()->with('success', 'Đã xóa ảnh.');
    }

    // --- Sub-routes: Giá Trị ---
    public function storeGiaTri(Request $request, GachHoaThongGio $gachHoaThongGio): RedirectResponse
    {
        $data = $request->validate([
            'background'   =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image'        =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title'        =>['required', 'string', 'max:50'],
            'desscription' => ['required', 'string'],
        ]);
        $this->service->addGiaTri($gachHoaThongGio->gach_hoa_thong_gio_id, $data);
        return back()->with('success', 'Thêm giá trị thành công.');
    }

    public function updateGiaTri(Request $request, int $giaTriId): RedirectResponse
    {
        $data = $request->validate([
            'background'   =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image'        =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title'        =>['required', 'string', 'max:50'],
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
}