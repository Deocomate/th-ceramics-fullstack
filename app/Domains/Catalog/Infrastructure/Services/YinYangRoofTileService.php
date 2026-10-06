<?php

namespace App\Domains\Catalog\Infrastructure\Services;

use App\Domains\Catalog\Infrastructure\Models\YinYangRoofTile;
use App\Domains\Media\Infrastructure\FileUploadHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class YinYangRoofTileService
{
    public function getFirstRecord(): YinYangRoofTile
    {
        return YinYangRoofTile::query()->firstOrFail();
    }

    public function update(array $data): YinYangRoofTile
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

            if (! empty($fillable)) {
                $ngoiAmDuong->fill($fillable)->save();
            }

            return $ngoiAmDuong->fresh();
        });
    }
}
