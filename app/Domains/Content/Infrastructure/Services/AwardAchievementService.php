<?php

namespace App\Domains\Content\Infrastructure\Services;

use App\Domains\Content\Infrastructure\Models\AwardAchievement;
use App\Domains\Media\Infrastructure\FileUploadHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class AwardAchievementService
{
    /** @return Collection<int, AwardAchievement> */
    public function getAll(): Collection
    {
        return AwardAchievement::query()->latest()->get();
    }

    public function findById(int $id): AwardAchievement
    {
        return AwardAchievement::query()->findOrFail($id);
    }

    /** @param array<string, mixed> $data */
    public function store(array $data): AwardAchievement
    {
        $imagePath = FileUploadHelper::upload($data['image'], 'giai_thuong_thanh_tuu/images');

        /** @var AwardAchievement $model */
        $model = AwardAchievement::query()->create([
            'image' => $imagePath,
            'des' => $data['des'],
        ]);

        return $model;
    }

    /** @param array<string, mixed> $data */
    public function update(int $id, array $data): AwardAchievement
    {
        $model = $this->findById($id);
        $fillable = [
            'des' => $data['des'] ?? $model->des,
        ];

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $fillable['image'] = FileUploadHelper::replace($data['image'], $model->image, 'giai_thuong_thanh_tuu/images');
        }

        $model->fill($fillable)->save();

        return $model->fresh();
    }

    public function destroy(int $id): void
    {
        $model = $this->findById($id);
        FileUploadHelper::delete($model->image);

        AwardAchievement::destroy($id);
    }
}
