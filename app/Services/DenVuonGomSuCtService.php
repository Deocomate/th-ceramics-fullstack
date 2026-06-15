<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\DenVuonGomSuCt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DenVuonGomSuCtService
{
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
            ];
            $images = [];
            if (! empty($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $images[] = FileUploadHelper::upload($file, 'den_vuon_gom_su_ct/images');
                    }
                }
            }
            $fillable['images'] = $images;
            if (isset($data['size_image']) && $data['size_image'] instanceof UploadedFile) {
                $fillable['size_image'] = FileUploadHelper::upload($data['size_image'], 'den_vuon_gom_su_ct/sizes');
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
                $fillable['size_image'] = FileUploadHelper::replace($data['size_image'], $model->size_image, 'den_vuon_gom_su_ct/sizes');
            }
            if (! empty($data['new_images']) && is_array($data['new_images'])) {
                $currentImages = is_array($model->images) ? $model->images : [];
                foreach ($data['new_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $currentImages[] = FileUploadHelper::upload($file, 'den_vuon_gom_su_ct/images');
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
        if ($status === 1) {
            $model->phanLoais()->update(['is_delete' => 1]);
        }
    }

    public function removeImageFromJson(int $id, string $imagePath): void
    {
        $model = $this->findById($id);
        $currentImages = is_array($model->images) ? $model->images : [];
        $newImages = array_filter($currentImages, fn ($path) => $path !== $imagePath);
        $model->update(['images' => empty($newImages) ? null : array_values($newImages)]);
        FileUploadHelper::delete($imagePath);
    }
}
