# Rule dự án
- Trong Controller luôn luôn sử dụng $request->validate() để validate request. Không cần phải tạo các FormRequest riêng làm cấu trúc thêm phức tạp


# Workflow hoàn thiện các module

- Dựng model dựa theo migration, database. Viết model vào trong '/app/Models'

- Sau khi dựng model xong -> dựng service đầy đủ các chức năng CRUD và chức năng nâng cao tùy thuộc module. Viết vào trong '/app/Services'

- Khai báo module trong '/routes/web.php', khai báo controller

- Cập nhật '/resources/views/components/admin/layout/sidebar.blade.php' để cập nhật lên UI

- Xây dựng controller hoàn chỉnh, tích hợp các services đã viết vào controller

- Xây dựng các views đầy đủ dựa theo controller.

- Validate lại một lần nữa, tối ưu giao diện UI tốt nhất cho người dùng.

===


