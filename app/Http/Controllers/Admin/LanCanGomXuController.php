<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LanCanGomXuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LanCanGomXuController extends Controller
{
    public function __construct(private readonly LanCanGomXuService $service) {}

    public function index(): View
    {
        $lanCanGomXu = $this->service->getFirstRecord();
        return view('admin.lan-can-gom-xu.edit', compact('lanCanGomXu'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          =>['nullable', 'string', 'max:500'],
        ]);

        $this->service->update($data);
        return back()->with('success', 'Cập nhật cấu hình thành công.');
    }

    public function storeGiaTri(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'image'        =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title'        => ['required', 'string', 'max:50'],
            'desscription' => ['required', 'string'],
        ],[
            'title.max' => 'Tiêu đề không được vượt quá 50 ký tự.',
            'image.max' => 'Hình ảnh không được vượt quá 5MB.',
        ]);
        
        $this->service->addGiaTri($data);
        return back()->with('success', 'Thêm Giá trị thành công.');
    }

    public function updateGiaTri(Request $request, int $giaTriId): RedirectResponse
    {
        $data = $request->validate([
            'image'        =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title'        => ['required', 'string', 'max:50'],
            'desscription' =>['required', 'string'],
        ],[
            'title.max' => 'Tiêu đề không được vượt quá 50 ký tự.',
            'image.max' => 'Hình ảnh không được vượt quá 5MB.',
        ]);
        
        $this->service->updateGiaTri($giaTriId, $data);
        return back()->with('success', 'Cập nhật Giá trị thành công.');
    }

    public function destroyGiaTri(int $giaTriId): RedirectResponse
    {
        $this->service->deleteGiaTri($giaTriId);
        return back()->with('success', 'Đã xóa Giá trị khỏi danh sách.');
    }
}