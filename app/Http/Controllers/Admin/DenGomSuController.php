<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DenGomSuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DenGomSuController extends Controller
{
    public function __construct(private readonly DenGomSuService $service) {}

    public function index(): View
    {
        $denGomSu = $this->service->getFirstRecord();
        return view('admin.den-gom-su.edit', compact('denGomSu'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image1'         =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image2'         =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title2'         => ['nullable', 'string', 'max:30'],
            'image3'         =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title3'         => ['nullable', 'string', 'max:30'],
            'image4'         =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          =>['nullable', 'string', 'max:500'],
            'new_images'     => ['nullable', 'array'],
            'new_images.*'   =>['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ],[
            'title2.max' => 'Tiêu đề 2 không được vượt quá 30 ký tự.',
            'title3.max' => 'Tiêu đề 3 không được vượt quá 30 ký tự.',
        ]);

        $this->service->update($data);
        return back()->with('success', 'Cập nhật cấu hình Đèn Gốm Sứ thành công.');
    }

    public function destroyAnh(int $anhId): RedirectResponse
    {
        $this->service->deleteAnh($anhId);
        return back()->with('success', 'Đã xóa ảnh khỏi thư viện.');
    }
}