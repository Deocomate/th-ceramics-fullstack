<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class CustomerServiceController extends Controller
{
    public function show($page)
    {
        if (str_ends_with($page, '.html')) {
            $cleanPage = str_replace('.html', '', $page);

            return redirect()->route('client.customer-service.show', $cleanPage, 301);
        }

        $validPages = [
            'bao-mat-thong-tin',
            'chinh-sach-doi-tra',
            'chinh-sach-van-chuyen',
            'huong-dan-thi-cong',
            'quy-trinh-dat-hang',
            'tai-catalog',
            'tai-khoan-cua-toi',
            'trang-thai-don-hang',
        ];

        if (in_array($page, $validPages)) {
            return view("clients.dich-vu-khach-hang.{$page}");
        }

        abort(404);
    }
}
