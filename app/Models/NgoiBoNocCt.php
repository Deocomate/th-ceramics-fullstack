<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NgoiBoNocCt extends Model
{
    protected $table = 'ngoi_bo_noc_ct';
    protected $primaryKey = 'ngoi_bo_noc_ct_id';

    protected $fillable =[
        'name', 'images', 'des', 'size', 'size_image', 'size_des', 'is_delete',
    ];

    protected function casts(): array
    {
        return[
            'images'   => 'array',
            'des'      => 'array',
            'size_des' => 'array',
        ];
    }

    public function phanLoais(): HasMany
    {
        return $this->hasMany(PhanLoaiNgoiBoNocCt::class, 'ngoi_bo_noc_ct_id', 'ngoi_bo_noc_ct_id');
    }
}