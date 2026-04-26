<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\DauAnGachTrangTri;
use App\Models\GachTrangTri;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class GachTrangTriService
{
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return GachTrangTri::with('dauAn')->latest()->paginate($perPage);
    }

    public function findById(int $id): GachTrangTri
    {
        return GachTrangTri::with('dauAn')->findOrFail($id);
    }

    public function create(array $data): GachTrangTri
    {
        return DB::transaction(function () use ($data) {
            $thumbnailMain = FileUploadHelper::upload($data['thumbnail_main'], 'gach_trang_tri/images');

            return GachTrangTri::create([
                'thumbnail_main' => $thumbnailMain,
                'video'          => $data['video'] ?? null,
            ]);
        });
    }

    public function update(int $id, array $data): GachTrangTri
    {
        $model = $this->findById($id);

        return DB::transaction(function () use ($model, $data) {
            $fillable = [];

            if (isset($data['thumbnail_main']) && $data['thumbnail_main'] instanceof UploadedFile) {
                $fillable['thumbnail_main'] = FileUploadHelper::replace($data['thumbnail_main'], $model->thumbnail_main, 'gach_trang_tri/images');
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
            foreach ($model->dauAn as $dauAn) {
                FileUploadHelper::delete($dauAn->background);
            }

            FileUploadHelper::delete($model->thumbnail_main);
            $model->delete();
        });
    }

    // --- DẤU ẤN GẠCH TRANG TRÍ ---

    public function addDauAn(int $gachId, array $data): DauAnGachTrangTri
    {
        $model = $this->findById($gachId);
        
        $backgroundPath = FileUploadHelper::upload($data['background'], 'gach_trang_tri/dau_an');

        return $model->dauAn()->create([
            'background'  => $backgroundPath,
            'title'       => $data['title'],
            'location'    => $data['location'],
            'description' => $data['description'],
        ]);
    }

    public function updateDauAn(int $dauAnId, array $data): DauAnGachTrangTri
    {
        $dauAn = DauAnGachTrangTri::findOrFail($dauAnId);
        
        $fillable = [
            'title'       => $data['title'] ?? $dauAn->title,
            'location'    => $data['location'] ?? $dauAn->location,
            'description' => $data['description'] ?? $dauAn->description,
        ];

        if (isset($data['background']) && $data['background'] instanceof UploadedFile) {
            $fillable['background'] = FileUploadHelper::replace($data['background'], $dauAn->background, 'gach_trang_tri/dau_an');
        }

        $dauAn->update($fillable);

        return $dauAn->fresh();
    }

    public function deleteDauAn(int $dauAnId): void
    {
        $dauAn = DauAnGachTrangTri::findOrFail($dauAnId);
        FileUploadHelper::delete($dauAn->background);
        $dauAn->delete();
    }
}