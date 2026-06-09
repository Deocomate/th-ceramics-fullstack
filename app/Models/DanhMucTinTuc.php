<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DanhMucTinTuc extends Model
{
    protected $table = 'danh_muc_tin_tuc';

    protected $primaryKey = 'danh_muc_tin_tuc_id';

    protected $fillable = [
        'ten_danh_muc',
        'is_delete',
    ];

    public function tinTucs(): HasMany
    {
        return $this->hasMany(TinTuc::class, 'danh_muc_tin_tuc_id', 'danh_muc_tin_tuc_id');
    }
}
