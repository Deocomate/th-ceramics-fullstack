<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GachTrangTri;
use App\Services\GachTrangTriService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GachTrangTriController extends Controller
{
    public function __construct(private readonly GachTrangTriService $service) {}

    public function index(): View
    {
        $records = $this->service->getAllPaginated();
        return view('admin.gach-trang-tri.index', compact('records'));
    }

    public function create(): View
    {
        return view('admin.gach-trang-tri.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          => ['nullable', 'string', 'max:500'],
        ]);

        $this->service->create($data);
        return redirect()->route('admin.gach-trang-tri.index')->with('success', 'Thêm mới thành công.');
    }

    public function edit(GachTrangTri $gachTrangTri): View
    {
        $gachTrangTri->load('dauAn');
        return view('admin.gach-trang-tri.edit', compact('gachTrangTri'));
    }

    public function update(Request $request, GachTrangTri $gachTrangTri): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          => ['nullable', 'string', 'max:500'],
        ]);

        $this->service->update($gachTrangTri->gach_trang_tri_id, $data);
        return back()->with('success', 'Cập nhật thành công.');
    }

    public function destroy(GachTrangTri $gachTrangTri): RedirectResponse
    {
        $this->service->delete($gachTrangTri->gach_trang_tri_id);
        return redirect()->route('admin.gach-trang-tri.index')->with('success', 'Đã xóa bản ghi.');
    }

    // --- Sub-routes: Dấu Ấn ---
    public function storeDauAn(Request $request, GachTrangTri $gachTrangTri): RedirectResponse
    {
        $data = $request->validate([
            'background'  =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title'       => ['required', 'string', 'max:255'],
            'location'    =>['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);
        $this->service->addDauAn($gachTrangTri->gach_trang_tri_id, $data);
        return back()->with('success', 'Thêm Dấu Ấn thành công.');
    }

    public function updateDauAn(Request $request, int $dauAnId): RedirectResponse
    {
        $data = $request->validate([
            'background'  =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title'       => ['required', 'string', 'max:255'],
            'location'    =>['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);
        $this->service->updateDauAn($dauAnId, $data);
        return back()->with('success', 'Cập nhật Dấu Ấn thành công.');
    }

    public function destroyDauAn(int $dauAnId): RedirectResponse
    {
        $this->service->deleteDauAn($dauAnId);
        return back()->with('success', 'Đã xóa Dấu Ấn.');
    }
}