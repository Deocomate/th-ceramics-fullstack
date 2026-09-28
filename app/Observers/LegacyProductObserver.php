<?php

namespace App\Observers;

use App\Services\ProductBackfillService;
use Illuminate\Database\Eloquent\Model;

class LegacyProductObserver
{
    public function saved(Model $model): void
    {
        if (config('product_catalog.shadow_write')) {
            app(ProductBackfillService::class)->sync($model->getTable(), (int) $model->getKey());
        }
    }

    public function deleted(Model $model): void
    {
        if (config('product_catalog.shadow_write')) {
            app(ProductBackfillService::class)->sync($model->getTable(), (int) $model->getKey());
        }
    }
}
