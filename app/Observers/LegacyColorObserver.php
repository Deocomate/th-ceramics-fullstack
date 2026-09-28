<?php

namespace App\Observers;

use App\Services\ProductBackfillService;
use Illuminate\Database\Eloquent\Model;

class LegacyColorObserver
{
    public function saved(Model $model): void
    {
        if (config('product_catalog.shadow_write')) {
            app(ProductBackfillService::class)->syncSharedColors();
        }
    }

    public function deleted(Model $model): void
    {
        $this->saved($model);
    }
}
