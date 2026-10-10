<?php

namespace App\Domains\Catalog\Domain;

final class PhuKienNgoiCategory
{
    public const TYPE_BO_NOC = 'ngoi_bo_noc';

    public const TYPE_CHU_VAN = 'chu_van';

    public static function normalizeLegacy(?string $type): ?string
    {
        return $type === 'bo_noc' ? self::TYPE_BO_NOC : $type;
    }

    public static function label(string $type): string
    {
        return $type === self::TYPE_CHU_VAN ? 'Bờ Nóc Chữ Vạn' : 'Ngói Bờ Nóc';
    }

    public static function codePrefix(?string $type): string
    {
        return $type === self::TYPE_CHU_VAN ? 'BNCV-' : 'NBN-';
    }
}
