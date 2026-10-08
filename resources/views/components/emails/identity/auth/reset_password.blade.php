<x-mail::message>
# YÊU CẦU ĐẶT LẠI MẬT KHẨU

Xin chào,

Bạn nhận được email này vì chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.

<x-mail::button :url="$url">
Đặt lại mật khẩu
</x-mail::button>

Liên kết đặt lại mật khẩu này sẽ hết hạn sau **{{ config('auth.passwords.users.expire') }} phút**.

Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này. Tài khoản của bạn vẫn được bảo mật.

Trân trọng,<br>
**{{ config('app.name') }}**
</x-mail::message>
