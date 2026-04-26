<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GachCoBatTrang;
use App\Services\GachCoBatTrangService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GachCoBatTrangController extends Controller
{
    public function __construct(private readonly GachCoBatTrangService $service) {}

    public function index(): View
    {
        $records = $this->service->getAllPaginated();
        return view('admin.gach-co-bat-trang.index', compact('records'));
    }

    public function create(): View
    {
        return view('admin.gach-co-bat-trang.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          => ['nullable', 'string', 'max:500'],
        ]);

        $this->service->create($data);
        return redirect()->route('admin.gach-co-bat-trang.index')->with('success', 'Thêm mới thành công.');
    }

    public function edit(GachCoBatTrang $gachCoBatTrang): View
    {
        $gachCoBatTrang->load('anh');
        return view('admin.gach-co-bat-trang.edit', compact('gachCoBatTrang'));
    }

    public function update(Request $request, GachCoBatTrang $gachCoBatTrang): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          =>['nullable', 'string', 'max:500'],
        ]);

        $this->service->update($gachCoBatTrang->gach_co_bat_trang_id, $data);
        return back()->with('success', 'Cập nhật thành công.');
    }

    public function destroy(GachCoBatTrang $gachCoBatTrang): RedirectResponse
    {
        $this->service->delete($gachCoBatTrang->gach_co_bat_trang_id);
        return redirect()->route('admin.gach-co-bat-trang.index')->with('success', 'Đã xóa bản ghi.');
    }

    public function storeAnh(Request $request, GachCoBatTrang $gachCoBatTrang): RedirectResponse
    {
        $data = $request->validate(['image' =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']]);
        $this->service->addAnh($gachCoBatTrang->gach_co_bat_trang_id, $data);
        return back()->with('success', 'Thêm ảnh thành công.');
    }

    public function destroyAnh(int $anhId): RedirectResponse
    {
        $this->service->deleteAnh($anhId);
        return back()->with('success', 'Đã xóa ảnh.');
    }
}