<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['items', 'user']);

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending_payment,processing,shipping,completed,canceled,returned'],
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $validated['status']]);

        if ($validated['status'] !== $oldStatus && $order->email) {
            Mail::to($order->email)->send(
                new OrderStatusUpdatedMail($order->load('items'))
            );
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }
}
