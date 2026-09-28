# Chuyển dữ liệu sản phẩm và nội dung website

Tài liệu này áp dụng cho `main` và `refactor/big-refactor`. Chỉ thao tác trên bản sao dữ liệu trước khi áp dụng cho môi trường đang phục vụ khách.

## 1. Tạo và thử bản nội dung trên `main`

1. Chạy migration mới và queue worker (`QUEUE_CONNECTION=database`). Trang **Sao lưu nội dung** chỉ dành cho superadmin.
2. Tạo ZIP trong trang quản trị hoặc chạy `php artisan content:archive export`. Giữ cả ZIP và mã SHA-256 của ZIP ở nơi lưu trữ bền vững, ngoài máy chủ ứng dụng.
3. Trong môi trường thử nghiệm dùng cùng phiên bản schema, chạy `php artisan content:archive preview <đường-dẫn-zip>` rồi `php artisan content:archive import <đường-dẫn-zip>`. Kiểm tra danh sách xung đột và media thiếu trước khi nhập qua giao diện quản trị.
4. Thử phục hồi `database.sql` trong một **database MariaDB thử nghiệm riêng** có cùng phiên bản schema. SQL chỉ chứa các bảng nội dung trong allowlist, không chứa tài khoản, đơn hàng, coupon hay yêu cầu tư vấn. Không chạy SQL này trên database đang phục vụ khách.

ZIP có `manifest.json`, `database.sql`, `data/*.ndjson` và `media/*`. Bản xuất lấy dữ liệu theo danh sách tường minh trong `config/content_archive.php`. File tải xuống nằm ở `storage/app/private/content-archives`, không ở thư mục public. Bản nhập đồng bộ không tự xóa bản ghi đích; ID trùng từ nguồn khác và bản đích mới hơn được báo cáo và bỏ qua. Quan hệ con có cha xung đột cũng được bỏ qua để tránh gắn nhầm. Lần nhập sau nhận diện cùng `source_id` và ID gốc qua `content_archive_record_maps`.

## 2. Mở rộng schema trên `refactor/big-refactor`

1. Triển khai code và chạy `php artisan migrate`. Migration thêm `products`, `product_variants`, `product_media`, `product_display_options` và các bảng ánh xạ ID; không xóa bảng cũ.
2. Đặt `PRODUCT_SHADOW_WRITE=true`, `PRODUCT_READ_UNIFIED=false`; làm mới cache cấu hình và chạy queue worker. Từ đây thao tác qua Eloquent ở bảng cũ ghi sang lõi mới. Không sửa trực tiếp bảng sản phẩm bằng SQL trong giai đoạn chuyển, trừ quy trình đã chủ động gọi đồng bộ.
3. Chạy `php artisan products:backfill`. Lệnh có thể chạy lại; mã hàng trùng sẽ làm lệnh dừng và báo rõ các bảng liên quan. Sửa trùng mã ở nguồn cũ rồi chạy lại.
4. Chạy `php artisan products:backfill --verify`. Lệnh thất bại nếu còn bản ghi thiếu, trường/giá/phân loại/gallery lệch, mã hàng trùng hoặc tổng số không khớp. Mỗi nhóm có tối đa 20 ví dụ sai lệch trong kết quả.

ZIP từ `main` có `source_schema=legacy`. Bản refactor nhập được ZIP này và chạy backfill. ZIP xuất từ refactor có `source_schema=hybrid` và chứa cả bảng cũ lẫn bảng mới trong giai đoạn tương thích. Có thể chuyển database cũ chưa từng xuất ZIP bằng cách chạy migration và `products:backfill` trực tiếp trên **bản sao** của database đó; nếu chỉ có SQL cũ, phục hồi SQL trong database tạm trước khi làm bước này.

## 3. Đổi nguồn đọc

1. Trên bản sao gần sát dữ liệu thật, thử đủ 9 trang sản phẩm, tìm kiếm, lọc, gallery, giỏ hàng, checkout, đơn hàng cũ và URL/redirect hiện có. So thời gian và số truy vấn của trang danh sách trước/sau.
2. Khi cần đối soát cuối, chạy `php artisan content:writes lock`. Khóa này chặn thao tác sửa **nội dung trong quản trị**; trang khách, giỏ hàng, checkout và quản lý đơn hàng vẫn mở.
3. Chạy lại `php artisan products:backfill`, rồi `php artisan products:backfill --verify`. Chỉ khi lệnh verify thành công mới đặt `PRODUCT_READ_UNIFIED=true` và làm mới cache cấu hình.
4. Thử nhanh các luồng chính rồi chạy `php artisan content:writes unlock` ngay sau đối soát.

Trong giai đoạn có thể quay lại, giữ `PRODUCT_SHADOW_WRITE=true`, bảng cũ và ánh xạ ID. Giỏ hàng/đơn hàng tiếp tục dùng ID cũ; `order_items` vẫn lưu tên, giá và biến thể đã chụp. Nếu phải quay lại, đặt `PRODUCT_READ_UNIFIED=false`, làm mới cache cấu hình, và dùng ZIP đã kiểm chứng nếu cần phục hồi nội dung. Không dùng `migrate:rollback` để hoàn tác migration gộp phụ kiện cũ vì `down()` của migration đó có lỗi.

## 4. Điều kiện thu gọn

Chỉ xóa bảng và code cũ trong một đợt riêng sau khi thử nhập lại ZIP, phục hồi SQL trên MariaDB, đối soát bằng dữ liệu thật, kiểm thử URL/giỏ hàng/đơn hàng và theo dõi ổn định. Bước này không được tự động kích hoạt bởi migration thêm schema.

## 5. Kết quả thử trên bản sao cục bộ (2026-09-28)

- Sao chép 65 bảng từ database MySQL cục bộ sang schema thử nghiệm riêng. Hai migration mới chạy thành công; database gốc không thay đổi.
- Backfill 174 sản phẩm thuộc 10 nhóm và 8 lựa chọn màu hiển thị. Chạy lại backfill và đối soát đều thành công: không thiếu bản ghi, không lệch trường/biến thể/gallery và không có mã hàng trùng.
- Xuất ZIP schema `hybrid`, xem trước trên schema không có nội dung: 2.445 bản ghi cần thêm, 0 xung đột, 0 media thiếu. Sau nhập và chạy lại, cả 2.445 bản ghi được nhận diện là không đổi.
- Phục hồi `database.sql` trên một schema MySQL 8.4 thử nghiệm cùng phiên bản: số bản ghi của cả 58 bảng trong manifest khớp; đối soát product vẫn đạt 174/174. Cần thử riêng trên MariaDB trước khi phát hành.
- Bộ kiểm thử archive trên nhánh refactor: 12 bài qua. Lần chạy toàn bộ trước thay đổi nhỏ về định dạng JSON: 273 bài qua, 3 bài lỗi giao diện cũng xuất hiện trên `main` (`NewsPageTest` và `NgoiAmDuongDetailViewTest`).

Các schema thử nghiệm và ZIP thử đã được xóa sau khi đối soát. Hai migration mới vẫn ở trạng thái chưa chạy trên database gốc.
