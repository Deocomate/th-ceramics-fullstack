<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\DenVuonGomSuCt;
use App\Services\Concerns\ManagesProductGalleryMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DenVuonGomSuCtService
{
    use ManagesProductGalleryMedia;

    private const IMAGE_DIRECTORY = 'den_vuon_gom_su_ct/images';

    private const SIZE_DIRECTORY = 'den_vuon_gom_su_ct/sizes';

    public function getAll(string $status = 'active')
    {
        $query = DenVuonGomSuCt::query()
            ->with(['phanLoais' => fn ($q) => $q->where('is_delete', 0)->orderBy('price')])
            ->withCount(['phanLoais' => fn ($q) => $q->where('is_delete', 0)])->latest();
        if ($status === 'active') {
            $query->where('is_delete', 0);
        } elseif ($status === 'deleted') {
            $query->where('is_delete', 1);
        }

        return $query->get();
    }

    public function findById(int $id): DenVuonGomSuCt
    {
        return DenVuonGomSuCt::findOrFail($id);
    }

    public function findActiveForClient(int $id): DenVuonGomSuCt
    {
        return $this->clientBaseQuery()
            ->whereKey($id)
            ->firstOrFail();
    }

    public function paginatedForClient(string $categoryType, array $filters = [], string $pageName = 'page'): LengthAwarePaginator
    {
        $query = $this->clientBaseQuery()
            ->where('category_type', $categoryType);

        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $query->where(function (Builder $query) use ($search) {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('size', 'like', "%{$search}%")
                    ->orWhereHas('phanLoais', function (Builder $query) use ($search) {
                        $query
                            ->where('is_delete', 0)
                            ->where('code', 'like', "%{$search}%");
                    });
            });
        }

        match ((string) ($filters['sort'] ?? '')) {
            'price_asc' => $query->orderByRaw('min_price is null')->orderBy('min_price')->orderByDesc('den_vuon_gom_su_ct_id'),
            'price_desc' => $query->orderByDesc('min_price')->orderByDesc('den_vuon_gom_su_ct_id'),
            'name_asc' => $query->orderBy('name')->orderByDesc('den_vuon_gom_su_ct_id'),
            default => $query->orderByDesc('den_vuon_gom_su_ct_id'),
        };

        return $query
            ->paginate(8, ['*'], $pageName)
            ->withQueryString();
    }

    public function relatedForClient(int $productId, string $categoryType, int $limit = 4)
    {
        return $this->clientBaseQuery()
            ->where('category_type', $categoryType)
            ->whereKeyNot($productId)
            ->orderByDesc('den_vuon_gom_su_ct_id')
            ->take($limit)
            ->get();
    }

    private function clientBaseQuery(): Builder
    {
        return DenVuonGomSuCt::query()
            ->where('is_delete', 0)
            ->with(['phanLoais' => fn ($query) => $query->where('is_delete', 0)->orderBy('price')])
            ->withMin(['phanLoais as min_price' => fn ($query) => $query->where('is_delete', 0)], 'price');
    }

    public function create(array $data): DenVuonGomSuCt
    {
        return DB::transaction(function () use ($data) {
            $fillable = [
                'name' => $data['name'], 'color' => trim((string) ($data['color'] ?? '')) ?: 'Tự chọn', 'category_type' => $data['category_type'], 'size' => $data['size'] ?? null,
                'des' => ! empty($data['des']) ? array_values(array_filter(array_map('trim', $data['des']))) : null,
                'size_des' => ! empty($data['size_des']) ? array_values(array_filter(array_map('trim', $data['size_des']))) : null,
                'is_delete' => 0,
                'video' => $this->normalizeJourneyVideo($data['video'] ?? null),
            ];
            $images = [];
            if (! empty($data['images']) && is_array($data['images'])) {
                $images = $this->storeGalleryImages($data['images'], self::IMAGE_DIRECTORY);
            }
            if (! empty($data['video_urls']) && is_array($data['video_urls'])) {
                $images = $this->appendGalleryVideos($images, $data['video_urls']);
            }
            $fillable['images'] = $images;
            if (isset($data['size_image']) && $data['size_image'] instanceof UploadedFile) {
                $fillable['size_image'] = FileUploadHelper::upload($data['size_image'], self::SIZE_DIRECTORY);
            }

            return DenVuonGomSuCt::create($fillable);
        });
    }

    public function update(int $id, array $data): DenVuonGomSuCt
    {
        $model = $this->findById($id);

        return DB::transaction(function () use ($model, $data) {
            $fillable = [
                'name' => $data['name'], 'color' => trim((string) ($data['color'] ?? '')) ?: 'Tự chọn', 'category_type' => $data['category_type'], 'size' => $data['size'] ?? $model->size,
                'des' => isset($data['des']) ? array_values(array_filter(array_map('trim', $data['des']))) : null,
                'size_des' => isset($data['size_des']) ? array_values(array_filter(array_map('trim', $data['size_des']))) : null,
            ];
            if (isset($data['size_image']) && $data['size_image'] instanceof UploadedFile) {
                $fillable['size_image'] = FileUploadHelper::replace($data['size_image'], $model->size_image, self::SIZE_DIRECTORY);
            }
            $currentImages = is_array($model->images) ? $model->images : [];
            $fillable['video'] = $this->normalizeJourneyVideo($data['video'] ?? null);

            $merged = $this->mergeGalleryUpdates($currentImages, $data, self::IMAGE_DIRECTORY);
            if ($merged !== null) {
                $fillable['images'] = $merged;
            }
            $model->update($fillable);

            return $model->fresh();
        });
    }

    public function toggleStatus(int $id, int $status): void
    {
        $model = $this->findById($id);
        $model->update(['is_delete' => $status]);
        if ($status === 1) {
            $model->phanLoais()->update(['is_delete' => 1]);
        }
    }

    public function removeImageFromJson(int $id, string $imagePath): DenVuonGomSuCt
    {
        return $this->removeGalleryImage($this->findById($id), $imagePath);
    }

    public function removeVideoFromJson(int $id, string $videoUrl): DenVuonGomSuCt
    {
        return $this->removeGalleryVideo($this->findById($id), $videoUrl);
    }

    /**
     * @param  array<int, string>  $imagePaths
     * @param  array<int, string>  $videoUrls
     */
    public function removeGalleryItemsFromJson(int $id, array $imagePaths = [], array $videoUrls = []): DenVuonGomSuCt
    {
        return $this->removeGalleryItems($this->findById($id), $imagePaths, $videoUrls);
    }
}
