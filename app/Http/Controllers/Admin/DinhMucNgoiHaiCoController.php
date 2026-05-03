<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DinhMucNgoiHaiCoService;
use Illuminate\Http\Request;

class DinhMucNgoiHaiCoController extends Controller
{
    public function __construct(private readonly DinhMucNgoiHaiCoService $service) {}

    public function index()
    {
        $dinhMucs = $this->service->getAll();
        return view('admin.dinh-muc-ngoi-hai-co.index', compact('dinhMucs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'roof_type'             =>['required', 'string', 'max:255'],
            'ngoi_tren_mai_go'      =>['required', 'integer', 'min:0'],
            'ngoi_tren_mai_be_tong' =>['required', 'integer', 'min:0'],
        ]);

        $this->service->create($data);
        return back()->with('success', 'Thêm định mức thành công.');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'roof_type'             => ['required', 'string', 'max:255'],
            'ngoi_tren_mai_go'      => ['required', 'integer', 'min:0'],
            'ngoi_tren_mai_be_tong' => ['required', 'integer', 'min:0'],
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