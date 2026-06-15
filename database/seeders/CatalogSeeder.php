<?php

namespace Database\Seeders;

use App\Models\Catalog;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        Catalog::truncate();
        $this->seedFromData('catalog', Catalog::class);
    }
}
