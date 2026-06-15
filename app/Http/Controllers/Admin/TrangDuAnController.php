<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TrangDuAnService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrangDuAnController extends Controller
{
    public function __construct(private readonly TrangDuAnService $service) {}

    public function index(): View
    {
        $trangDuAn = $this->service->getFirstRecord();

        return view('admin.trang-du-an.edit', compact('trangDuAn'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'promo_title' => ['required', 'string', 'max:500'],
            'promo_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'promo_cta_label' => ['required', 'string', 'max:100'],
            'promo_cta_url' => ['nullable', 'string', 'max:500'],
            'promo_enabled' => ['nullable', 'boolean'],
        ]);

        $data['promo_enabled'] = $request->boolean('promo_enabled');

        $this->service->update($data);

        return back()->with('success', 'Cập nhật cấu hình trang dự án thành công.');
    }
}
