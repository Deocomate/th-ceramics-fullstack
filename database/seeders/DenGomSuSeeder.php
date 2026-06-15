<?php

namespace Database\Seeders;

use App\Models\DenGomSu;
use App\Models\DenGomSuAnh;
use App\Models\DenVuonGomSuCt;
use App\Models\PhanLoaiDenVuonGomSuCt;
use Database\Seeders\Support\SeederDataContract;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DenGomSuSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->truncateTables(
            'phan_loai_den_vuon_gom_su_ct',
            'den_vuon_gom_su_ct',
            'den_gom_su_anh',
            'den_gom_su',
        );

        $this->seedFromData('den_gom_su', DenGomSu::class);

        $galleryRows = $this->seederData('den_gom_su_anh');
        $pool = array_column($galleryRows, 'image');
        $expanded = SeederDataContract::expandGallery($pool, $pool, 5);

        Model::unguarded(function () use ($expanded): void {
            foreach ($expanded as $index => $image) {
                DenGomSuAnh::create([
                    'den_gom_su_anh_id' => $index + 1,
                    'image' => $image,
                    'den_gom_su_id' => 1,
                ]);
            }
        });

        $this->seedFromData('den_vuon_gom_su_ct', DenVuonGomSuCt::class);
        $this->seedFromData('phan_loai_den_vuon_gom_su_ct', PhanLoaiDenVuonGomSuCt::class);
    }
}
