<?php

namespace App\Domains\Commerce\Http\Admin;

use App\Domains\Commerce\Application\OrderAdminService;
use App\Domains\Commerce\Domain\OrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(OrderAdminService $orders): View
    {
        $orders = $orders->listing();

        return view('admin.commerce.orders.index', compact('orders'));
    }

    public function show(int $order, OrderAdminService $orders): View
    {
        $order = $orders->find($order);

        return view('admin.commerce.orders.show', compact('order'));
    }

    public function update(Request $request, int $order, OrderAdminService $orders): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', OrderStatus::values())],
        ]);

        $orders->updateStatus($order, $validated['status']);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }
}
