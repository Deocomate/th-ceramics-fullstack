<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\Catalog;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CatalogService
{
    public function getAll(int $perPage = 10)
    {
        return Catalog::query()->latest()->paginate($perPage);
    }

    public function findById(int $id): Catalog
    {
        return Catalog::query()->findOrFail($id);
    }

    public function store(array $data): Catalog
    {
        $fillable = [
            'tieu_de' => $data['tieu_de'] ?? null,
        ];

        try {
            if (isset($data['anh_dai_dien']) && $data['anh_dai_dien'] instanceof UploadedFile) {
                $fillable['anh_dai_dien'] = FileUploadHelper::upload($data['anh_dai_dien'], 'catalog/images');
            }

            if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
                $fillable['file'] = FileUploadHelper::upload($data['file'], 'catalog/files');
            }
        } catch (\Throwable $e) {
            Log::error('Catalog file upload failed during store', [
                'error' => $e->getMessage(),
                'data' => [
                    'tieu_de' => $data['tieu_de'] ?? null,
                    'anh_dai_dien' => isset($data['anh_dai_dien']) ? $data['anh_dai_dien']->getClientOriginalName() : null,
                    'file' => isset($data['file']) ? $data['file']->getClientOriginalName() : null,
                ],
            ]);
            throw new \RuntimeException('Không thể lưu file, vui lòng thử lại.', 0, $e);
        }

        return Catalog::query()->create($fillable);
    }

    public function update(int $id, array $data): Catalog
    {
        $model = $this->findById($id);

        $fillable = [
            'tieu_de' => array_key_exists('tieu_de', $data) ? $data['tieu_de'] : $model->tieu_de,
        ];

        try {
            if (isset($data['anh_dai_dien']) && $data['anh_dai_dien'] instanceof UploadedFile) {
                $fillable['anh_dai_dien'] = FileUploadHelper::replace($data['anh_dai_dien'], $model->anh_dai_dien, 'catalog/images');
            }

            if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
                $fillable['file'] = FileUploadHelper::replace($data['file'], $model->file, 'catalog/files');
            }
        } catch (\Throwable $e) {
            Log::error('Catalog file upload/replace failed during update', [
                'catalog_id' => $id,
                'error' => $e->getMessage(),
                'data' => [
                    'tieu_de' => $data['tieu_de'] ?? null,
                    'anh_dai_dien' => isset($data['anh_dai_dien']) ? $data['anh_dai_dien']->getClientOriginalName() : null,
                    'file' => isset($data['file']) ? $data['file']->getClientOriginalName() : null,
                ],
            ]);
            throw new \RuntimeException('Không thể lưu file, vui lòng thử lại.', 0, $e);
        }

        $model->fill($fillable)->save();

        return $model->fresh();
    }

    public function destroy(int $id): void
    {
        $model = $this->findById($id);
        FileUploadHelper::delete($model->anh_dai_dien);
        FileUploadHelper::delete($model->file);

        $model->delete();
    }
}
