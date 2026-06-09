<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DenVuonGomSuCt extends Model
{
    public const CATEGORY_DEN_GOM = 'den_gom';

    public const CATEGORY_DEN_SU = 'den_su';

    protected $table = 'den_vuon_gom_su_ct';

    protected $primaryKey = 'den_vuon_gom_su_ct_id';

    protected $fillable = ['name', 'color', 'category_type', 'images', 'des', 'size', 'size_image', 'size_des', 'is_delete'];

    protected $attributes = ['color' => 'Tự chọn'];

    protected function casts(): array
    {
        return ['images' => 'array', 'des' => 'array', 'size_des' => 'array'];
    }

    public function phanLoais(): HasMany
    {
        return $this->hasMany(PhanLoaiDenVuonGomSuCt::class, 'den_vuon_gom_su_ct_id', 'den_vuon_gom_su_ct_id');
    }

    public function activePhanLoais(): HasMany
    {
        return $this->phanLoais()->where('is_delete', 0)->orderBy('price');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category_type) {
            self::CATEGORY_DEN_SU => 'Đèn Sứ',
            default => 'Đèn Gốm',
        };
    }

    public function getDisplayVariantAttribute(): ?PhanLoaiDenVuonGomSuCt
    {
        $phanLoais = $this->relationLoaded('phanLoais')
            ? $this->phanLoais
            : $this->phanLoais()->where('is_delete', 0)->get();

        return $phanLoais->where('is_delete', 0)->sortBy('price')->first();
    }

    public function getDisplayCodeAttribute(): ?string
    {
        return $this->display_variant?->code;
    }

    public function getDisplayPriceAttribute(): string
    {
        $price = $this->min_price ?? $this->display_variant?->price;

        return $price > 0 ? 'Từ '.number_format((float) $price, 0, ',', '.').' đ' : 'Liên hệ';
    }
}
