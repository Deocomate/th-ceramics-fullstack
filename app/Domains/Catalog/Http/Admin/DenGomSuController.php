<?php

namespace App\Domains\Catalog\Http\Admin;

use App\Domains\Catalog\Infrastructure\Services\DenGomSuService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DenGomSuController extends Controller
{
    public function __construct(private readonly DenGomSuService $service) {}

    public function index(): View
    {
        $denGomSu = $this->service->getFirstRecord();

        return view('admin.catalog.den-gom-su.edit', compact('denGomSu'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'video_url' => ['nullable', 'url', 'max:500'],
            'video_thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->update($data);

        return back()->with('success', 'Cập nhật thông tin thành công.');
    }

    public function destroyAnh(int $anhId): RedirectResponse
    {
        $this->service->deleteAnh($anhId);

        return back()->with('success', 'Đã xóa ảnh.');
    }
}
