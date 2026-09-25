<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasProductPriority
{
    protected static function bootHasProductPriority(): void
    {
        static::creating(function ($model): void {
            if ($model->priority !== null && (int) $model->priority !== 0) {
                return;
            }

            $query = static::query();
            $categoryColumn = $model->priorityCategoryColumn();

            if ($categoryColumn !== null && filled($model->getAttribute($categoryColumn))) {
                $query->where($categoryColumn, $model->getAttribute($categoryColumn));
            }

            $model->priority = ((int) $query->max('priority')) + 1;
        });
    }

    public function scopeOrderedByPriority(Builder $query): Builder
    {
        return $query
            ->orderByDesc($query->getModel()->qualifyColumn('priority'))
            ->orderByDesc($query->getModel()->getQualifiedKeyName());
    }

    protected function priorityCategoryColumn(): ?string
    {
        return in_array($this->getTable(), [
            'phu_kien_ngoi_ct',
            'den_vuon_gom_su_ct',
            'gach_co_bat_trang_ct',
        ], true) ? 'category_type' : null;
    }
}
