<x-mail::message>
# Yêu cầu tư vấn mới

@if ($record->product_name)
**Sản phẩm:** {{ $record->product_name }}

@if ($record->variant_name)
**Phân loại:** {{ $record->variant_name }}
@endif
@endif

**Họ và tên:** {{ $record->customer_name }}

**Số điện thoại:** {{ $record->phone }}

@if ($record->email)
**Email:** {{ $record->email }}
@endif

@if ($record->note)
**Ghi chú:**

{{ $record->note }}
@endif

<x-mail::button :url="route('admin.consultation-requests.show', $record)">
Xem trong admin
</x-mail::button>
</x-mail::message>
