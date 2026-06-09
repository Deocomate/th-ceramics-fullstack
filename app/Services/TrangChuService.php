<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\TrangChu;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class TrangChuService
{
    public function getFirstRecord(): TrangChu
    {
        $record = TrangChu::query()->first();
        if (! $record) {
            $record = TrangChu::query()->create([
                'banner' => [],
                'khach_hang_doi_tac' => [],
                'loi_tri_an' => [],
                'loi_tri_an_anh' => '',
                've_chung_toi_logo' => [],
                'video' => null,
                'nhung_con_so' => [],
                'showroom_images' => [],
                'showroom_noidung' => null,
            ]);
        }

        return $record;
    }

    public function update(array $data): TrangChu
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data) {
            $fillable = [];

            if (isset($data['loi_tri_an_anh']) && $data['loi_tri_an_anh'] instanceof UploadedFile) {
                $fillable['loi_tri_an_anh'] = FileUploadHelper::replace($data['loi_tri_an_anh'], $model->loi_tri_an_anh, 'trang_chu/images');
            }

            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }
            if (array_key_exists('showroom_noidung', $data)) {
                $fillable['showroom_noidung'] = $data['showroom_noidung'];
            }

            if (isset($data['loi_tri_an'])) {
                $fillable['loi_tri_an'] = array_values(array_filter(array_map('trim', $data['loi_tri_an'])));
            } else {
                $fillable['loi_tri_an'] = [];
            }

            if (isset($data['nhung_con_so'])) {
                $nhungConSo = [];
                foreach ($data['nhung_con_so'] as $item) {
                    $head = trim($item['head'] ?? '');
                    $body = trim($item['body'] ?? '');
                    if ($head !== '' || $body !== '') {
                        $nhungConSo[] = [
                            'head' => $head,
                            'body' => $body,
                        ];
                    }
                }
                $fillable['nhung_con_so'] = $nhungConSo;
            } else {
                $fillable['nhung_con_so'] = [];
            }

            $galleries = [
                'banner' => 'new_banner',
                'khach_hang_doi_tac' => 'new_khach_hang',
                've_chung_toi_logo' => 'new_ve_chung_toi_logo',
                'showroom_images' => 'new_showroom_images',
            ];

            foreach ($galleries as $dbField => $newField) {
                $existing = is_array($model->{$dbField}) ? $model->{$dbField} : [];
                $deleteField = str_replace('new_', 'delete_', $newField);

                // Check xóa hình ảnh (Lọc các id đánh dấu và gọi Helper xóa file vật lý)
                if (! empty($data[$deleteField])) {
                    foreach ($data[$deleteField] as $idx) {
                        $idx = (int) $idx;
                        if (isset($existing[$idx])) {
                            FileUploadHelper::delete($existing[$idx]);
                            unset($existing[$idx]);
                        }
                    }
                    $existing = array_values($existing); // Reset lại mảng
                }

                // Check thêm hình ảnh
                if (! empty($data[$newField]) && is_array($data[$newField])) {
                    foreach ($data[$newField] as $file) {
                        if ($file instanceof UploadedFile) {
                            $existing[] = FileUploadHelper::upload($file, "trang_chu/{$dbField}");
                        }
                    }
                }
                $fillable[$dbField] = $existing;
            }

            $model->update($fillable);

            return $model->fresh();
        });
    }
}
