<?php
namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\LanCanGomXu;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class LanCanGomXuService
{
    public function getFirstRecord(): LanCanGomXu
    {
        // Thêm query()
        return LanCanGomXu::query()->firstOrFail();
    }

    public function update(array $data): LanCanGomXu
    {
        $model = $this->getFirstRecord();
        
        return DB::transaction(function () use ($model, $data) {
            $fillable =[];
            if (isset($data['thumbnail_main']) && $data['thumbnail_main'] instanceof UploadedFile) {
                $fillable['thumbnail_main'] = FileUploadHelper::replace($data['thumbnail_main'], $model->thumbnail_main, 'lan_can_gom_xu/images');
            }
            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }
            
            if (!empty($fillable)) {
                // Thay update() bằng fill()->save()
                $model->fill($fillable)->save();
            }
            
            return $model->fresh();
        });
    }
}