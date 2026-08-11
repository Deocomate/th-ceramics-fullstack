<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\DestroysProductGalleryMedia;
use App\Http\Controllers\Admin\Concerns\UploadsProductGalleryMedia;
use App\Http\Controllers\Controller;
use App\Models\PhuKienNgoiCt;
use App\Rules\YoutubeUrl;
use App\Services\PhuKienNgoiCtService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PhuKienNgoiCtController extends Controller
{
    use DestroysProductGalleryMedia;
    use UploadsProductGalleryMedia;

    public function __construct(private readonly PhuKienNgoiCtService $service) {}

    public function index(Request $request)
    {
        $status = $request->query('status', 'active');
        $categoryType = $this->categoryType($request);
        $products = $this->service->getAll($status, $categoryType);
        $categoryLabel = PhuKienNgoiCt::categoryLabel($categoryType);

        return view('admin.phu-kien-ngoi-ct.index', compact('products', 'status', 'categoryType', 'categoryLabel'));
    }

    public function create(Request $request)
    {
        $categoryType = $this->categoryType($request);
        $categoryLabel = PhuKienNgoiCt::categoryLabel($categoryType);

        return view('admin.phu-kien-ngoi-ct.create', compact('categoryType', 'categoryLabel'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedProductData($request, true);
        $this->service->create($data);

        return redirect()
            ->route('admin.phu-kien-ngoi-ct.index', ['category_type' => $data['category_type']])
            ->with('success', 'Thêm mới '.PhuKienNgoiCt::categoryLabel($data['category_type']).' thành công.');
    }

    public function edit(int $id)
    {
        $product = $this->service->findById($id);
        $categoryType = $product->category_type;
        $categoryLabel = PhuKienNgoiCt::categoryLabel($categoryType);

        return view('admin.phu-kien-ngoi-ct.edit', compact('product', 'categoryType', 'categoryLabel'));
    }

    public function update(Request $request, int $id)
    {
        $product = $this->service->findById($id);
        $data = $this->validatedProductData($request, false, $product->category_type);
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


    private function validatedProductData(Request $request, bool $create, ?string $fallbackCategoryType = null): array
    {
        return $request->validate([
            'category_type' => ['required', Rule::in([PhuKienNgoiCt::TYPE_BO_NOC, PhuKienNgoiCt::TYPE_CHU_VAN])],
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:100'],
            'size' => ['nullable', 'string', 'max:255'],
            'des' => ['nullable', 'array'],
            'des.*' => ['nullable', 'string', 'max:500'],
            'size_des' => ['nullable', 'array'],
            'size_des.*' => ['nullable', 'string', 'max:500'],
            $create ? 'images' : 'new_images' => $create
                ? ['nullable', 'required_without:cover_image', 'array', 'min:1']
                : ['nullable', 'array'],
            ($create ? 'images' : 'new_images').'.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'cover_image' => $create
                ? ['nullable', 'required_without:images', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']
                : ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'gallery_order' => ['nullable', 'array'],
            'gallery_order.*' => ['string'],
            'size_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video' => ['nullable', 'string', 'max:500', 'url', new YoutubeUrl],
            $create ? 'video_urls' : 'new_video_urls' => ['nullable', 'array'],
            ($create ? 'video_urls' : 'new_video_urls').'.*' => ['nullable', 'string', 'max:500', 'url', new YoutubeUrl],
        ]) + ['category_type' => $fallbackCategoryType ?? $request->input('category_type')];
    }

    private function categoryType(Request $request): string
    {
        $categoryType = $request->query('category_type', PhuKienNgoiCt::TYPE_BO_NOC);

        return $categoryType === PhuKienNgoiCt::TYPE_CHU_VAN ? PhuKienNgoiCt::TYPE_CHU_VAN : PhuKienNgoiCt::TYPE_BO_NOC;
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
