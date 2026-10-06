<?php

namespace App\Domains\Content\Domain;

final class ContentPageRegistry
{
    /** @var list<string> */
    public const VALID_CUSTOMER_SERVICE_PAGES = [
        'bao-mat-thong-tin',
        'chinh-sach-doi-tra',
        'chinh-sach-van-chuyen',
        'huong-dan-thi-cong',
        'quy-trinh-dat-hang',
        'tai-catalog',
        'tai-khoan-cua-toi',
        'trang-thai-don-hang',
    ];

    public static function isValidCustomerServicePage(string $page): bool
    {
        return in_array($page, self::VALID_CUSTOMER_SERVICE_PAGES, true);
    }

    public static function stripHtmlExtension(string $page): string
    {
        if (str_ends_with($page, '.html')) {
            return substr($page, 0, -5);
        }

        return $page;
    }
}
