<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\NgoiHaiVanMieu;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class NgoiHaiVanMieuService
{
    /**
     * Lấy danh sách phân trang.
     */
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return NgoiHaiVanMieu::latest()->paginate($perPage);
    }

    /**
     * Lấy chi tiết theo ID.
     */
    public function findById(int $id): NgoiHaiVanMieu
    {
        return NgoiHaiVanMieu::findOrFail($id);
    }

    /**
     * Tạo mới bản ghi.
     * 
     * @param array{thumbnail_main: UploadedFile, title1: string, thumbnail1: UploadedFile, title2: string, thumbnail2: UploadedFile, title3: string, thumbnail3: UploadedFile, video: string} $data
     */
    public function create(array $data): NgoiHaiVanMieu
    {
        return DB::transaction(function () use ($data) {
            $thumbnailMain = FileUploadHelper::upload($data['thumbnail_main'], 'ngoi_hai_van_mieu/images');
            $thumbnail1 = FileUploadHelper::upload($data['thumbnail1'], 'ngoi_hai_van_mieu/images');
            $thumbnail2 = FileUploadHelper::upload($data['thumbnail2'], 'ngoi_hai_van_mieu/images');
            $thumbnail3 = FileUploadHelper::upload($data['thumbnail3'], 'ngoi_hai_van_mieu/images');

            return NgoiHaiVanMieu::create([
                'thumbnail_main' => $thumbnailMain,
                'title1'         => $data['title1'],
                'thumbnail1'     => $thumbnail1,
                'title2'         => $data['title2'],
                'thumbnail2'     => $thumbnail2,
                'title3'         => $data['title3'],
                'thumbnail3'     => $thumbnail3,
                'video'          => $data['video'] ?? '', // Điền chuỗi link video
            ]);
        });
    }

    /**
     * Cập nhật bản ghi.
     */
    public function update(int $id, array $data): NgoiHaiVanMieu
    {
        $ngoiHai = $this->findById($id);

        return DB::transaction(function () use ($ngoiHai, $data) {
            $fillable = [
                'title1' => $data['title1'] ?? $ngoiHai->title1,
                'title2' => $data['title2'] ?? $ngoiHai->title2,
                'title3' => $data['title3'] ?? $ngoiHai->title3,
            ];

            if (isset($data['thumbnail_main']) && $data['thumbnail_main'] instanceof UploadedFile) {
                $fillable['thumbnail_main'] = FileUploadHelper::replace($data['thumbnail_main'], $ngoiHai->thumbnail_main, 'ngoi_hai_van_mieu/images');
            }

            if (isset($data['thumbnail1']) && $data['thumbnail1'] instanceof UploadedFile) {
                $fillable['thumbnail1'] = FileUploadHelper::replace($data['thumbnail1'], $ngoiHai->thumbnail1, 'ngoi_hai_van_mieu/images');
            }

            if (isset($data['thumbnail2']) && $data['thumbnail2'] instanceof UploadedFile) {
                $fillable['thumbnail2'] = FileUploadHelper::replace($data['thumbnail2'], $ngoiHai->thumbnail2, 'ngoi_hai_van_mieu/images');
            }

            if (isset($data['thumbnail3']) && $data['thumbnail3'] instanceof UploadedFile) {
                $fillable['thumbnail3'] = FileUploadHelper::replace($data['thumbnail3'], $ngoiHai->thumbnail3, 'ngoi_hai_van_mieu/images');
            }

            // Cập nhật string link video
            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }

            $ngoiHai->update($fillable);

            return $ngoiHai->fresh();
        });
    }

    /**
     * Xóa bản ghi và xóa file vật lý.
     */
    public function delete(int $id): void
    {
        $ngoiHai = $this->findById($id);

        DB::transaction(function () use ($ngoiHai) {
            FileUploadHelper::delete($ngoiHai->thumbnail_main);
            FileUploadHelper::delete($ngoiHai->thumbnail1);
            FileUploadHelper::delete($ngoiHai->thumbnail2);
            FileUploadHelper::delete($ngoiHai->thumbnail3);

            $ngoiHai->delete();
        });
    }
}