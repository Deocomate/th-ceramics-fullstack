<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LinhVatPhongThuy;
use App\Services\LinhVatPhongThuyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LinhVatPhongThuyController extends Controller
{
    public function __construct(private readonly LinhVatPhongThuyService $service) {}

    public function index(): View
    {
        $records = $this->service->getAllPaginated();
        return view('admin.linh-vat-phong-thuy.index', compact('records'));
    }

    public function create(): View
    {
        return view('admin.linh-vat-phong-thuy.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          =>['nullable', 'string', 'max:500'],
        ]);

        $this->service->create($data);
        return redirect()->route('admin.linh-vat-phong-thuy.index')->with('success', 'Thêm mới thành công.');
    }

    public function edit(LinhVatPhongThuy $linhVatPhongThuy): View
    {
        $linhVatPhongThuy->load(['linhVat', 'anh']);
        return view('admin.linh-vat-phong-thuy.edit', compact('linhVatPhongThuy'));
    }

    public function update(Request $request, LinhVatPhongThuy $linhVatPhongThuy): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          =>['nullable', 'string', 'max:500'],
        ]);

        $this->service->update($linhVatPhongThuy->linh_vat_phong_thuy_id, $data);
        return back()->with('success', 'Cập nhật thành công.');
    }

    public function destroy(LinhVatPhongThuy $linhVatPhongThuy): RedirectResponse
    {
        $this->service->delete($linhVatPhongThuy->linh_vat_phong_thuy_id);
        return redirect()->route('admin.linh-vat-phong-thuy.index')->with('success', 'Đã xóa bản ghi.');
    }

    // --- Sub-routes: Chi Tiết Linh Vật ---
    public function storeLinhVat(Request $request, LinhVatPhongThuy $linhVatPhongThuy): RedirectResponse
    {
        $data = $request->validate([
            'image'       =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title'       =>['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
        ]);
        $this->service->addLinhVat($linhVatPhongThuy->linh_vat_phong_thuy_id, $data);
        return back()->with('success', 'Thêm Linh Vật thành công.');
    }

    public function updateLinhVat(Request $request, int $itemId): RedirectResponse
    {
        $data = $request->validate([
            'image'       =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title'       => ['required', 'string', 'max:50'],
            'description' =>['required', 'string'],
        ]);
        $this->service->updateLinhVat($itemId, $data);
        return back()->with('success', 'Cập nhật Linh Vật thành công.');
    }

    public function destroyLinhVat(int $itemId): RedirectResponse
    {
        $this->service->deleteLinhVat($itemId);
        return back()->with('success', 'Đã xóa Linh Vật.');
    }

    // --- Sub-routes: Thư Viện Ảnh ---
    public function storeAnh(Request $request, LinhVatPhongThuy $linhVatPhongThuy): RedirectResponse
    {
        $data = $request->validate(['image' =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']]);
        $this->service->addAnh($linhVatPhongThuy->linh_vat_phong_thuy_id, $data);
        return back()->with('success', 'Thêm ảnh thành công.');
    }

    public function destroyAnh(int $anhId): RedirectResponse
    {
        $this->service->deleteAnh($anhId);
        return back()->with('success', 'Đã xóa ảnh.');
    }
}