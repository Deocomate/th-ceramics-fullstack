<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NgoiHaiCoCt extends Model
{
    protected $table = 'ngoi_hai_co_ct';

    protected $primaryKey = 'ngoi_hai_co_ct_id';

    protected $fillable = [
        'name', 'color', 'images', 'des', 'size', 'size_image', 'is_delete',
    ];

    protected $attributes = [
        'color' => 'Tự chọn',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'des' => 'array',
        ];
    }

    public function getCodeAttribute(): ?string
    {
        $firstColor = $this->relationLoaded('mauSacs')
            ? $this->mauSacs->first(fn ($m) => $m->is_delete === 0)
            : $this->mauSacs()->where('is_delete', 0)->first();

        return $firstColor?->code;
    }

    public function getPriceAttribute(): float
    {
        $firstColor = $this->relationLoaded('mauSacs')
            ? $this->mauSacs->first(fn ($m) => $m->is_delete === 0)
            : $this->mauSacs()->where('is_delete', 0)->first();

        return (float) ($firstColor?->price ?? 0);
    }

    public function mauSacs(): HasMany
    {
        return $this->hasMany(MauSacNgoiHaiCoCt::class, 'ngoi_hai_co_ct_id', 'ngoi_hai_co_ct_id');
    }
}
