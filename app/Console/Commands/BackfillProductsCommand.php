<?php

namespace App\Console\Commands;

use App\Services\ProductBackfillService;
use Illuminate\Console\Command;

class BackfillProductsCommand extends Command
{
    protected $signature = 'products:backfill {--verify : Only report counts and missing mappings}';

    protected $description = 'Backfill unified products from legacy product tables';

    public function handle(ProductBackfillService $service): int
    {
        $result = $this->option('verify') ? $service->verify() : $service->backfill();
        $this->line(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        if ($this->option('verify') && ($result['duplicate_skus'] !== []
            || array_sum($result['missing']) > 0
            || array_sum($result['mismatched']) > 0
            || $result['legacy'] !== $result['unified'])) {
            $this->error('Đối soát chưa đạt; không chuyển nguồn đọc.');

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
