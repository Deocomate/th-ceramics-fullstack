<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GachCoBatTrangService;
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

        return view('admin.gach-co-bat-trang.edit', compact('gachCoBatTrang'));
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
            'section_bat' => ['nullable', 'array'],
            'section_bat.title' => ['nullable', 'string', 'max:255'],
            'section_bat.subtitle' => ['nullable', 'string', 'max:255'],
            'section_bat.description' => ['nullable', 'string', 'max:2000'],
            'section_bat.colors' => ['nullable', 'array', 'size:3'],
            'section_bat.colors.*' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'section_bat_gallery_order' => ['nullable', 'array'],
            'section_bat_gallery_order.*' => ['string'],
            'section_bat_new_images' => ['nullable', 'array'],
            'section_bat_new_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'section_that' => ['nullable', 'array'],
            'section_that.title' => ['nullable', 'string', 'max:255'],
            'section_that.subtitle' => ['nullable', 'string', 'max:255'],
            'section_that.description' => ['nullable', 'string', 'max:2000'],
            'section_that.colors' => ['nullable', 'array', 'size:3'],
            'section_that.colors.*' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'section_that_gallery_order' => ['nullable', 'array'],
            'section_that_gallery_order.*' => ['string'],
            'section_that_new_images' => ['nullable', 'array'],
            'section_that_new_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'section_the' => ['nullable', 'array'],
            'section_the.title' => ['nullable', 'string', 'max:255'],
            'section_the.subtitle' => ['nullable', 'string', 'max:255'],
            'section_the.description' => ['nullable', 'string', 'max:2000'],
            'section_the.colors' => ['nullable', 'array', 'size:3'],
            'section_the.colors.*' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'section_the_gallery_order' => ['nullable', 'array'],
            'section_the_gallery_order.*' => ['string'],
            'section_the_new_images' => ['nullable', 'array'],
            'section_the_new_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->service->update($data);

        return back()->with('success', 'Cập nhật cấu hình thành công.');
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
}
