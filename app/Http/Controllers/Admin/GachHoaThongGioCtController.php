<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\DestroysProductGalleryMedia;
use App\Http\Controllers\Admin\Concerns\UploadsProductGalleryMedia;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGachHoaThongGioCtRequest;
use App\Http\Requests\UpdateGachHoaThongGioCtRequest;
use App\Services\GachHoaThongGioCtService;
use Illuminate\Http\Request;
use InvalidArgumentException;

class GachHoaThongGioCtController extends Controller
{
    use DestroysProductGalleryMedia;
    use UploadsProductGalleryMedia;

    public function __construct(private readonly GachHoaThongGioCtService $service) {}

    public function index(Request $request)
    {
        $status = $request->query('status', 'active');
        $products = $this->service->getAll($status);

        return view('admin.gach-hoa-thong-gio-ct.index', compact('products', 'status'));
    }

    public function create()
    {
        return view('admin.gach-hoa-thong-gio-ct.create');
    }

    public function store(StoreGachHoaThongGioCtRequest $request)
    {
        try {
            $this->service->create($request->validated());

            return redirect()->route('admin.gach-hoa-thong-gio-ct.index')
                ->with('success', 'Thêm mới Gạch Hoa Thông Gió thành công.');
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['code' => $e->getMessage()]);
        }
    }

    public function edit(int $id)
    {
        $product = $this->service->findById($id);

        return view('admin.gach-hoa-thong-gio-ct.edit', compact('product'));
    }

    public function update(UpdateGachHoaThongGioCtRequest $request, int $id)
    {
        try {
            $this->service->update($id, $request->validated());

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
