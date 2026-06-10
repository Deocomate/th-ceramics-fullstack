<x-mail::message>
# XÁC THỰC EMAIL

Xin chào{{ isset($user) ? ' **'.$user->name.'**' : '' }},

Cảm ơn bạn đã đăng ký tài khoản tại TH Ceramics. Vui lòng nhấn nút bên dưới để xác thực địa chỉ email và bắt đầu theo dõi đơn hàng, quản lý tài khoản.

<x-mail::button :url="$url">
Xác thực email
</x-mail::button>

Liên kết xác thực này sẽ hết hạn sau **60 phút**.

Nếu bạn không tạo tài khoản, vui lòng bỏ qua email này.

Trân trọng,<br>
**{{ config('app.name') }}**
</x-mail::message>
