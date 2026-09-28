<?php

namespace App\Observers;

use App\Products\ProductTypeRegistry;
use App\Services\ProductBackfillService;
use Illuminate\Database\Eloquent\Model;

class LegacyVariantObserver
{
    public function saved(Model $model): void
    {
        $this->sync($model);
    }

    public function deleted(Model $model): void
    {
        $this->sync($model);
    }

    private function sync(Model $model): void
    {
        if (! config('product_catalog.shadow_write')) {
            return;
        }
        foreach (ProductTypeRegistry::all() as $type => $config) {
            if ($config['variant_table'] === $model->getTable()) {
                app(ProductBackfillService::class)->sync($type, (int) $model->{$config['variant_fk']});

                return;
            }
        }
    }
}
