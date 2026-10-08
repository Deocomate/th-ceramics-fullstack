<?php

namespace App\Domains\Commerce\Http\Client;

use App\Domains\Commerce\Application\OrderAdminService;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    public function index(OrderAdminService $ordersService): View
    {
        $orders = collect();

        if (auth()->check()) {
            $orders = $ordersService->forUser((int) auth()->id());
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

        return view('clients.commerce.dich-vu-khach-hang.trang-thai-don-hang', compact('orders', 'counts'));
    }
}
