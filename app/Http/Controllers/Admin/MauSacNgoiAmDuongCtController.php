<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MauSacNgoiAmDuongCtService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MauSacNgoiAmDuongCtController extends Controller
{
    public function __construct(private readonly MauSacNgoiAmDuongCtService $service) {}

    public function index(): View
    {
        $mauSacs = $this->service->getAll();

        return view('admin.mau-sac-ngoi-am-duong-ct.index', compact('mauSacs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->create($data);

        return back()->with('success', 'Thêm màu sắc thành công.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->update($id, $data);

        return back()->with('success', 'Cập nhật màu sắc thành công.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->destroy($id);

        return back()->with('success', 'Xóa màu sắc thành công.');
    }
}
