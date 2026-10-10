<x-client.protection.page title="Cảnh báo bản quyền">
    <p>
        Hệ thống phát hiện công cụ dành cho nhà phát triển được mở nhiều lần trong phiên truy cập này.
    </p>
    <p>
        Toàn bộ hình ảnh, video, catalog, nội dung và mã nguồn trên website thuộc quyền sở hữu của Thanh Hải Ceramics và được bảo hộ
        theo pháp luật Việt Nam về sở hữu trí tuệ.
    </p>
    <p>
        Hệ thống đã ghi nhận địa chỉ IP, thời điểm truy cập và thông tin trình duyệt của phiên này.
        @if ($recordId)
            <span class="record">Mã ghi nhận: #{{ $recordId }}</span>
        @endif
    </p>
    <p>
        Việc sao chép, phân phối hoặc sử dụng trái phép các nội dung trên có thể bị xử lý theo quy định của pháp luật, gồm xử phạt
        hành chính, bồi thường dân sự và truy cứu trách nhiệm hình sự theo Điều 225 Bộ luật Hình sự.
    </p>

    <x-slot:contact>
        Nếu bạn cần sử dụng hình ảnh hoặc tài liệu của chúng tôi, vui lòng liên hệ:
    </x-slot:contact>
</x-client.protection.page>
