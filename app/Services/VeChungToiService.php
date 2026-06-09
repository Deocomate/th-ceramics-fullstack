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
        if (! $record) {
            $record = VeChungToi::query()->create([
                'banner' => '',
                'header_banner' => '',
                'body_banner' => '',
                'gs_head' => [], 'gs_gia_tri' => [], 'gs_hanh_trinh' => [],
                'gs_nguoi_sang_lap_anh' => '', 'gs_nguoi_sang_lap_noi_dung' => '', 'gs_giai_thuong' => [],
                'nt_head' => '', 'nt_body' => '', 'nt_ngon_ngu' => [],
                'nt_che_tac_head' => '', 'nt_che_tac_body' => '', 'nt_che_tac_anh' => [],
                'nt_luyen_dat_head' => '', 'nt_luyen_dat_body' => '', 'nt_luyen_dat_item' => [],
                'nt_dun_lo_head' => '', 'nt_dun_lo_body' => '', 'nt_dun_lo_anh' => [],
            ]);
        }

        return $record;
    }

    public function updateSection(string $section, array $data): VeChungToi
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data, $section) {
            $fillable = [];

            // 1. CHỈ CẬP NHẬT PHẦN BANNER
            if ($section === 'banner') {
                if (isset($data['header_banner'])) {
                    $fillable['header_banner'] = $data['header_banner'];
                }
                if (isset($data['body_banner'])) {
                    $fillable['body_banner'] = $data['body_banner'];
                }

                if (isset($data['banner']) && $data['banner'] instanceof UploadedFile) {
                    $fillable['banner'] = FileUploadHelper::replace($data['banner'], $model->banner, 've_chung_toi/banner');
                }
            }

            // 2.1 CHỈ CẬP NHẬT ĐIỂM NHẤN & GIÁ TRỊ CỐT LÕI
            if ($section === 'gom_su_1') {
                $jsonFields = ['gs_head', 'gs_gia_tri'];
                foreach ($jsonFields as $field) {
                    if (isset($data[$field])) {
                        $jsonArray = [];
                        foreach ($data[$field] as $item) {
                            $imagePath = $item['old_image'] ?? null;
                            if (isset($item['new_image']) && $item['new_image'] instanceof UploadedFile) {
                                if ($imagePath) {
                                    FileUploadHelper::delete($imagePath);
                                }
                                $imagePath = FileUploadHelper::upload($item['new_image'], "ve_chung_toi/{$field}");
                            }
                            $processedItem = ['image' => $imagePath];
                            if (isset($item['head'])) {
                                $processedItem['head'] = $item['head'];
                            }
                            if (isset($item['body'])) {
                                $processedItem['body'] = $item['body'];
                            }
                            $jsonArray[] = $processedItem;
                        }
                        $fillable[$field] = $jsonArray;
                    } else {
                        $fillable[$field] = [];
                    }
                }
            }

            // 2.2 CHỈ CẬP NHẬT HÀNH TRÌNH & GIẢI THƯỞNG
            if ($section === 'gom_su_2') {
                $jsonFields = ['gs_hanh_trinh', 'gs_giai_thuong'];
                foreach ($jsonFields as $field) {
                    if (isset($data[$field])) {
                        $jsonArray = [];
                        foreach ($data[$field] as $item) {
                            $imagePath = $item['old_image'] ?? null;
                            if (isset($item['new_image']) && $item['new_image'] instanceof UploadedFile) {
                                if ($imagePath) {
                                    FileUploadHelper::delete($imagePath);
                                }
                                $imagePath = FileUploadHelper::upload($item['new_image'], "ve_chung_toi/{$field}");
                            }
                            $processedItem = ['image' => $imagePath];
                            if (isset($item['head'])) {
                                $processedItem['head'] = $item['head'];
                            }
                            if (isset($item['body'])) {
                                $processedItem['body'] = $item['body'];
                            }
                            $jsonArray[] = $processedItem;
                        }
                        $fillable[$field] = $jsonArray;
                    } else {
                        $fillable[$field] = [];
                    }
                }
            }

            // 2.3 CHỈ CẬP NHẬT NGƯỜI SÁNG LẬP
            if ($section === 'gom_su_3') {
                if (isset($data['gs_nguoi_sang_lap_noi_dung'])) {
                    $fillable['gs_nguoi_sang_lap_noi_dung'] = $data['gs_nguoi_sang_lap_noi_dung'];
                }
                if (isset($data['gs_nguoi_sang_lap_anh']) && $data['gs_nguoi_sang_lap_anh'] instanceof UploadedFile) {
                    $fillable['gs_nguoi_sang_lap_anh'] = FileUploadHelper::replace($data['gs_nguoi_sang_lap_anh'], $model->gs_nguoi_sang_lap_anh, 've_chung_toi/images');
                }
            }

            // 3. CHỈ CẬP NHẬT PHẦN CHẾ TÁC
            if ($section === 'che_tac') {
                $textFields = ['nt_head', 'nt_body', 'nt_che_tac_head', 'nt_che_tac_body', 'nt_luyen_dat_head', 'nt_luyen_dat_body', 'nt_dun_lo_head', 'nt_dun_lo_body'];
                foreach ($textFields as $tf) {
                    if (isset($data[$tf])) {
                        $fillable[$tf] = $data[$tf];
                    }
                }

                $jsonFields = ['nt_ngon_ngu', 'nt_luyen_dat_item'];
                foreach ($jsonFields as $field) {
                    if (isset($data[$field])) {
                        $jsonArray = [];
                        foreach ($data[$field] as $item) {
                            $imagePath = $item['old_image'] ?? null;
                            if (isset($item['new_image']) && $item['new_image'] instanceof UploadedFile) {
                                if ($imagePath) {
                                    FileUploadHelper::delete($imagePath);
                                }
                                $imagePath = FileUploadHelper::upload($item['new_image'], "ve_chung_toi/{$field}");
                            }
                            $processedItem = ['image' => $imagePath];
                            if (isset($item['head'])) {
                                $processedItem['head'] = $item['head'];
                            }
                            if (isset($item['body'])) {
                                $processedItem['body'] = $item['body'];
                            }
                            $jsonArray[] = $processedItem;
                        }
                        $fillable[$field] = $jsonArray;
                    } else {
                        $fillable[$field] = [];
                    }
                }

                $galleries = ['nt_che_tac_anh' => 'new_nt_che_tac_anh', 'nt_dun_lo_anh' => 'new_nt_dun_lo_anh'];
                foreach ($galleries as $dbField => $newField) {
                    $existing = is_array($model->{$dbField}) ? $model->{$dbField} : [];
                    $deleteField = str_replace('new_', 'delete_', $newField);

                    if (! empty($data[$deleteField])) {
                        foreach ($data[$deleteField] as $idx) {
                            if (isset($existing[$idx])) {
                                FileUploadHelper::delete($existing[$idx]);
                                unset($existing[$idx]);
                            }
                        }
                        $existing = array_values($existing);
                    }

                    if (! empty($data[$newField]) && is_array($data[$newField])) {
                        foreach ($data[$newField] as $file) {
                            if ($file instanceof UploadedFile) {
                                $existing[] = FileUploadHelper::upload($file, "ve_chung_toi/{$dbField}");
                            }
                        }
                    }
                    $fillable[$dbField] = $existing;
                }
            }

            if (! empty($fillable)) {
                $model->update($fillable);
            }

            return $model->fresh();
        });
    }
}
