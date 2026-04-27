<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LinhVatPhongThuyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LinhVatPhongThuyController extends Controller
{
    public function __construct(private readonly LinhVatPhongThuyService $service) {}

    public function index(): View
    {
        $linhVatPhongThuy = $this->service->getFirstRecord();
        return view('admin.linh-vat-phong-thuy.edit', compact('linhVatPhongThuy'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          =>['nullable', 'string', 'max:500'],
            'new_images'     => ['nullable', 'array'],
            'new_images.*'   =>['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->update($data);
        return back()->with('success', 'Cập nhật cấu hình thành công.');
    }

    public function storeLinhVat(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'image'       =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title'       =>['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
        ],[
            'title.max' => 'Tiêu đề không được vượt quá 50 ký tự.',
        ]);
        $this->service->addLinhVat($data);
        return back()->with('success', 'Thêm Linh Vật thành công.');
    }

    public function updateLinhVat(Request $request, int $itemId): RedirectResponse
    {
        $data = $request->validate([
            'image'       =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title'       =>['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
        ],[
            'title.max' => 'Tiêu đề không được vượt quá 50 ký tự.',
        ]);
        $this->service->updateLinhVat($itemId, $data);
        return back()->with('success', 'Cập nhật Linh Vật thành công.');
    }

    public function destroyLinhVat(int $itemId): RedirectResponse
    {
        $this->service->deleteLinhVat($itemId);
        return back()->with('success', 'Đã xóa Linh Vật.');
    }

    public function destroyAnh(int $anhId): RedirectResponse
    {
        $this->service->deleteAnh($anhId);
        return back()->with('success', 'Đã xóa ảnh khỏi thư viện.');
    }
}