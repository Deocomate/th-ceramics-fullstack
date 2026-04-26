<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\GachCoBatTrang;
use App\Models\GachCoBatTrangAnh;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class GachCoBatTrangService
{
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return GachCoBatTrang::with('anh')->latest()->paginate($perPage);
    }

    public function findById(int $id): GachCoBatTrang
    {
        return GachCoBatTrang::with('anh')->findOrFail($id);
    }

    public function create(array $data): GachCoBatTrang
    {
        return DB::transaction(function () use ($data) {
            $thumbnailMain = FileUploadHelper::upload($data['thumbnail_main'], 'gach_co_bat_trang/images');

            return GachCoBatTrang::create([
                'thumbnail_main' => $thumbnailMain,
                'video'          => $data['video'] ?? null,
            ]);
        });
    }

    public function update(int $id, array $data): GachCoBatTrang
    {
        $model = $this->findById($id);

        return DB::transaction(function () use ($model, $data) {
            $fillable = [];

            if (isset($data['thumbnail_main']) && $data['thumbnail_main'] instanceof UploadedFile) {
                $fillable['thumbnail_main'] = FileUploadHelper::replace($data['thumbnail_main'], $model->thumbnail_main, 'gach_co_bat_trang/images');
            }

            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }

            if (!empty($fillable)) {
                $model->update($fillable);
            }

            return $model->fresh();
        });
    }

    public function delete(int $id): void
    {
        $model = $this->findById($id);

        DB::transaction(function () use ($model) {
            foreach ($model->anh as $anh) {
                FileUploadHelper::delete($anh->image);
            }

            FileUploadHelper::delete($model->thumbnail_main);
            $model->delete();
        });
    }

    // --- THƯ VIỆN ẢNH GẠCH CỔ BÁT TRÀNG ---

    public function addAnh(int $gachId, array $data): GachCoBatTrangAnh
    {
        $model = $this->findById($gachId);
        $imagePath = FileUploadHelper::upload($data['image'], 'gach_co_bat_trang/gallery');

        return $model->anh()->create(['image' => $imagePath]);
    }

    public function deleteAnh(int $anhId): void
    {
        $anh = GachCoBatTrangAnh::findOrFail($anhId);
        FileUploadHelper::delete($anh->image);
        $anh->delete();
    }
}