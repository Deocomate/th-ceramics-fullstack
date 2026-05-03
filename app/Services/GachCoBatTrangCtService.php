<?php
namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\GachCoBatTrangCt;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class GachCoBatTrangCtService
{
    public function __construct(private readonly GlobalProductCodeService $globalCodeService) {}

    public function getAll(string $status = 'active')
    {
        $query = GachCoBatTrangCt::query()->latest();

        if ($status === 'active') {
            $query->where('is_delete', 0);
        } elseif ($status === 'deleted') {
            $query->where('is_delete', 1);
        }
        
        return $query->get();
    }

    public function findById(int $id): GachCoBatTrangCt
    {
        return GachCoBatTrangCt::findOrFail($id);
    }

    public function create(array $data): GachCoBatTrangCt
    {
        if (!$this->globalCodeService->isUnique($data['code'])) {
            throw new InvalidArgumentException('Mã sản phẩm (Code) đã tồn tại trên hệ thống.');
        }

        return DB::transaction(function () use ($data) {
            $fillable =[
                'code'      => $data['code'],
                'name'      => $data['name'],
                'price'     => $data['price'],
                'size'      => $data['size'] ?? null,
                'des'       => !empty($data['des']) ? array_values(array_filter(array_map('trim', $data['des']))) : null,
                'is_delete' => 0,
            ];

            $images =[];
            if (!empty($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $images[] = FileUploadHelper::upload($file, 'gach_co_bat_trang_ct/images');
                    }
                }
            }
            $fillable['images'] = $images;

            if (isset($data['size_image']) && $data['size_image'] instanceof UploadedFile) {
                $fillable['size_image'] = FileUploadHelper::upload($data['size_image'], 'gach_co_bat_trang_ct/sizes');
            }

            return GachCoBatTrangCt::create($fillable);
        });
    }

    public function update(int $id, array $data): GachCoBatTrangCt
    {
        $model = $this->findById($id);

        if (!$this->globalCodeService->isUnique($data['code'], 'gach_co_bat_trang_ct', $model->gach_co_bat_trang_ct_id)) {
            throw new InvalidArgumentException('Mã sản phẩm (Code) này đã được sử dụng ở một sản phẩm khác.');
        }

        return DB::transaction(function () use ($model, $data) {
            $fillable = [
                'code'  => $data['code'],
                'name'  => $data['name'],
                'price' => $data['price'],
                'size'  => $data['size'] ?? $model->size,
                'des'   => isset($data['des']) ? array_values(array_filter(array_map('trim', $data['des']))) : null,
            ];

            if (isset($data['size_image']) && $data['size_image'] instanceof UploadedFile) {
                $fillable['size_image'] = FileUploadHelper::replace($data['size_image'], $model->size_image, 'gach_co_bat_trang_ct/sizes');
            }

            if (!empty($data['new_images']) && is_array($data['new_images'])) {
                $currentImages = is_array($model->images) ? $model->images :[];
                foreach ($data['new_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $currentImages[] = FileUploadHelper::upload($file, 'gach_co_bat_trang_ct/images');
                    }
                }
                $fillable['images'] = $currentImages;
            }

            $model->update($fillable);
            return $model->fresh();
        });
    }

    public function toggleStatus(int $id, int $status): void
    {
        $model = $this->findById($id);
        $model->update(['is_delete' => $status]);
    }

    public function removeImageFromJson(int $id, string $imagePathToRemove): GachCoBatTrangCt
    {
        $model = $this->findById($id);
        $currentImages = is_array($model->images) ? $model->images :[];

        $newImages = array_filter($currentImages, fn($path) => $path !== $imagePathToRemove);
        $model->update(['images' => empty($newImages) ? null : array_values($newImages)]);
        FileUploadHelper::delete($imagePathToRemove);

        return $model->fresh();
    }
}