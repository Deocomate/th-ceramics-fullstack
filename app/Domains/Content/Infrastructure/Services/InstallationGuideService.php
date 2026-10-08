<?php

namespace App\Domains\Content\Infrastructure\Services;

use App\Domains\Content\Infrastructure\Models\InstallationGuide;
use App\Domains\Media\Infrastructure\FileUploadHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

class InstallationGuideService
{
    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return InstallationGuide::query()->latest()->paginate($perPage);
    }

    public function findById(int $id): InstallationGuide
    {
        return InstallationGuide::query()->findOrFail($id);
    }

    /** @param array<string, mixed> $data */
    public function store(array $data): InstallationGuide
    {
        $imagePath = FileUploadHelper::upload($data['anh'], 'thi_cong/images');

        return InstallationGuide::query()->create([
            'tieu_de' => $data['tieu_de'],
            'link_youtube' => $data['link_youtube'] ?? null,
            'anh' => $imagePath,
        ]);
    }

    /** @param array<string, mixed> $data */
    public function update(int $id, array $data): InstallationGuide
    {
        $model = $this->findById($id);

        $fillable = [
            'tieu_de' => $data['tieu_de'] ?? $model->tieu_de,
            'link_youtube' => array_key_exists('link_youtube', $data) ? $data['link_youtube'] : $model->link_youtube,
        ];

        if (isset($data['anh']) && $data['anh'] instanceof UploadedFile) {
            $fillable['anh'] = FileUploadHelper::replace($data['anh'], $model->anh, 'thi_cong/images');
        }

        $model->fill($fillable)->save();

        return $model->fresh();
    }

    public function destroy(int $id): void
    {
        $model = $this->findById($id);
        FileUploadHelper::delete($model->anh);

        $model->delete();
    }
}
