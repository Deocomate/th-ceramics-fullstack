<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LanCanGomSuCt extends Model
{
    protected $table = 'lan_can_gom_su_ct';

    protected $primaryKey = 'lan_can_gom_su_ct_id';

    protected $fillable = ['name', 'color', 'images', 'des', 'size', 'size_image', 'size_des', 'is_delete'];

    protected $attributes = ['color' => 'Tự chọn'];

    protected function casts(): array
    {
        return ['images' => 'array', 'des' => 'array', 'size_des' => 'array'];
    }

    public function phanLoais(): HasMany
    {
        return $this->hasMany(PhanLoaiLanCanGomSuCt::class, 'lan_can_gom_su_ct_id', 'lan_can_gom_su_ct_id');
    }

    public function getDisplayCodeAttribute(): string
    {
        $code = $this->phanLoais
            ?->firstWhere('is_delete', 0)
            ?->code;

        return $code ?: 'Đang cập nhật';
    }

    public function getDisplayPriceAttribute(): string
    {
        $price = $this->phanLoais
            ?->where('is_delete', 0)
            ->min('price');

        if (! $price) {
            return 'Liên hệ';
        }

        return 'Giá: '.number_format($price, 0, ',', '.').' đ/m²';
    }
}
