<?php

namespace App\Domains\Content\Http\Admin;

use App\Domains\Content\Infrastructure\Services\CatalogService;
use App\Domains\Media\Infrastructure\MediaDisk;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CatalogController extends Controller
{
    public function __construct(private readonly CatalogService $service) {}

    public function index(): View
    {
        $catalogs = $this->service->getAll();

        return view('admin.content.catalog.index', compact('catalogs'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $messages = [
            'anh_dai_dien.image' => 'Ảnh đại diện phải là hình ảnh.',
            'anh_dai_dien.mimes' => 'Ảnh chưa được tối ưu đúng định dạng. Hãy tải lại trang, chọn lại ảnh và đợi xử lý xong.',
            'anh_dai_dien.max' => 'Ảnh đại diện không được vượt quá 5MB.',
            'file.mimes' => 'File catalog phải là file PDF.',
            'file.max' => 'File catalog không được vượt quá 200MB.',
        ];

        $data = $request->validate([
            'tieu_de' => ['nullable', 'string', 'max:255'],
            'anh_dai_dien' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:204800'], // Max 200MB (204800 KB)
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
                        'file' => $catalog->file ? route('admin.catalog.file', $catalog->catalog_id) : null,
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
            'anh_dai_dien.mimes' => 'Ảnh chưa được tối ưu đúng định dạng. Hãy tải lại trang, chọn lại ảnh và đợi xử lý xong.',
            'anh_dai_dien.max' => 'Ảnh đại diện không được vượt quá 5MB.',
            'file.mimes' => 'File catalog phải là file PDF.',
            'file.max' => 'File catalog không được vượt quá 200MB.',
        ];

        $data = $request->validate([
            'tieu_de' => ['nullable', 'string', 'max:255'],
            'anh_dai_dien' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:204800'],
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
                        'file' => $catalog->file ? route('admin.catalog.file', $catalog->catalog_id) : null,
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

    /**
     * Serve the original catalog file to admins; it has no public URL.
     */
    public function file(int $id): StreamedResponse
    {
        $path = (string) $this->service->findById($id)->file;
        $disk = Storage::disk(MediaDisk::forPath($path));

        abort_unless(str_starts_with($path, 'catalog/files/') && ! str_contains($path, '..') && $disk->exists($path), 404);

        return $disk->response($path, basename($path), ['Cache-Control' => 'private, no-store']);
    }

    public function storePage(Request $request, int $id): JsonResponse
    {
        // The server allows headroom over the size the admin browser aims for.
        $maxKilobytes = (int) ceil((int) config('content_protection.catalog_pages.max_bytes', 1_000_000) * 1.5 / 1000);

        $data = $request->validate([
            'batch' => ['required', 'uuid'],
            'total' => ['required', 'integer', 'min:1', 'max:'.$this->maxPageItems()],
            'index' => ['required', 'integer', 'min:0', 'lt:total'],
            'image' => ['required', 'image', 'mimes:webp', 'max:'.$maxKilobytes],
        ], [
            'total.max' => 'Catalog có quá nhiều trang.',
            'image.mimes' => 'Ảnh trang phải là WebP.',
            'image.max' => 'Ảnh trang vượt quá dung lượng cho phép.',
        ]);

        $path = $this->service->storePage($id, strtolower($data['batch']), (int) $data['index'], $data['image']);

        return response()->json(['success' => true, 'path' => $path]);
    }

    public function finalizePages(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'batch' => ['required', 'uuid'],
            'pages' => ['required', 'array', 'min:1', 'max:'.$this->maxPageItems()],
            'pages.*.pdf_page' => ['required', 'integer', 'min:1'],
            'pages.*.side' => ['required', Rule::in(['full', 'left', 'right'])],
        ]);

        $catalog = $this->service->finalizePages($id, strtolower($data['batch']), $data['pages']);

        return response()->json(['success' => true, 'pages' => count($catalog->pageItems())]);
    }

    /**
     * A spread page is stored as two images, so a PDF at the page limit can yield twice as many.
     */
    private function maxPageItems(): int
    {
        return 2 * (int) config('content_protection.catalog_pages.max_pages', 400);
    }
}
