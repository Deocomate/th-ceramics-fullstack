<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NgoiHaiVanMieu;
use App\Services\NgoiHaiVanMieuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NgoiHaiVanMieuController extends Controller
{
    public function __construct(private readonly NgoiHaiVanMieuService $service) {}

    public function index(): View
    {
        $records = $this->service->getAllPaginated();
        return view('admin.ngoi-hai-van-mieu.index', compact('records'));
    }

    public function create(): View
    {
        return view('admin.ngoi-hai-van-mieu.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title1'         =>['required', 'string', 'max:50'],
            'thumbnail1'     =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title2'         =>['required', 'string', 'max:50'],
            'thumbnail2'     =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title3'         => ['required', 'string', 'max:50'],
            'thumbnail3'     =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          => ['nullable', 'string', 'max:500'],
        ]);

        $this->service->create($data);

        return redirect()->route('admin.ngoi-hai-van-mieu.index')
            ->with('success', 'Thêm mới Ngói Hài Văn Miếu thành công.');
    }

    public function edit(NgoiHaiVanMieu $ngoiHaiVanMieu): View
    {
        return view('admin.ngoi-hai-van-mieu.edit', compact('ngoiHaiVanMieu'));
    }

    public function update(Request $request, NgoiHaiVanMieu $ngoiHaiVanMieu): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title1'         => ['required', 'string', 'max:50'],
            'thumbnail1'     =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title2'         => ['required', 'string', 'max:50'],
            'thumbnail2'     =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title3'         =>['required', 'string', 'max:50'],
            'thumbnail3'     =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          =>['nullable', 'string', 'max:500'],
        ]);

        $this->service->update($ngoiHaiVanMieu->ngoi_hai_van_mieu_id, $data);

        return back()->with('success', 'Cập nhật thông tin thành công.');
    }

    public function destroy(NgoiHaiVanMieu $ngoiHaiVanMieu): RedirectResponse
    {
        $this->service->delete($ngoiHaiVanMieu->ngoi_hai_van_mieu_id);

        return redirect()->route('admin.ngoi-hai-van-mieu.index')
            ->with('success', 'Đã xóa bản ghi thành công.');
    }
}