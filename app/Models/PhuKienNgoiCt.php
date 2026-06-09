<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhuKienNgoiCt extends Model
{
    public const TYPE_BO_NOC = 'bo_noc';

    public const TYPE_CHU_VAN = 'chu_van';

    protected $table = 'phu_kien_ngoi_ct';

    protected $primaryKey = 'phu_kien_ngoi_ct_id';

    protected $fillable = [
        'name',
        'category_type',
        'legacy_type',
        'legacy_id',
        'color',
        'images',
        'des',
        'size',
        'size_image',
        'size_des',
        'is_delete',
    ];

    protected $attributes = [
        'category_type' => self::TYPE_BO_NOC,
        'color' => 'Tự chọn',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'des' => 'array',
            'size_des' => 'array',
        ];
    }

    public function phanLoais(): HasMany
    {
        return $this->hasMany(PhanLoaiPhuKienNgoiCt::class, 'phu_kien_ngoi_ct_id', 'phu_kien_ngoi_ct_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_delete', 0);
    }

    public function scopeCategory(Builder $query, string $categoryType): Builder
    {
        return $query->where('category_type', $categoryType);
    }

    public static function categoryLabel(string $categoryType): string
    {
        return match ($categoryType) {
            self::TYPE_CHU_VAN => 'Bò Nóc Chữ Vạn',
            default => 'Ngói Bò Nóc',
        };
    }

    public static function categoryCodePrefix(string $categoryType): string
    {
        return match ($categoryType) {
            self::TYPE_CHU_VAN => 'PKN-CV',
            default => 'PKN-BN',
        };
    }

    public function getDisplayCodeAttribute(): string
    {
        $code = $this->relationLoaded('phanLoais')
            ? $this->phanLoais->firstWhere('is_delete', 0)?->code
            : $this->phanLoais()->where('is_delete', 0)->first()?->code;

        return $code ?: (match ($this->category_type) {
            self::TYPE_CHU_VAN => 'PKN-CV'.$this->phu_kien_ngoi_ct_id,
            default => 'PKN-BN'.$this->phu_kien_ngoi_ct_id,
        });
    }

    public function getDisplayPriceAttribute(): string
    {
        $price = $this->relationLoaded('phanLoais')
            ? $this->phanLoais->where('is_delete', 0)->min('price')
            : $this->phanLoais()->where('is_delete', 0)->min('price');

        return $price > 0 ? 'Giá: '.number_format((float) $price, 0, ',', '.').' đ/m²' : 'Giá: Liên hệ';
    }
}
