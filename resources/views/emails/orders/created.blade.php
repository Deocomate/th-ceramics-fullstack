<x-mail::message>
# XÁC NHẬN ĐƠN HÀNG

Xin chào **{{ $order->customer_name }}**,

Đơn hàng của bạn đã được tiếp nhận và đang chờ xử lý.

---

### Thông tin đơn hàng

| | |
|---|---|
| **Mã đơn hàng** | {{ $order->order_code }} |
| **Ngày đặt** | {{ $order->created_at->format('d/m/Y H:i') }} |
| **Phương thức thanh toán** | Thanh toán khi nhận hàng (COD) |
| **Trạng thái** | Đang xử lý |

---

### Thông tin khách hàng

{{ $order->customer_name }}
{{ $order->phone }}
{{ $order->email }}
{{ $order->address }}

---

### Danh sách sản phẩm

<x-mail::table>
| Sản phẩm | Phân loại | SL | Đơn giá | Thành tiền |
|-----------|-----------|-----|---------|------------|
@foreach($order->items as $item)
| {{ $item->product_name }} | {{ $item->variant_name ?? '-' }} | {{ $item->quantity }} | {{ number_format($item->price, 0, ',', '.') }}đ | {{ number_format($item->total, 0, ',', '.') }}đ |
@endforeach
</x-mail::table>

---

### Tổng kết

<x-mail::table>
| | |
|---|---|
| **Tạm tính** | {{ number_format($order->subtotal, 0, ',', '.') }}đ |
@if($order->discount > 0)
| **Giảm giá** | -{{ number_format($order->discount, 0, ',', '.') }}đ |
@endif
| **Phí vận chuyển** | Miễn phí |
| **Tổng cộng** | **{{ number_format($order->total_amount, 0, ',', '.') }}đ** |
</x-mail::table>

@if($order->coupon_code)
*Mã giảm giá đã sử dụng: **{{ $order->coupon_code }}***
@endif

---

Cảm ơn bạn đã mua hàng tại TH Ceramics!

<x-mail::button :url="route('client.dich-vu.trang-thai-don-hang')">
Theo dõi đơn hàng
</x-mail::button>

Trân trọng,<br>
**{{ config('app.name') }}**
</x-mail::message>
