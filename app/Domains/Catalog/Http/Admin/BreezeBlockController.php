<?php

namespace App\Domains\Catalog\Http\Admin;

use App\Domains\Catalog\Infrastructure\Services\BreezeBlockService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BreezeBlockController extends Controller
{
    public function __construct(private readonly BreezeBlockService $service) {}

    public function index(): View
    {
        $gachHoaThongGio = $this->service->getFirstRecord();

        return view('admin.catalog.gach-hoa-thong-gio.edit', compact('gachHoaThongGio'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'video_thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'new_images' => ['nullable', 'array'],
            'new_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'process_images' => ['nullable', 'array'],
            'process_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->update($data);

        return back()->with('success', 'Cập nhật thành công.');
    }

    public function storeAnh(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
        $this->service->addAnh($data);

        return back()->with('success', 'Thêm ảnh vào thư viện thành công.');
    }

    public function destroyAnh(int $anhId): RedirectResponse
    {
        $this->service->deleteAnh($anhId);

        return back()->with('success', 'Đã xóa ảnh khỏi thư viện.');
    }

    public function storeGiaTri(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'background' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title' => ['required', 'string', 'max:50'],
            'desscription' => ['required', 'string'],
        ]);
        $this->service->addGiaTri($data);

        return back()->with('success', 'Thêm giá trị thành công.');
    }

    public function updateGiaTri(Request $request, int $giaTriId): RedirectResponse
    {
        $data = $request->validate([
            'background' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title' => ['required', 'string', 'max:50'],
            'desscription' => ['required', 'string'],
        ]);
        $this->service->updateGiaTri($giaTriId, $data);

        return back()->with('success', 'Cập nhật giá trị thành công.');
    }

    public function destroyGiaTri(int $giaTriId): RedirectResponse
    {
        $this->service->deleteGiaTri($giaTriId);

        return back()->with('success', 'Đã xóa giá trị.');
    }

    public function destroyProcessImage(Request $request): RedirectResponse
    {
        $request->validate(['image_path' => ['required', 'string']]);
        $this->service->removeProcessImage($request->input('image_path'));

        return back()->with('success', 'Đã xóa ảnh công đoạn chế tác khỏi danh sách.');
    }
}
