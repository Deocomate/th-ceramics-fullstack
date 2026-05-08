<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\VeChungToi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class VeChungToiService
{
    public function getFirstRecord(): VeChungToi
    {
        $record = VeChungToi::query()->first();
        if (!$record) {
            $record = VeChungToi::query()->create([
                'banner' => '',
                'header_banner' => '',
                'body_banner' => '',
                'gs_head' => [],
                'gs_gia_tri' =>[],
                'gs_hanh_trinh' =>[],
                'gs_nguoi_sang_lap_anh' => '',
                'gs_nguoi_sang_lap_noi_dung' => '',
                'gs_giai_thuong' =>[],
                'nt_head' => '',
                'nt_body' => '',
                'nt_ngon_ngu' =>[],
                'nt_che_tac_head' => '',
                'nt_che_tac_body' => '',
                'nt_che_tac_anh' =>[],
                'nt_luyen_dat_head' => '',
                'nt_luyen_dat_body' => '',
                'nt_luyen_dat_item' =>[],
                'nt_dun_lo_head' => '',
                'nt_dun_lo_body' => '',
                'nt_dun_lo_anh' =>[],
            ]);
        }
        return $record;
    }

    public function update(array $data): VeChungToi
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data) {
            $fillable = array_intersect_key($data, array_flip([
                'header_banner', 'body_banner', 'gs_nguoi_sang_lap_noi_dung',
                'nt_head', 'nt_body', 'nt_che_tac_head', 'nt_che_tac_body',
                'nt_luyen_dat_head', 'nt_luyen_dat_body', 'nt_dun_lo_head', 'nt_dun_lo_body'
            ]));

            // --- Cập nhật ảnh đơn ---
            if (isset($data['banner']) && $data['banner'] instanceof UploadedFile) {
                $fillable['banner'] = FileUploadHelper::replace($data['banner'], $model->banner, 've_chung_toi/banner');
            }
            if (isset($data['gs_nguoi_sang_lap_anh']) && $data['gs_nguoi_sang_lap_anh'] instanceof UploadedFile) {
                $fillable['gs_nguoi_sang_lap_anh'] = FileUploadHelper::replace($data['gs_nguoi_sang_lap_anh'], $model->gs_nguoi_sang_lap_anh, 've_chung_toi/images');
            }

            // --- Cập nhật các trường JSON có chứa ảnh ---
            $jsonFields =['gs_head', 'gs_gia_tri', 'gs_hanh_trinh', 'gs_giai_thuong', 'nt_ngon_ngu', 'nt_luyen_dat_item'];
            foreach ($jsonFields as $field) {
                if (isset($data[$field])) {
                    $jsonArray =[];
                    foreach ($data[$field] as $item) {
                        $imagePath = $item['old_image'] ?? null;
                        
                        if (isset($item['new_image']) && $item['new_image'] instanceof UploadedFile) {
                            if ($imagePath) {
                                FileUploadHelper::delete($imagePath);
                            }
                            $imagePath = FileUploadHelper::upload($item['new_image'], "ve_chung_toi/{$field}");
                        }

                        // Xử lý giữ lại các dữ liệu text khác
                        $processedItem = ['image' => $imagePath];
                        if (isset($item['head'])) $processedItem['head'] = $item['head'];
                        if (isset($item['body'])) $processedItem['body'] = $item['body'];

                        $jsonArray[] = $processedItem;
                    }
                    $fillable[$field] = $jsonArray;
                } else {
                    $fillable[$field] =[];
                }
            }

            // --- Cập nhật các trường LIST ảnh ---
            $galleries =[
                'nt_che_tac_anh' => 'new_nt_che_tac_anh',
                'nt_dun_lo_anh' => 'new_nt_dun_lo_anh',
            ];

            foreach ($galleries as $dbField => $newField) {
                $existing = is_array($model->{$dbField}) ? $model->{$dbField} :[];
                $deleteField = str_replace('new_', 'delete_', $newField);

                if (!empty($data[$deleteField])) {
                    foreach ($data[$deleteField] as $idx) {
                        if (isset($existing[$idx])) {
                            FileUploadHelper::delete($existing[$idx]);
                            unset($existing[$idx]);
                        }
                    }
                    $existing = array_values($existing);
                }

                if (!empty($data[$newField]) && is_array($data[$newField])) {
                    foreach ($data[$newField] as $file) {
                        if ($file instanceof UploadedFile) {
                            $existing[] = FileUploadHelper::upload($file, "ve_chung_toi/{$dbField}");
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