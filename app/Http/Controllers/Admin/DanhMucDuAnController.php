<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DanhMucDuAnService;
use Illuminate\Http\Request;

class DanhMucDuAnController extends Controller
{
    public function __construct(private readonly DanhMucDuAnService $service) {}

    public function index(Request $request)
    {
        $status = $request->query('status', 'active');
        $danhMucs = $this->service->getAll($status);
        return view('admin.danh-muc-du-an.index', compact('danhMucs', 'status'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ten_danh_muc' => ['required', 'string', 'max:255']
        ]);

        $this->service->create($data);
        return back()->with('success', 'Thêm danh mục dự án thành công.');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'ten_danh_muc' =>['required', 'string', 'max:255']
        ]);

        $this->service->update($id, $data);
        return back()->with('success', 'Cập nhật danh mục thành công.');
    }

    public function destroy(int $id)
    {
        $this->service->toggleStatus($id, 1);
        return back()->with('success', 'Đã tạm ẩn danh mục.');
    }

    public function restore(int $id)
    {
        $this->service->toggleStatus($id, 0);
        return back()->with('success', 'Khôi phục danh mục thành công.');
    }
}