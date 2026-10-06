<?php

namespace App\Domains\Catalog\Http\Admin;

use App\Domains\Catalog\Infrastructure\Services\FengShuiCreatureService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FengShuiCreatureController extends Controller
{
    public function __construct(private readonly FengShuiCreatureService $service) {}

    public function index(): View
    {
        $linhVatPhongThuy = $this->service->getFirstRecord();
        $linhVats = $this->service->getAllLinhVat();

        return view('admin.catalog.linh-vat-phong-thuy.edit', compact('linhVatPhongThuy', 'linhVats'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'video_url' => ['nullable', 'url', 'max:500'],
            'video_thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->update($data);

        return back()->with('success', 'Cập nhật thành công.');
    }

    public function storeLinhVat(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->createLinhVat($data);

        return back()->with('success', 'Thêm linh vật thành công.');
    }

    public function updateLinhVat(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->updateLinhVat($id, $data);

        return back()->with('success', 'Cập nhật linh vật thành công.');
    }

    public function destroyLinhVat(int $id): RedirectResponse
    {
        $this->service->deleteLinhVat($id);

        return back()->with('success', 'Đã xóa linh vật.');
    }

    public function destroyAnh(int $anhId): RedirectResponse
    {
        $this->service->deleteAnh($anhId);

        return back()->with('success', 'Đã xóa ảnh.');
    }
}
