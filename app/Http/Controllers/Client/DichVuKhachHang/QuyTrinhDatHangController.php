<?php

namespace App\Http\Controllers\Client\DichVuKhachHang;

use App\Http\Controllers\Controller;

class QuyTrinhDatHangController extends Controller
{
    public function index()
    {
        return view('clients.dich-vu-khach-hang.quy-trinh-dat-hang');
    }
}
