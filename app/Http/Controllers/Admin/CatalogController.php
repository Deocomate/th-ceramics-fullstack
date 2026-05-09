<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function __construct(private readonly CatalogService $service) {}

    public function index(): View
    {
        $catalogs = $this->service->getAll();
        return view('admin.catalog.index', compact('catalogs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tieu_de'      => ['nullable', 'string', 'max:255'],
            'anh_dai_dien' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'file'         =>['nullable', 'file', 'mimes:pdf,doc,docx,zip,rar', 'max:20480'], // Max 20MB
        ]);

        $this->service->store($data);
        return back()->with('success', 'Thêm catalog thành công.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'tieu_de'      => ['nullable', 'string', 'max:255'],
            'anh_dai_dien' =>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'file'         =>['nullable', 'file', 'mimes:pdf,doc,docx,zip,rar', 'max:20480'],
        ]);

        $this->service->update($id, $data);
        return back()->with('success', 'Cập nhật catalog thành công.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->destroy($id);
        return back()->with('success', 'Đã xóa catalog khỏi danh sách.');
    }
}