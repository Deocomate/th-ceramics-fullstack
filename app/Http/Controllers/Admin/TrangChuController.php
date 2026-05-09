<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TrangChuService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TrangChuController extends Controller
{
    public function __construct(private readonly TrangChuService $service) {}

    public function edit(): View
    {
        $trangChu = $this->service->getFirstRecord();
        return view('admin.trang-chu.edit', compact('trangChu'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'new_banner' => ['nullable', 'array'],
            'new_banner.*' =>['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'delete_banner' => ['nullable', 'array'],
            'delete_banner.*' => ['integer'],
            
            'new_khach_hang' => ['nullable', 'array'],
            'new_khach_hang.*' =>['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'delete_khach_hang' =>['nullable', 'array'],
            'delete_khach_hang.*' => ['integer'],

            'loi_tri_an' => ['nullable', 'array'],
            'loi_tri_an.*' =>['nullable', 'string'],
            
            'loi_tri_an_anh' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            'new_ve_chung_toi_logo' => ['nullable', 'array'],
            'new_ve_chung_toi_logo.*' =>['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'delete_ve_chung_toi_logo' =>['nullable', 'array'],
            'delete_ve_chung_toi_logo.*' => ['integer'],

            'video' => ['nullable', 'string', 'max:255'],

            'nhung_con_so' => ['nullable', 'array'],
            'nhung_con_so.*.head' => ['nullable', 'string', 'max:255'],
            'nhung_con_so.*.body' => ['nullable', 'string', 'max:255'],

            'new_showroom_images' => ['nullable', 'array'],
            'new_showroom_images.*' =>['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'delete_showroom_images' => ['nullable', 'array'],
            'delete_showroom_images.*' => ['integer'],

            'showroom_noidung' => ['nullable', 'string'],
        ]);

        $this->service->update($data);

        return back()->with('success', 'Cập nhật Trang chủ thành công.');
    }
}