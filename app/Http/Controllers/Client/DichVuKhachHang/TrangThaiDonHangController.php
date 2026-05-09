<?php

namespace App\Http\Controllers\Client\DichVuKhachHang;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class TrangThaiDonHangController extends Controller
{
    public function index(): View
    {
        $orders = collect();

        if (auth()->check()) {
            $orders = Order::where('user_id', auth()->id())
                ->with('items')
                ->latest()
                ->get();
        }

        $countByStatus = $orders->groupBy('status')->map->count();
        $counts = [
            'all' => $orders->count(),
            'pending_payment' => $countByStatus['pending_payment'] ?? 0,
            'processing' => $countByStatus['processing'] ?? 0,
            'shipping' => $countByStatus['shipping'] ?? 0,
            'completed' => $countByStatus['completed'] ?? 0,
            'canceled' => $countByStatus['canceled'] ?? 0,
            'returned' => $countByStatus['returned'] ?? 0,
        ];

        return view('clients.dich-vu-khach-hang.trang-thai-don-hang', compact('orders', 'counts'));
    }
}
