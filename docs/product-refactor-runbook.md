# Chuyển dữ liệu sản phẩm và nội dung website

## Chuyển database cũ sang catalog hiện tại

Code hiện tại đọc các bảng canonical. Không dùng các cờ PRODUCT_SHADOW_WRITE, PRODUCT_READ_UNIFIED hoặc lệnh lịch sử products:backfill; lệnh đó không tồn tại trên nhánh hiện tại.

Trước khi triển khai, sao lưu **toàn bộ database**, storage/app/public, media trong public/assets, .env và revision Git. Giữ backup ngoài webroot và Git, kiểm tra SHA-256, rồi phục hồi SQL vào database thử nghiệm. Archive nội dung chỉ chứa các bảng trong allowlist, nên không thay thế bản sao lưu toàn bộ tài khoản, đơn hàng, session và queue.

Trên bản sao database, và sau đó trong cửa sổ bảo trì trên server:

```bash
php artisan migrate --force
php scripts/migrate-legacy-catalog.php --apply --backup=/absolute/private/path/database.sql.gz
php scripts/migrate-legacy-catalog.php --verify
```

[Script chuyển catalog](../scripts/migrate-legacy-catalog.php) sử dụng adapter archive hiện có để chuyển sản phẩm, biến thể, màu hiển thị và đường dẫn gallery. Script giữ ID công khai cũ, dự trữ dải ID biến thể trước khi cấp ID mới, giữ thứ tự và thời gian lịch sử, và đối soát trong transaction. Script không sao chép hoặc xóa file media và không sửa các bảng legacy. Nếu canonical đã có dữ liệu khác nguồn legacy, script dừng thay vì ghi đè. Chạy lại khi dữ liệu đã khớp chỉ đối soát.

Tham số --backup xác nhận file dump tồn tại và không rỗng; người vận hành vẫn phải kiểm chứng checksum và khả năng phục hồi trước khi chạy. Không chạy seeders hoặc migrate:fresh trên database thật. Giữ nguyên .env, APP_KEY, đường dẫn storage và các lớp tương thích cho payload queue cũ.

Điều kiện mở lại website: script trả verified: true và không có mismatches; các bảng nguồn còn đủ bản ghi; ID, giá, mô tả, ngày tháng và đường dẫn media khớp; các trang danh sách/chi tiết của mọi nhóm sản phẩm và ảnh đại diện hoạt động; revision server khớp origin/main. Kiểm tra payload job cũ bằng việc nạp lớp, không xử lý queue chỉ để thử vì việc đó có thể gửi email thật.

## Archive nội dung

```bash
php artisan content:archive export
php artisan content:archive preview /private/path/database.zip
php artisan content:archive import /private/path/database.zip
```

ZIP chứa manifest, SQL nội dung, NDJSON và media theo config/content_archive.php. Bản nhập kiểm tra checksum, giới hạn kích thước, đường dẫn và xung đột. Đây là công cụ chuyển nội dung; việc đồng bộ toàn bộ server dùng full database dump và backup media riêng.

## Phục hồi

Giữ website trong bảo trì, phục hồi revision cũ, dependencies tương ứng và .env cũ. Nếu cần hoàn tác database, nhập bản dump đầy đủ đã kiểm chứng; chỉ phục hồi media nếu file đã thay đổi. Làm mới cache, kiểm tra trang và ảnh, rồi chạy php artisan up.

Không dùng migrate:rollback để hoàn tác đợt chuyển đổi này: migration gộp phụ kiện lịch sử có đường down() không an toàn. Việc xóa bảng legacy hoặc loại bỏ các lớp tương thích queue cần một đợt riêng sau khi dữ liệu và queue đã được kiểm chứng.
