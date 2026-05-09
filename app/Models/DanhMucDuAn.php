<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DanhMucDuAn extends Model
{
    protected $table = 'danh_muc_du_an';
    protected $primaryKey = 'danh_muc_du_an_id';

    protected $fillable =[
        'ten_danh_muc',
        'is_delete',
    ];

    public function duAns(): HasMany
    {
        return $this->hasMany(DuAn::class, 'danh_muc_du_an_id', 'danh_muc_du_an_id');
    }
}