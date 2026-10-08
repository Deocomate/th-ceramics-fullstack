<x-mail::message>
# Cảm ơn bạn đã liên hệ Thanh Hải

Chúng tôi đã nhận yêu cầu tư vấn của bạn và sẽ phản hồi trong thời gian sớm nhất.

@if ($record->product_name)
**Sản phẩm quan tâm:** {{ $record->product_name }}

@if ($record->variant_name)
**Phân loại:** {{ $record->variant_name }}
@endif
@endif

@if ($record->note)
**Ghi chú của bạn:**

{{ $record->note }}
@endif

Trân trọng,<br>
Thanh Hải Ceramics
</x-mail::message>
