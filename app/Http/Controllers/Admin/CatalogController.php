<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CatalogService;
use Illuminate\Http\JsonResponse;
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

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $messages = [
            'anh_dai_dien.image' => 'Ảnh đại diện phải là hình ảnh.',
            'anh_dai_dien.mimes' => 'Định dạng ảnh không hỗ trợ (vd HEIC từ iPhone). Vui lòng đổi sang JPG, PNG hoặc WEBP trước khi tải lên.',
            'anh_dai_dien.max' => 'Ảnh đại diện không được vượt quá 5MB.',
            'file.mimes' => 'File catalog phải thuộc định dạng: pdf, doc, docx, zip, rar.',
            'file.max' => 'File catalog không được vượt quá 200MB.',
        ];

        $data = $request->validate([
            'tieu_de' => ['nullable', 'string', 'max:255'],
            'anh_dai_dien' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip,rar', 'max:204800'], // Max 200MB (204800 KB)
        ], $messages);

        try {
            $catalog = $this->service->store($data);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'catalog' => [
                        'catalog_id' => $catalog->catalog_id,
                        'tieu_de' => $catalog->tieu_de,
                        'anh_dai_dien' => asset('storage/'.$catalog->anh_dai_dien),
                        'file' => $catalog->file ? asset('storage/'.$catalog->file) : null,
                    ],
                ], 201);
            }

            return back()->with('success', 'Thêm catalog thành công.');
        } catch (\RuntimeException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $messages = [
            'anh_dai_dien.image' => 'Ảnh đại diện phải là hình ảnh.',
            'anh_dai_dien.mimes' => 'Định dạng ảnh không hỗ trợ (vd HEIC từ iPhone). Vui lòng đổi sang JPG, PNG hoặc WEBP trước khi tải lên.',
            'anh_dai_dien.max' => 'Ảnh đại diện không được vượt quá 5MB.',
            'file.mimes' => 'File catalog phải thuộc định dạng: pdf, doc, docx, zip, rar.',
            'file.max' => 'File catalog không được vượt quá 200MB.',
        ];

        $data = $request->validate([
            'tieu_de' => ['nullable', 'string', 'max:255'],
            'anh_dai_dien' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip,rar', 'max:204800'],
        ], $messages);

        try {
            $catalog = $this->service->update($id, $data);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'catalog' => [
                        'catalog_id' => $catalog->catalog_id,
                        'tieu_de' => $catalog->tieu_de,
                        'anh_dai_dien' => asset('storage/'.$catalog->anh_dai_dien),
                        'file' => $catalog->file ? asset('storage/'.$catalog->file) : null,
                    ],
                ], 200);
            }

            return back()->with('success', 'Cập nhật catalog thành công.');
        } catch (\RuntimeException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->destroy($id);

        return back()->with('success', 'Đã xóa catalog khỏi danh sách.');
    }
}
