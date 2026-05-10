# Hệ Thống Xác Thực (Authentication) - Tài Liệu Cài Đặt

## 📋 Tổng Quan

Hệ thống xác thực cho phía Client (Khách hàng) đã được hoàn thiện với các tính năng:
- ✅ Đăng nhập
- ✅ Đăng ký tài khoản
- ✅ Quên mật khẩu
- ✅ Reset mật khẩu
- ✅ Đăng nhập bằng Google OAuth2

---

## 🔧 Cài Đặt & Cấu Hình

### 1. Packages Đã Cài Đặt
```bash
laravel/socialite  v5.27.0
```

### 2. Cấu Hình Google OAuth

#### Bước 1: Lấy Credentials từ Google Cloud Console

1. Truy cập: https://console.cloud.google.com/
2. Tạo hoặc chọn một dự án
3. Vào **APIs & Services** → **Credentials**
4. Tạo **OAuth 2.0 Client ID** (Application type: Web application)
5. Đặt **Authorized redirect URIs**: `http://localhost:8000/tai-khoan/google/callback`
6. Copy `Client ID` và `Client Secret`

#### Bước 2: Cập Nhật `.env`

```env
# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI="${APP_URL}/tai-khoan/google/callback"
```

#### Bước 3: Xác Nhận `config/services.php`

File này đã được cập nhật tự động với:
```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

---

## 📁 Các File Đã Tạo/Cập Nhật

### Database
- ✅ `database/migrations/2026_05_09_074615_add_google_oauth_to_users_table.php`
  - Thêm: `google_id`, `avatar`
  - Cập nhật: `password` thành nullable

### Models
- ✅ `app/Models/User.php`
  - Thêm `google_id`, `avatar` vào `$fillable`
  - Override `sendPasswordResetNotification()` để gửi `ResetPasswordNotification` custom (role-based routing, ShouldQueue)

### Services
- ✅ `app/Services/AuthService.php`
  - Thêm: `registerClient()` - Đăng ký khách hàng
  - Thêm: `handleGoogleUser()` - Xử lý Google OAuth

### Notifications
- ✅ `app/Notifications/ResetPasswordNotification.php`
  - Extends `Illuminate\Auth\Notifications\ResetPassword`
  - Implements `ShouldQueue` (database queue)
  - Role-based routing: admin -> `/admin/reset-password/{token}`, client -> `/tai-khoan/dat-lai-mat-khau/{token}`
  - Custom Vietnamese-branded email via `emails.auth.reset_password` markdown template

### Controllers
- ✅ `app/Http/Controllers/Client/AuthController.php`
  - `showLogin()` - Hiển thị form đăng nhập
  - `login()` - Xử lý đăng nhập
  - `showRegister()` - Hiển thị form đăng ký
  - `register()` - Xử lý đăng ký
  - `logout()` - Đăng xuất
  - `showForgotPassword()` - Hiển thị form quên mật khẩu
  - `sendResetLink()` - Gửi link reset mật khẩu
  - `showResetPassword()` - Hiển thị form reset mật khẩu
  - `resetPassword()` - Xử lý reset mật khẩu
  - `redirectToGoogle()` - Chuyển hướng tới Google OAuth
  - `handleGoogleCallback()` - Xử lý callback từ Google

### Form Requests (Validation)
- ✅ `app/Http/Requests/Client/Auth/LoginRequest.php`
- ✅ `app/Http/Requests/Client/Auth/RegisterRequest.php`
- ✅ `app/Http/Requests/Client/Auth/ForgotPasswordRequest.php`
- ✅ `app/Http/Requests/Client/Auth/ResetPasswordRequest.php`

### Routes
- ✅ `routes/client.php`
  - Thêm group route `/tai-khoan` với các endpoints:
    - `GET /tai-khoan/dang-nhap` - Hiển thị form đăng nhập
    - `POST /tai-khoan/dang-nhap` - Xử lý đăng nhập
    - `GET /tai-khoan/dang-ky` - Hiển thị form đăng ký
    - `POST /tai-khoan/dang-ky` - Xử lý đăng ký
    - `GET /tai-khoan/quen-mat-khau` - Hiển thị form quên mật khẩu
    - `POST /tai-khoan/quen-mat-khau` - Gửi link reset
    - `GET /tai-khoan/dat-lai-mat-khau/{token}` - Form reset mật khẩu
    - `POST /tai-khoan/dat-lai-mat-khau` - Xử lý reset mật khẩu
    - `GET /tai-khoan/google` - Chuyển hướng tới Google
    - `GET /tai-khoan/google/callback` - Callback từ Google
    - `POST /tai-khoan/dang-xuat` - Đăng xuất

### Views (Blade Templates)
- ✅ `resources/views/layouts/app.blade.php` - Layout master
- ✅ `resources/views/clients/auth/login.blade.php` - Form đăng nhập
- ✅ `resources/views/clients/auth/register.blade.php` - Form đăng ký
- ✅ `resources/views/clients/auth/forget-password.blade.php` - Form quên mật khẩu
- ✅ `resources/views/clients/auth/reset-password.blade.php` - Form reset mật khẩu
- ✅ `resources/views/emails/auth/reset_password.blade.php` - Email template đặt lại mật khẩu (tiếng Việt, branded)

### Configuration
- ✅ `config/services.php` - Cấu hình Google OAuth
- ✅ `.env` - Thêm Google OAuth variables

---

## 🧪 Kiểm Tra Chức Năng

### URLs Để Test

1. **Đăng Nhập**: `http://localhost:8000/tai-khoan/dang-nhap`
2. **Đăng Ký**: `http://localhost:8000/tai-khoan/dang-ky`
3. **Quên Mật Khẩu**: `http://localhost:8000/tai-khoan/quen-mat-khau`
4. **Google OAuth**: `http://localhost:8000/tai-khoan/google`

### Test Cases

#### Test 1: Đăng Ký Tài Khoản
```bash
POST /tai-khoan/dang-ky
- name: "Nguyễn Văn A"
- email: "test@example.com"
- password: "Test1234@"
- password_confirmation: "Test1234@"
```

#### Test 2: Đăng Nhập
```bash
POST /tai-khoan/dang-nhap
- email: "test@example.com"
- password: "Test1234@"
- remember: false (hoặc true)
```

#### Test 3: Quên Mật Khẩu
```bash
POST /tai-khoan/quen-mat-khau
- email: "test@example.com"
```

#### Test 4: Reset Mật Khẩu
```bash
POST /tai-khoan/dat-lai-mat-khau
- token: "{reset_token_từ_email}"
- email: "test@example.com"
- password: "NewPassword123@"
- password_confirmation: "NewPassword123@"
```

---

## 📧 Cấu Hình Mail (Tùy Chọn)

Để gửi email reset password, cập nhật `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@thceramics.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🔐 Validation Rules

### LoginRequest
- `email`: required, email
- `password`: required, string, min:8
- `remember`: sometimes, boolean

### RegisterRequest
- `name`: required, string, max:255
- `email`: required, email, unique:users
- `password`: required, string, min:8, confirmed
- `password_confirmation`: required

### ForgotPasswordRequest
- `email`: required, email, exists:users

### ResetPasswordRequest
- `token`: required, string
- `email`: required, email, exists:users
- `password`: required, string, min:8, confirmed
- `password_confirmation`: required

---

## 🚀 Deployment Checklist

- [ ] Cập nhật `.env` với Google OAuth credentials
- [ ] Cập nhật `GOOGLE_REDIRECT_URI` để khớp với production domain
- [ ] Chạy `php artisan migrate` trên production
- [ ] Kiểm tra MAIL_MAILER cấu hình cho reset password
- [ ] Test tất cả auth flows trên staging environment
- [ ] Thêm Google OAuth domain vào whitelist nếu cần

---

## 📚 Tài Liệu Tham Khảo

- [Laravel Socialite Documentation](https://laravel.com/docs/12.x/socialite)
- [Google OAuth 2.0 Documentation](https://developers.google.com/identity/protocols/oauth2)
- [Laravel Authentication Documentation](https://laravel.com/docs/12.x/authentication)

---

## 🎯 Các Bước Tiếp Theo

1. **Email Verification** - Xác thực email khi đăng ký
2. **Two-Factor Authentication (2FA)** - Bảo mật thêm một lớp
3. **OAuth Provider Khác** - Facebook, GitHub, etc.
4. **User Profile Management** - Chỉnh sửa thông tin tài khoản
5. **Remember Me Duration** - Tuỳ chỉnh thời gian ghi nhớ

---

**Ngày Cài Đặt**: 09/05/2026  
**Phiên Bản Laravel**: 12.x  
**Phiên Bản Socialite**: 5.27.0
