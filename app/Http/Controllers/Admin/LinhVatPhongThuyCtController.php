<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\DestroysProductGalleryMedia;
use App\Http\Controllers\Admin\Concerns\UploadsProductGalleryMedia;
use App\Http\Controllers\Controller;
use App\Services\LinhVatPhongThuyCtService;
use App\Rules\YoutubeUrl;
use Illuminate\Http\Request;
use InvalidArgumentException;

class LinhVatPhongThuyCtController extends Controller
{
    use DestroysProductGalleryMedia;
    use UploadsProductGalleryMedia;

    public function __construct(private readonly LinhVatPhongThuyCtService $service) {}

    public function index(Request $request)
    {
        $status = $request->query('status', 'active');
        $products = $this->service->getAll($status);

        return view('admin.linh-vat-phong-thuy-ct.index', compact('products', 'status'));
    }

    public function create()
    {
        return view('admin.linh-vat-phong-thuy-ct.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:0'],
            'size' => ['nullable', 'string', 'max:255'],
            'des' => ['nullable', 'array'],
            'des.*' => ['nullable', 'string', 'max:500'],
            'size_des' => ['nullable', 'array'],
            'size_des.*' => ['nullable', 'string', 'max:500'],
            'images' => ['nullable', 'required_without:cover_image', 'array', 'min:1'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'cover_image' => ['nullable', 'required_without:images', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'size_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video' => ['nullable', 'string', 'max:500', 'url', new YoutubeUrl],
            'video_urls' => ['nullable', 'array'],
            'video_urls.*' => ['nullable', 'string', 'max:500', 'url', new YoutubeUrl],
        ]);

        try {
            $this->service->create($data);

            return redirect()->route('admin.linh-vat-phong-thuy-ct.index')
                ->with('success', 'Thêm mới Linh Vật Phong Thủy thành công.');
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['code' => $e->getMessage()]);
        }
    }

    public function edit(int $id)
    {
        $product = $this->service->findById($id);

        return view('admin.linh-vat-phong-thuy-ct.edit', compact('product'));
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:0'],
            'size' => ['nullable', 'string', 'max:255'],
            'des' => ['nullable', 'array'],
            'des.*' => ['nullable', 'string', 'max:500'],
            'size_des' => ['nullable', 'array'],
            'size_des.*' => ['nullable', 'string', 'max:500'],
            'new_images' => ['nullable', 'array'],
            'new_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'gallery_order' => ['nullable', 'array'],
            'gallery_order.*' => ['string'],
            'size_image' => ['nullable', 'image', 'max:5120'],
            'video' => ['nullable', 'string', 'max:500', 'url', new YoutubeUrl],
            'new_video_urls' => ['nullable', 'array'],
            'new_video_urls.*' => ['nullable', 'string', 'max:500', 'url', new YoutubeUrl],
        ]);

        try {
            $this->service->update($id, $data);

            return back()->with('success', 'Cập nhật sản phẩm thành công.');
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['code' => $e->getMessage()]);
        }
    }

    public function destroy(int $id)
    {
        $this->service->toggleStatus($id, 1);

        return back()->with('success', 'Đã tạm ẩn sản phẩm thành công.');
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


    public function storeImages(Request $request, int $id)
    {
        return $this->storeGalleryImagesResponse(
            $request,
            fn (array $files) => $this->service->appendImagesToGallery($id, $files)
        );
    }

    public function reorderGallery(Request $request, int $id)
    {
        return $this->reorderGalleryResponse(
            $request,
            fn (array $tokens) => $this->service->reorderGalleryItems($id, $tokens),
            fn (string $imagePath) => $this->service->promoteCoverImage($id, $imagePath)
        );
    }
}
