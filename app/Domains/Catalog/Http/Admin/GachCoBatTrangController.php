<?php

namespace App\Domains\Catalog\Http\Admin;

use App\Domains\Catalog\Infrastructure\Services\GachCoBatTrangService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GachCoBatTrangController extends Controller
{
    public function __construct(private readonly GachCoBatTrangService $service) {}

    public function index(): View
    {
        $gachCoBatTrang = $this->service->getFirstRecord();

        return view('admin.catalog.gach-co-bat-trang.edit', compact('gachCoBatTrang'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video' => ['nullable', 'string', 'max:500'],
            'cong_doan_order' => ['nullable', 'array'],
            'cong_doan_order.*' => ['string'],
            'cong_doan_images' => ['nullable', 'array'],
            'cong_doan_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            ...$this->sectionRules('section_bat'),
            ...$this->sectionRules('section_that'),
            ...$this->sectionRules('section_the'),
        ]);

        $this->service->update($data);

        return back()->with('success', 'Cập nhật thông tin Gạch Cổ Bát Tràng thành công.');
    }

    public function storeAnh(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
        $this->service->addAnh($data);

        return back()->with('success', 'Thêm ảnh vào thư viện thành công.');
    }

    public function destroyAnh(int $anhId): RedirectResponse
    {
        $this->service->deleteAnh($anhId);

        return back()->with('success', 'Đã xóa ảnh khỏi thư viện.');
    }

    public function destroyCongDoanImage(Request $request): RedirectResponse
    {
        $request->validate(['image_path' => ['required', 'string']]);
        $this->service->removeImageFromJson($request->input('image_path'));

        return back()->with('success', 'Đã xóa ảnh công đoạn chế tác khỏi danh sách.');
    }

    public function destroySectionImage(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'section' => ['required', Rule::in(GachCoBatTrangService::SECTION_KEYS)],
            'image_path' => ['required', 'string'],
        ]);
        $this->service->removeSectionImage($data['section'], $data['image_path']);

        return back()->with('success', 'Đã xóa ảnh khỏi thư viện phân khu.');
    }

    /** @return array<string, array<int, mixed>> */
    private function sectionRules(string $key): array
    {
        return [
            $key => ['nullable', 'array'],
            $key.'.title' => ['nullable', 'string', 'max:255'],
            $key.'.subtitle' => ['nullable', 'string', 'max:255'],
            $key.'.description' => ['nullable', 'string', 'max:2000'],
            $key.'.colors' => ['nullable', 'array', 'size:3'],
            $key.'.colors.*' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            $key.'_gallery_order' => ['nullable', 'array'],
            $key.'_gallery_order.*' => ['string'],
            $key.'_new_images' => ['nullable', 'array'],
            $key.'_new_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}
