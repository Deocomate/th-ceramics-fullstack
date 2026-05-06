<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NgoiHaiCoCt extends Model
{
    protected $table = 'ngoi_hai_co_ct';
    protected $primaryKey = 'ngoi_hai_co_ct_id';
    protected $fillable =[
        'name', 'images', 'des', 'size', 'size_image', 'is_delete',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'des'    => 'array',
        ];
    }

    public function mauSacs(): HasMany
    {
        return $this->hasMany(MauSacNgoiHaiCoCt::class, 'ngoi_hai_co_ct_id', 'ngoi_hai_co_ct_id');
    }
}