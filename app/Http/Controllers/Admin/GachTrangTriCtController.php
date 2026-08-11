<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\DestroysProductGalleryMedia;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGachTrangTriCtRequest;
use App\Http\Requests\UpdateGachTrangTriCtRequest;
use App\Services\GachTrangTriCtService;
use Illuminate\Http\Request;
use InvalidArgumentException;

class GachTrangTriCtController extends Controller
{
    use DestroysProductGalleryMedia;

    public function __construct(private readonly GachTrangTriCtService $service) {}

    public function index(Request $request)
    {
        $status = $request->query('status', 'active');
        $products = $this->service->getAll($status);

        return view('admin.gach-trang-tri-ct.index', compact('products', 'status'));
    }

    public function create()
    {
        return view('admin.gach-trang-tri-ct.create');
    }

    public function store(StoreGachTrangTriCtRequest $request)
    {
        try {
            $this->service->create($request->validated());

            return redirect()->route('admin.gach-trang-tri-ct.index')
                ->with('success', 'Thêm mới Gạch Trang Trí thành công.');
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['code' => $e->getMessage()]);
        }
    }

    public function edit(int $id)
    {
        $product = $this->service->findById($id);

        return view('admin.gach-trang-tri-ct.edit', compact('product'));
    }

    public function update(UpdateGachTrangTriCtRequest $request, int $id)
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

}
