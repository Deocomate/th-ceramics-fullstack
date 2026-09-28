<?php

namespace App\Domains\Catalog\Http\Admin;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\ProductWriter;
use App\Domains\Catalog\Services\CatalogQueryService;
use App\Domains\Media\FileUploadHelper;
use App\Http\Controllers\Admin\Concerns\DestroysProductGalleryMedia;
use App\Http\Controllers\Admin\Concerns\UploadsProductGalleryMedia;
use App\Http\Controllers\Controller;
use App\Rules\YoutubeUrl;
use App\Services\ProductCopyService;
use App\Support\ProductGallery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use InvalidArgumentException;

abstract class BaseProductItemController extends Controller
{
    use DestroysProductGalleryMedia;
    use UploadsProductGalleryMedia;

    protected string $typeKey;
    protected string $viewPrefix;
    protected string $routePrefix;
    protected string $itemLabel;
    protected string $imageDirectory;
    protected string $sizeDirectory;

    public function __construct(
        protected readonly CatalogQueryService $queryService,
        protected readonly ProductWriter $writer,
        protected readonly ProductCopyService $copyService,
    ) {}

    public function index(Request $request): View
    {
        $status = $request->query('status', 'active');
        $categoryType = $request->query('category_type');
        $products = $this->queryService->all($this->typeKey, $status, $categoryType);

        return view("{$this->viewPrefix}.index", compact('products', 'status', 'categoryType'));
    }

    public function create(Request $request): View
    {
        $copiedProduct = null;
        if ($request->filled('copy_from')) {
            $copiedProduct = $this->copyService->getProductDetailForCopy(
                str_replace('_', '-', $this->typeKey),
                (int) $request->query('copy_from')
            );
        }

        return view("{$this->viewPrefix}.create", compact('copiedProduct'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $this->validateStore($request);

            if ($request->hasFile('size_image')) {
                $data['size_image'] = FileUploadHelper::upload($request->file('size_image'), $this->sizeDirectory);
            }

            $product = $this->writer->create($this->typeKey, $data);

            $this->storeUploadsOnProduct($request, $product);

            return redirect()->route("{$this->routePrefix}.index")
                ->with('success', "Thêm mới {$this->itemLabel} thành công.");
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['code' => $e->getMessage()]);
        }
    }

    public function edit(int $id): View
    {
        $product = $this->queryService->find($this->typeKey, $id);

        return view("{$this->viewPrefix}.edit", compact('product'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        try {
            $product = $this->queryService->find($this->typeKey, $id);
            $data = $this->validateUpdate($request, $product);

            if ($request->hasFile('size_image')) {
                if ($product->size_image) {
                    FileUploadHelper::delete($product->size_image);
                }
                $data['size_image'] = FileUploadHelper::upload($request->file('size_image'), $this->sizeDirectory);
            }

            $this->writer->update($product, $data);

            $this->storeUploadsOnProduct($request, $product);

            return back()->with('success', 'Cập nhật sản phẩm thành công.');
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['code' => $e->getMessage()]);
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $product = $this->queryService->find($this->typeKey, $id);
        $this->writer->softDelete($product);

        return back()->with('success', 'Đã tạm ẩn sản phẩm thành công.');
    }

    public function restore(int $id): RedirectResponse
    {
        $product = $this->queryService->find($this->typeKey, $id);
        $this->writer->restore($product);

        return back()->with('success', 'Khôi phục sản phẩm thành công.');
    }

    public function destroyImage(Request $request, int $id): RedirectResponse|JsonResponse
    {
        return $this->destroyGalleryMediaResponse(
            $request,
            function (array $imagePaths, array $videoUrls, array $videoPaths = []) use ($id) {
                $product = $this->queryService->find($this->typeKey, $id);
                return $this->removeGalleryItems($product, $imagePaths, $videoUrls, $videoPaths);
            }
        );
    }

    public function storeImages(Request $request, int $id)
    {
        return $this->storeGalleryImagesResponse(
            $request,
            function (array $images, array $videoUrls, array $videoFiles) use ($id) {
                $product = $this->queryService->find($this->typeKey, $id);
                return $this->appendMediaToGallery($product, $images, $videoUrls, $videoFiles, $this->imageDirectory);
            }
        );
    }

    public function reorderGallery(Request $request, int $id)
    {
        return $this->reorderGalleryResponse(
            $request,
            function (array $tokens) use ($id) {
                $product = $this->queryService->find($this->typeKey, $id);
                return $this->reorderGalleryItems($product, $tokens);
            },
            function (string $imagePath) use ($id) {
                $product = $this->queryService->find($this->typeKey, $id);
                return $this->promoteCoverImage($product, $imagePath);
            }
        );
    }

    protected function validateStore(Request $request): array
    {
        $rules = array_merge([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'price' => ['nullable', 'integer', 'min:0'],
            'color' => ['nullable', 'string', 'max:100'],
            'category_type' => ['nullable', 'string', 'max:50'],
            'size' => ['nullable', 'string', 'max:255'],
            'video' => ['nullable', 'string', 'max:500'],
            'video_urls' => ['nullable', 'array', 'max:20'],
            'video_urls.*' => ['nullable', 'string', 'max:500', 'url', new YoutubeUrl],
            'videos' => ['nullable', 'array', 'max:5'],
            'videos.*' => ['file', 'mimes:mp4,webm', 'max:51200'],
            'dinh_muc' => ['nullable', 'string', 'max:50'],
            'weight' => ['nullable', 'string', 'max:50'],
            'des' => ['nullable', 'array'],
            'des.*' => ['nullable', 'string', 'max:500'],
            'size_des' => ['nullable', 'array'],
            'size_des.*' => ['nullable', 'string', 'max:500'],
            'cover_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], $this->customStoreRules($request));

        $data = $request->validate($rules);
        return $this->sanitizePayload($data);
    }

    protected function validateUpdate(Request $request, Product $product): array
    {
        $rules = array_merge([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'price' => ['nullable', 'integer', 'min:0'],
            'color' => ['nullable', 'string', 'max:100'],
            'category_type' => ['nullable', 'string', 'max:50'],
            'size' => ['nullable', 'string', 'max:255'],
            'video' => ['nullable', 'string', 'max:500'],
            'new_video_urls' => ['nullable', 'array', 'max:20'],
            'new_video_urls.*' => ['nullable', 'string', 'max:500', 'url', new YoutubeUrl],
            'new_videos' => ['nullable', 'array', 'max:5'],
            'new_videos.*' => ['file', 'mimes:mp4,webm', 'max:51200'],
            'new_images' => ['nullable', 'array'],
            'new_images.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'dinh_muc' => ['nullable', 'string', 'max:50'],
            'weight' => ['nullable', 'string', 'max:50'],
            'des' => ['nullable', 'array'],
            'des.*' => ['nullable', 'string', 'max:500'],
            'size_des' => ['nullable', 'array'],
            'size_des.*' => ['nullable', 'string', 'max:500'],
        ], $this->customUpdateRules($request, $product));

        $data = $request->validate($rules);
        return $this->sanitizePayload($data);
    }

    protected function customStoreRules(Request $request): array
    {
        return [];
    }

    protected function customUpdateRules(Request $request, Product $product): array
    {
        return [];
    }

    protected function sanitizePayload(array $data): array
    {
        if (isset($data['des']) && is_array($data['des'])) {
            $data['des'] = array_values(array_filter(array_map('trim', $data['des']), fn ($v) => $v !== ''));
        }
        if (isset($data['size_des']) && is_array($data['size_des'])) {
            $data['size_des'] = array_values(array_filter(array_map('trim', $data['size_des']), fn ($v) => $v !== ''));
        }
        if (array_key_exists('color', $data) && empty($data['color'])) {
            $data['color'] = 'Tự chọn';
        }

        return $data;
    }

    protected function storeUploadsOnProduct(Request $request, Product $product): void
    {
        $hasCover = $product->media()->where('is_cover', true)->exists();

        $coverFile = $request->file('cover_image');
        if ($coverFile instanceof UploadedFile) {
            $path = FileUploadHelper::upload($coverFile, $this->imageDirectory);
            $this->writer->addMedia($product, $path, 'image', true);
            $hasCover = true;
        }

        $files = array_merge(
            $request->file('images', []),
            $request->file('new_images', [])
        );
        if (is_array($files) && ! empty($files)) {
            foreach ($files as $index => $file) {
                if ($file instanceof UploadedFile) {
                    $path = FileUploadHelper::upload($file, $this->imageDirectory);
                    $this->writer->addMedia($product, $path, 'image', ! $hasCover && $index === 0);
                    if ($index === 0) {
                        $hasCover = true;
                    }
                }
            }
        }

        $videoFiles = array_merge(
            $request->file('videos', []),
            $request->file('new_videos', [])
        );
        if (is_array($videoFiles)) {
            $vidDir = ProductGallery::videoDirectoryFromImageDirectory($this->imageDirectory);
            foreach ($videoFiles as $vid) {
                if ($vid instanceof UploadedFile) {
                    $path = FileUploadHelper::upload($vid, $vidDir);
                    $this->writer->addMedia($product, $path, 'video', false);
                }
            }
        }

        $videoUrls = array_merge(
            (array) $request->input('video_urls', []),
            (array) $request->input('new_video_urls', [])
        );
        if (is_array($videoUrls)) {
            foreach ($videoUrls as $url) {
                if (is_string($url) && trim($url) !== '') {
                    $this->writer->addMedia($product, trim($url), 'video', false);
                }
            }
        }
    }

    protected function appendMediaToGallery(Product $product, array $images, array $videoUrls, array $videoFiles, string $directory): Product
    {
        $hasCover = $product->media()->where('is_cover', true)->exists();

        foreach ($images as $file) {
            if ($file instanceof UploadedFile) {
                $path = FileUploadHelper::upload($file, $directory);
                $this->writer->addMedia($product, $path, 'image', ! $hasCover);
                $hasCover = true;
            }
        }

        $vidDir = ProductGallery::videoDirectoryFromImageDirectory($directory);
        foreach ($videoFiles as $vid) {
            if ($vid instanceof UploadedFile) {
                $path = FileUploadHelper::upload($vid, $vidDir);
                $this->writer->addMedia($product, $path, 'video', false);
            }
        }

        foreach ($videoUrls as $url) {
            if (is_string($url) && trim($url) !== '') {
                $this->writer->addMedia($product, trim($url), 'video', false);
            }
        }

        return $product->refresh()->load('media');
    }

    protected function removeGalleryItems(Product $product, array $imagePaths, array $videoUrls, array $videoPaths): Product
    {
        foreach ($imagePaths as $path) {
            $this->writer->removeMedia($product, $path);
            FileUploadHelper::delete($path);
        }
        foreach ($videoUrls as $url) {
            $this->writer->removeMedia($product, $url);
        }
        foreach ($videoPaths as $path) {
            $this->writer->removeMedia($product, $path);
            FileUploadHelper::delete($path);
        }

        return $product->refresh()->load('media');
    }

    protected function reorderGalleryItems(Product $product, array $tokens): Product
    {
        return $this->writer->reorderMedia($product, $tokens);
    }

    protected function promoteCoverImage(Product $product, string $imagePath): Product
    {
        return DB::transaction(function () use ($product, $imagePath): Product {
            $product->media()->increment('sort_order', 10000);

            $target = $product->media()->where('path', $imagePath)->where('kind', 'image')->first();
            if ($target) {
                $product->media()->update(['is_cover' => false]);
                $target->update(['is_cover' => true, 'sort_order' => 0]);

                $others = $product->media()->where('id', '!=', $target->id)->orderBy('sort_order')->get();
                $sort = 1;
                foreach ($others as $other) {
                    $other->update(['sort_order' => $sort++]);
                }
            }

            return $product->refresh()->load('media');
        });
    }
}
