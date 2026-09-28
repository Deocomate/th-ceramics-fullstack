<?php

namespace App\Services;

use App\Models\LinhVatPhongThuyCt;

class LinhVatPhongThuyCtService extends DirectProductService
{
    protected const TYPE = 'linh_vat_phong_thuy_ct';

    protected const MODEL = LinhVatPhongThuyCt::class;

    protected const HAS_SIZE_DESCRIPTION = true;
}
