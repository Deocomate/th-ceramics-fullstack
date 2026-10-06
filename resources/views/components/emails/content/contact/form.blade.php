<x-mail::message>
# Liên hệ mới

**Họ và tên:** {{ $data['name'] }}

**Email:** {{ $data['email'] }}

**Số điện thoại:** {{ $data['phone'] }}

**Nội dung:**

{{ $data['message'] }}
</x-mail::message>
