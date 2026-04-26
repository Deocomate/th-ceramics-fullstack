<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\GiaTriNgoiAmDuong;
use App\Models\NgoiAmDuong;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class NgoiAmDuongService
{
    /**
     * Lấy bản ghi duy nhất.
     */
    public function getFirstRecord(): NgoiAmDuong
    {
        return NgoiAmDuong::with('giaTri')->firstOrFail();
    }

    public function findGiaTriById(int $id): GiaTriNgoiAmDuong
    {
        return GiaTriNgoiAmDuong::findOrFail($id);
    }

    public function update(array $data): NgoiAmDuong
    {
        $ngoiAmDuong = $this->getFirstRecord();

        return DB::transaction(function () use ($ngoiAmDuong, $data) {
            $fillable = [];

            if (isset($data['thumbnail_main']) && $data['thumbnail_main'] instanceof UploadedFile) {
                $fillable['thumbnail_main'] = FileUploadHelper::replace($data['thumbnail_main'], $ngoiAmDuong->thumbnail_main, 'ngoi_am_duong/images');
            }
            if (isset($data['thumbnail1']) && $data['thumbnail1'] instanceof UploadedFile) {
                $fillable['thumbnail1'] = FileUploadHelper::replace($data['thumbnail1'], $ngoiAmDuong->thumbnail1, 'ngoi_am_duong/images');
            }
            if (isset($data['thumbnail2']) && $data['thumbnail2'] instanceof UploadedFile) {
                $fillable['thumbnail2'] = FileUploadHelper::replace($data['thumbnail2'], $ngoiAmDuong->thumbnail2, 'ngoi_am_duong/images');
            }
            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }

            if (!empty($fillable)) {
                $ngoiAmDuong->update($fillable);
            }
            return $ngoiAmDuong->fresh();
        });
    }

    public function addGiaTri(array $data): GiaTriNgoiAmDuong
    {
        $ngoiAmDuong = $this->getFirstRecord();
        $imagePath = FileUploadHelper::upload($data['image'], 'ngoi_am_duong/gia_tri');

        return $ngoiAmDuong->giaTri()->create([
            'title'        => $data['title'],
            'desscription' => $data['desscription'],
            'image'        => $imagePath,
        ]);
    }

    public function updateGiaTri(int $giaTriId, array $data): GiaTriNgoiAmDuong
    {
        $giaTri = $this->findGiaTriById($giaTriId);
        $fillable = ['title' => $data['title'], 'desscription' => $data['desscription']];

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $fillable['image'] = FileUploadHelper::replace($data['image'], $giaTri->image, 'ngoi_am_duong/gia_tri');
        }
        $giaTri->update($fillable);
        return $giaTri->fresh();
    }

    public function deleteGiaTri(int $giaTriId): void
    {
        $giaTri = $this->findGiaTriById($giaTriId);
        FileUploadHelper::delete($giaTri->image);
        $giaTri->delete();
    }
}