<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NgoiAmDuongService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NgoiAmDuongController extends Controller
{
    public function __construct(private readonly NgoiAmDuongService $service) {}

    public function index(): View
    {
        $ngoiAmDuong = $this->service->getFirstRecord();
        return view('admin.ngoi-am-duong.edit', compact('ngoiAmDuong'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'thumbnail1'     =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'thumbnail2'     =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          =>['nullable', 'string', 'max:500'],
        ]);

        $this->service->update($data);
        return back()->with('success', 'Đã cập nhật thông tin Ngói Âm Dương thành công.');
    }

    public function storeGiaTri(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'        =>['required', 'string', 'max:50'],
            'desscription' => ['required', 'string'], 
            'image'        =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ],[
            'title.max' => 'Tiêu đề không được vượt quá 50 ký tự.',
            'image.max' => 'Hình ảnh không được vượt quá 5MB.',
        ]);

        $this->service->addGiaTri($data);
        return back()->with('success', 'Đã thêm Giá trị nổi bật thành công.');
    }

    public function updateGiaTri(Request $request, int $giaTriId): RedirectResponse
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:50'],
            'desscription' => ['required', 'string'],
            'image'        =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
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