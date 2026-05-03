<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DinhMucNgoiAmDuongService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DinhMucNgoiAmDuongController extends Controller
{
    public function __construct(private readonly DinhMucNgoiAmDuongService $service) {}

    public function index(): View
    {
        $dinhMucs = $this->service->getAll();
        return view('admin.dinh-muc-ngoi-am-duong.index', compact('dinhMucs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'roof_type'  => ['required', 'string', 'max:255'],
            'tile_type'  => ['required', 'string', 'max:255'],
            'ngoi_am'    => ['required', 'integer', 'min:0'],
            'ngoi_duong' =>['required', 'integer', 'min:0'],
            'diem'       =>['required', 'integer', 'min:0'],
        ]);

        $this->service->create($data);
        return back()->with('success', 'Thêm định mức thành công.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'roof_type'  => ['required', 'string', 'max:255'],
            'tile_type'  => ['required', 'string', 'max:255'],
            'ngoi_am'    => ['required', 'integer', 'min:0'],
            'ngoi_duong' =>['required', 'integer', 'min:0'],
            'diem'       =>['required', 'integer', 'min:0'],
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