<?php

namespace Database\Seeders;

use App\Domains\Content\Models\ThiCong;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class ThiCongSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        ThiCong::truncate();
        $this->seedFromData('thi_cong', ThiCong::class);
    }
}
