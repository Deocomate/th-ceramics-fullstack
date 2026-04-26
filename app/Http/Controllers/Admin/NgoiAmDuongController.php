<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NgoiAmDuong;
use App\Services\NgoiAmDuongService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NgoiAmDuongController extends Controller
{
    public function __construct(private readonly NgoiAmDuongService $service) {}

    // ─── QUẢN LÝ BẢNG CHÍNH ──────────────────────────────────────────────────

    public function index(): View
    {
        $records = $this->service->getAllPaginated();
        return view('admin.ngoi-am-duong.index', compact('records'));
    }

    public function create(): View
    {
        return view('admin.ngoi-am-duong.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'thumbnail1'     =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'thumbnail2'     =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          => ['nullable', 'string', 'max:500'],
        ]);

        $this->service->create($data);

        return redirect()->route('admin.ngoi-am-duong.index')
            ->with('success', 'Thêm mới Ngói Âm Dương thành công.');
    }

    public function edit(NgoiAmDuong $ngoiAmDuong): View
    {
        // Load relationships để hiển thị ở trang edit
        $ngoiAmDuong->load('giaTri');
        return view('admin.ngoi-am-duong.edit', compact('ngoiAmDuong'));
    }

    public function update(Request $request, NgoiAmDuong $ngoiAmDuong): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'thumbnail1'     =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'thumbnail2'     =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          => ['nullable', 'string', 'max:500'],
        ]);

        $this->service->update($ngoiAmDuong->ngoi_am_duong_id, $data);

        return back()->with('success', 'Cập nhật thông tin thành công.');
    }

    public function destroy(NgoiAmDuong $ngoiAmDuong): RedirectResponse
    {
        $this->service->delete($ngoiAmDuong->ngoi_am_duong_id);

        return redirect()->route('admin.ngoi-am-duong.index')
            ->with('success', 'Đã xóa bản ghi thành công.');
    }

    // ─── QUẢN LÝ BẢNG PHỤ: GIÁ TRỊ ──────────────────────────────────────────

    public function storeGiaTri(Request $request, NgoiAmDuong $ngoiAmDuong): RedirectResponse
    {
        $data = $request->validate([
            'title'        =>['required', 'string', 'max:50'],
            'desscription' => ['required', 'string'], // Giữ đúng tên cột như DB
            'image'        =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->addGiaTri($ngoiAmDuong->ngoi_am_duong_id, $data);

        return back()->with('success', 'Đã thêm Giá trị mới thành công.');
    }

    public function updateGiaTri(Request $request, int $giaTriId): RedirectResponse
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:50'],
            'desscription' =>['required', 'string'],
            'image'        =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->updateGiaTri($giaTriId, $data);

        return back()->with('success', 'Cập nhật Giá trị thành công.');
    }

    public function destroyGiaTri(int $giaTriId): RedirectResponse
    {
        $this->service->deleteGiaTri($giaTriId);

        return back()->with('success', 'Xóa Giá trị thành công.');
    }
}