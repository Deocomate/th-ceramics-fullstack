<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\NgoiHaiVanMieu;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class NgoiHaiVanMieuService
{
    public function getFirstRecord(): NgoiHaiVanMieu
    {
        return NgoiHaiVanMieu::firstOrFail();
    }

    public function update(array $data): NgoiHaiVanMieu
    {
        $ngoiHai = $this->getFirstRecord();

        return DB::transaction(function () use ($ngoiHai, $data) {
            $fillable =[
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
            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }

            $ngoiHai->update($fillable);
            return $ngoiHai->fresh();
        });
    }
}