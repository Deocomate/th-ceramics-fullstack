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
            'thumbnail_main' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video' => ['nullable', 'string', 'max:500'],
            'section_1_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'section_1_title' => ['nullable', 'string', 'max:255'],
            'section_1_products' => ['nullable', 'array'],
            'section_1_products.*' => ['integer', 'exists:lan_can_gom_su_ct,lan_can_gom_su_ct_id'],
            'section_2_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'section_2_title' => ['nullable', 'string', 'max:255'],
            'section_2_products' => ['nullable', 'array'],
            'section_2_products.*' => ['integer', 'exists:lan_can_gom_su_ct,lan_can_gom_su_ct_id'],
        ]);

        $this->service->update($data);

        return back()->with('success', 'Cập nhật cấu hình thành công.');
    }
}
