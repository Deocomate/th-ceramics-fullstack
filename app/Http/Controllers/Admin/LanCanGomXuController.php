<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanCanGomXu;
use App\Services\LanCanGomXuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LanCanGomXuController extends Controller
{
    public function __construct(private readonly LanCanGomXuService $service) {}

    public function index(): View
    {
        $records = $this->service->getAllPaginated();
        return view('admin.lan-can-gom-xu.index', compact('records'));
    }

    public function create(): View
    {
        return view('admin.lan-can-gom-xu.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          => ['nullable', 'string', 'max:500'],
        ]);

        $this->service->create($data);
        return redirect()->route('admin.lan-can-gom-xu.index')->with('success', 'Thêm mới thành công.');
    }

    public function edit(LanCanGomXu $lanCanGomXu): View
    {
        $lanCanGomXu->load('giaTri');
        return view('admin.lan-can-gom-xu.edit', compact('lanCanGomXu'));
    }

    public function update(Request $request, LanCanGomXu $lanCanGomXu): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          => ['nullable', 'string', 'max:500'],
        ]);

        $this->service->update($lanCanGomXu->lan_can_gom_xu_id, $data);
        return back()->with('success', 'Cập nhật thành công.');
    }

    public function destroy(LanCanGomXu $lanCanGomXu): RedirectResponse
    {
        $this->service->delete($lanCanGomXu->lan_can_gom_xu_id);
        return redirect()->route('admin.lan-can-gom-xu.index')->with('success', 'Đã xóa bản ghi.');
    }

    // --- Sub-routes: Giá Trị ---
    public function storeGiaTri(Request $request, LanCanGomXu $lanCanGomXu): RedirectResponse
    {
        $data = $request->validate([
            'image'        =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title'        => ['required', 'string', 'max:50'],
            'desscription' => ['required', 'string'],
        ]);
        $this->service->addGiaTri($lanCanGomXu->lan_can_gom_xu_id, $data);
        return back()->with('success', 'Thêm Giá trị thành công.');
    }

    public function updateGiaTri(Request $request, int $giaTriId): RedirectResponse
    {
        $data = $request->validate([
            'image'        =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title'        => ['required', 'string', 'max:50'],
            'desscription' => ['required', 'string'],
        ]);
        $this->service->updateGiaTri($giaTriId, $data);
        return back()->with('success', 'Cập nhật Giá trị thành công.');
    }

    public function destroyGiaTri(int $giaTriId): RedirectResponse
    {
        $this->service->deleteGiaTri($giaTriId);
        return back()->with('success', 'Đã xóa Giá trị.');
    }
}