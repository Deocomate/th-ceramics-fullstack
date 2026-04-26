<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\GiaTriNgoiAmDuong;
use App\Models\NgoiAmDuong;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class NgoiAmDuongService
{
    /**
     * Lấy danh sách phân trang.
     */
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return NgoiAmDuong::with('giaTri')->latest()->paginate($perPage);
    }

    /**
     * Lấy chi tiết theo ID.
     */
    public function findById(int $id): NgoiAmDuong
    {
        return NgoiAmDuong::with('giaTri')->findOrFail($id);
    }

    /**
     * Lấy chi tiết Giá trị theo ID.
     */
    public function findGiaTriById(int $id): GiaTriNgoiAmDuong
    {
        return GiaTriNgoiAmDuong::findOrFail($id);
    }

    /**
     * Tạo mới bản ghi.
     * 
     * @param array{thumbnail_main: UploadedFile, thumbnail1: UploadedFile, thumbnail2: UploadedFile, video?: string|null} $data
     */
    public function create(array $data): NgoiAmDuong
    {
        return DB::transaction(function () use ($data) {
            $thumbnailMain = FileUploadHelper::upload($data['thumbnail_main'], 'ngoi_am_duong/images');
            $thumbnail1 = FileUploadHelper::upload($data['thumbnail1'], 'ngoi_am_duong/images');
            $thumbnail2 = FileUploadHelper::upload($data['thumbnail2'], 'ngoi_am_duong/images');

            return NgoiAmDuong::create([
                'thumbnail_main' => $thumbnailMain,
                'thumbnail1'     => $thumbnail1,
                'thumbnail2'     => $thumbnail2,
                'video'          => $data['video'] ?? null, // Lưu link video dưới dạng string
            ]);
        });
    }

    /**
     * Cập nhật bản ghi.
     * 
     * @param array{thumbnail_main?: UploadedFile, thumbnail1?: UploadedFile, thumbnail2?: UploadedFile, video?: string|null} $data
     */
    public function update(int $id, array $data): NgoiAmDuong
    {
        $ngoiAmDuong = $this->findById($id);

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

            // Cập nhật link video nếu có request gửi lên
            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }

            if (!empty($fillable)) {
                $ngoiAmDuong->update($fillable);
            }

            return $ngoiAmDuong->fresh();
        });
    }

    /**
     * Xóa bản ghi (cascade sẽ xóa luôn GiaTriNgoiAmDuong, nhưng cần xóa file vật lý).
     */
    public function delete(int $id): void
    {
        $ngoiAmDuong = $this->findById($id);

        DB::transaction(function () use ($ngoiAmDuong) {
            // 1. Xóa file của các Giá Trị liên quan
            foreach ($ngoiAmDuong->giaTri as $giaTri) {
                FileUploadHelper::delete($giaTri->image);
            }

            // 2. Xóa các file ảnh của Ngói Âm Dương
            FileUploadHelper::delete($ngoiAmDuong->thumbnail_main);
            FileUploadHelper::delete($ngoiAmDuong->thumbnail1);
            FileUploadHelper::delete($ngoiAmDuong->thumbnail2);

            // 3. Xóa record DB (Cascade constraint sẽ tự drop DB con)
            $ngoiAmDuong->delete();
        });
    }

    /**
     * Thêm mới một Giá Trị cho Ngói Âm Dương.
     * 
     * @param array{title: string, desscription: string, image: UploadedFile} $data
     */
    public function addGiaTri(int $ngoiAmDuongId, array $data): GiaTriNgoiAmDuong
    {
        $ngoiAmDuong = $this->findById($ngoiAmDuongId);
        
        $imagePath = FileUploadHelper::upload($data['image'], 'ngoi_am_duong/gia_tri');

        return $ngoiAmDuong->giaTri()->create([
            'title'        => $data['title'],
            'desscription' => $data['desscription'],
            'image'        => $imagePath,
        ]);
    }

    /**
     * Cập nhật Giá Trị.
     * 
     * @param array{title: string, desscription: string, image?: UploadedFile} $data
     */
    public function updateGiaTri(int $giaTriId, array $data): GiaTriNgoiAmDuong
    {
        $giaTri = $this->findGiaTriById($giaTriId);
        
        $fillable =[
            'title'        => $data['title'],
            'desscription' => $data['desscription'],
        ];

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $fillable['image'] = FileUploadHelper::replace($data['image'], $giaTri->image, 'ngoi_am_duong/gia_tri');
        }

        $giaTri->update($fillable);

        return $giaTri->fresh();
    }

    /**
     * Xóa một Giá Trị.
     */
    public function deleteGiaTri(int $giaTriId): void
    {
        $giaTri = $this->findGiaTriById($giaTriId);
        
        FileUploadHelper::delete($giaTri->image);
        $giaTri->delete();
    }
}