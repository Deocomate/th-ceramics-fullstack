<?php

namespace App\Domains\Catalog\Http\Admin;

use App\Domains\Catalog\Infrastructure\Services\DinhMucGachCoBatTrangService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DinhMucGachCoBatTrangController extends Controller
{
    public function __construct(private readonly DinhMucGachCoBatTrangService $service) {}

    public function index(): View
    {
        $dinhMucs = $this->service->getAll();

        return view('admin.catalog.dinh-muc-gach-co-bat-trang.index', compact('dinhMucs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'brick_type' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
        ]);

        $this->service->create($data);

        return back()->with('success', 'Thêm định mức thành công.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'brick_type' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
        ]);

        $this->service->update($id, $data);

        return back()->with('success', 'Cập nhật định mức thành công.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->destroy($id);

        return back()->with('success', 'Xóa định mức thành công.');
    }
}
