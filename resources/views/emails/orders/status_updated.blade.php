<x-mail::message>
# CẬP NHẬT ĐƠN HÀNG

Xin chào **{{ $order->customer_name }}**,

Đơn hàng **{{ $order->order_code }}** của bạn đã được cập nhật sang trạng thái:

## {{ \App\Models\Order::statusLabel($order->status) }}

---

### Chi tiết đơn hàng

| | |
|---|---|
| **Mã đơn hàng** | {{ $order->order_code }} |
| **Ngày đặt** | {{ $order->created_at->format('d/m/Y H:i') }} |
| **Tổng tiền** | {{ number_format($order->total_amount, 0, ',', '.') }}đ |

---

<x-mail::button :url="route('client.dich-vu.trang-thai-don-hang')">
Xem trạng thái đơn hàng
</x-mail::button>

Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi.

Trân trọng,<br>
**{{ config('app.name') }}**
</x-mail::message>
