<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\DestroysProductGalleryMedia;
use App\Http\Controllers\Admin\Concerns\UploadsProductGalleryMedia;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNgoiHaiCoCtRequest;
use App\Http\Requests\UpdateNgoiHaiCoCtRequest;
use App\Services\NgoiHaiCoCtService;
use Illuminate\Http\Request;

class NgoiHaiCoCtController extends Controller
{
    use DestroysProductGalleryMedia;
    use UploadsProductGalleryMedia;

    public function __construct(private readonly NgoiHaiCoCtService $service) {}

    public function index(Request $request)
    {
        $status = $request->query('status', 'active');
        $products = $this->service->getAll($status);

        return view('admin.ngoi-hai-co-ct.index', compact('products', 'status'));
    }

    public function create()
    {
        return view('admin.ngoi-hai-co-ct.create');
    }

    public function store(StoreNgoiHaiCoCtRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.ngoi-hai-co-ct.index')
            ->with('success', 'Thêm mới Ngói Hài Cổ thành công.');
    }

    public function edit(int $id)
    {
        $product = $this->service->findById($id);

        return view('admin.ngoi-hai-co-ct.edit', compact('product'));
    }

    public function update(UpdateNgoiHaiCoCtRequest $request, int $id)
    {
        $this->service->update($id, $request->validated());

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
