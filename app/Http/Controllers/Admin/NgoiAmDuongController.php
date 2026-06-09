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
            'thumbnail_main' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'thumbnail1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'thumbnail2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video' => ['nullable', 'string', 'max:500'],
        ]);
        $this->service->update($data);

        return back()->with('success', 'Đã cập nhật thông tin Ngói Âm Dương thành công.');
    }
}
