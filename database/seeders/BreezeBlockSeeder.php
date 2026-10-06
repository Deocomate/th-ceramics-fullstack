<?php

namespace Database\Seeders;

use App\Domains\Catalog\Infrastructure\Models\AttributeValueBreezeBlock;
use App\Domains\Catalog\Infrastructure\Models\BreezeBlock;
use App\Domains\Catalog\Infrastructure\Models\BreezeBlockImage;
use Database\Seeders\Support\CanonicalProductSeeding;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class BreezeBlockSeeder extends Seeder
{
    use CanonicalProductSeeding;
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->seedFromData('gach_hoa_thong_gio', BreezeBlock::class);
        $this->seedFromData('gia_tri_gach_hoa_thong_gio', AttributeValueBreezeBlock::class);

        $galleryRows = array_slice($this->seederData('gach_hoa_thong_gio_anh'), 0, 10);
        Model::unguarded(function () use ($galleryRows): void {
            foreach ($galleryRows as $row) {
                unset($row['created_at'], $row['updated_at']);
                BreezeBlockImage::create($row);
            }
        });

        $this->seedCanonicalProductType('gach_hoa_thong_gio_ct', 'gach_hoa_thong_gio_ct_id', true);
    }
}
