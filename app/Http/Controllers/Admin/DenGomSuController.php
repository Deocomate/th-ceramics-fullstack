<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DenGomSu;
use App\Services\DenGomSuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DenGomSuController extends Controller
{
    public function __construct(private readonly DenGomSuService $service) {}

    public function index(): View
    {
        $records = $this->service->getAllPaginated();
        return view('admin.den-gom-su.index', compact('records'));
    }

    public function create(): View
    {
        return view('admin.den-gom-su.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image1'         =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image2'         =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title2'         => ['nullable', 'string', 'max:30'],
            'image3'         =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title3'         => ['nullable', 'string', 'max:30'],
            'image4'         =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          => ['nullable', 'string', 'max:500'],
        ]);

        $this->service->create($data);
        return redirect()->route('admin.den-gom-su.index')->with('success', 'Thêm mới thành công.');
    }

    public function edit(DenGomSu $denGomSu): View
    {
        $denGomSu->load('anh');
        return view('admin.den-gom-su.edit', compact('denGomSu'));
    }

    public function update(Request $request, DenGomSu $denGomSu): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image1'         =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image2'         =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title2'         => ['nullable', 'string', 'max:30'],
            'image3'         =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title3'         =>['nullable', 'string', 'max:30'],
            'image4'         =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'          =>['nullable', 'string', 'max:500'],
        ]);

        $this->service->update($denGomSu->den_gom_su_id, $data);
        return back()->with('success', 'Cập nhật thành công.');
    }

    public function destroy(DenGomSu $denGomSu): RedirectResponse
    {
        $this->service->delete($denGomSu->den_gom_su_id);
        return redirect()->route('admin.den-gom-su.index')->with('success', 'Đã xóa bản ghi.');
    }

    public function storeAnh(Request $request, DenGomSu $denGomSu): RedirectResponse
    {
        $data = $request->validate(['image' =>['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']]);
        $this->service->addAnh($denGomSu->den_gom_su_id, $data);
        return back()->with('success', 'Thêm ảnh thành công.');
    }

    public function destroyAnh(int $anhId): RedirectResponse
    {
        $this->service->deleteAnh($anhId);
        return back()->with('success', 'Đã xóa ảnh.');
    }
}