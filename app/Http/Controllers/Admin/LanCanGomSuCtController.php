<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\DestroysProductGalleryMedia;
use App\Http\Controllers\Controller;
use App\Services\LanCanGomSuCtService;
use App\Rules\YoutubeUrl;
use Illuminate\Http\Request;

class LanCanGomSuCtController extends Controller
{
    use DestroysProductGalleryMedia;

    public function __construct(private readonly LanCanGomSuCtService $service) {}

    public function index(Request $request)
    {
        $status = $request->query('status', 'active');
        $products = $this->service->getAll($status);

        return view('admin.lan-can-gom-su-ct.index', compact('products', 'status'));
    }

    public function create()
    {
        return view('admin.lan-can-gom-su-ct.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'], 'color' => ['nullable', 'string', 'max:100'], 'size' => ['nullable', 'string', 'max:255'],
            'des' => ['nullable', 'array'], 'des.*' => ['nullable', 'string', 'max:500'],
            'size_des' => ['nullable', 'array'], 'size_des.*' => ['nullable', 'string', 'max:500'],
            'images' => ['required', 'array'], 'images.*' => ['image', 'max:5120'],
            'size_image' => ['nullable', 'image', 'max:5120'],
            'video' => ['nullable', 'string', 'max:500', 'url', new YoutubeUrl],
            'video_urls' => ['nullable', 'array'],
            'video_urls.*' => ['nullable', 'string', 'max:500', 'url', new YoutubeUrl],
        ]);
        $this->service->create($data);

        return redirect()->route('admin.lan-can-gom-su-ct.index')->with('success', 'Thêm mới Lan Can Gốm Sứ thành công.');
    }

    public function edit(int $id)
    {
        $product = $this->service->findById($id);

        return view('admin.lan-can-gom-su-ct.edit', compact('product'));
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'], 'color' => ['nullable', 'string', 'max:100'], 'size' => ['nullable', 'string', 'max:255'],
            'des' => ['nullable', 'array'], 'des.*' => ['nullable', 'string', 'max:500'],
            'size_des' => ['nullable', 'array'], 'size_des.*' => ['nullable', 'string', 'max:500'],
            'new_images' => ['nullable', 'array'], 'new_images.*' => ['image', 'max:5120'],
            'size_image' => ['nullable', 'image', 'max:5120'],
            'video' => ['nullable', 'string', 'max:500', 'url', new YoutubeUrl],
            'new_video_urls' => ['nullable', 'array'],
            'new_video_urls.*' => ['nullable', 'string', 'max:500', 'url', new YoutubeUrl],
        ]);
        $this->service->update($id, $data);

        return back()->with('success', 'Cập nhật thành công.');
    }

    public function destroy(int $id)
    {
        $this->service->toggleStatus($id, 1);

        return back()->with('success', 'Đã tạm ẩn sản phẩm.');
    }

    public function restore(int $id)
    {
        $this->service->toggleStatus($id, 0);

        return back()->with('success', 'Khôi phục sản phẩm thành công.');
    }

    public function destroyImage(Request $request, int $id)
    {
        return $this->destroyGalleryMediaResponse(
            $request,
            fn (array $imagePaths, array $videoUrls) => $this->service->removeGalleryItemsFromJson($id, $imagePaths, $videoUrls)
        );
    }

}
