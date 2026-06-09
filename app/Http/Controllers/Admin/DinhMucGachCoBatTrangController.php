<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DinhMucGachCoBatTrangService;
use Illuminate\Http\Request;

class DinhMucGachCoBatTrangController extends Controller
{
    public function __construct(private readonly DinhMucGachCoBatTrangService $service) {}

    public function index()
    {
        $dinhMucs = $this->service->getAll();

        return view('admin.dinh-muc-gach-co-bat-trang.index', compact('dinhMucs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'brick_type' => ['required', 'string', 'max:255'],
            'value' => ['nullable', 'integer', 'min:0'],
        ]);

        $this->service->create($data);

        return back()->with('success', 'Thêm định mức thành công.');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'brick_type' => ['required', 'string', 'max:255'],
            'value' => ['nullable', 'integer', 'min:0'],
        ]);

        $this->service->update($id, $data);

        return back()->with('success', 'Cập nhật định mức thành công.');
    }

    public function destroy(int $id)
    {
        $this->service->destroy($id);

        return back()->with('success', 'Xóa định mức thành công.');
    }
}
