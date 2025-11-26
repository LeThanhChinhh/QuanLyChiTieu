# Quản Lý Chi Tiêu (Personal Finance Manager)

Ứng dụng web giúp quản lý tài chính cá nhân hiệu quả, theo dõi thu chi, lập ngân sách và báo cáo thống kê trực quan.

## 🚀 Tính Năng Chính

### 👤 Người Dùng (User)
- **Dashboard**: Tổng quan số dư, biểu đồ thu chi tháng hiện tại.
- **Quản lý Giao dịch**: Thêm, sửa, xóa các khoản thu/chi.
- **Quản lý Ngân sách**: Thiết lập giới hạn chi tiêu cho từng danh mục, cảnh báo khi vượt quá mức.
- **Danh mục**: Tùy chỉnh danh mục thu chi (Icon, Màu sắc).
- **Giao dịch Định kỳ**: Tự động tạo giao dịch lặp lại (hàng ngày, hàng tuần, hàng tháng).
- **Lịch**: Xem lịch sử giao dịch theo dạng lịch.
- **Đăng nhập/Đăng ký**: Hỗ trợ đăng nhập qua Google.

### 🛡️ Quản Trị Viên (Admin)
- **Dashboard Admin**: Thống kê tổng quan hệ thống (User, Transaction, Volume).
- **Quản lý User**: Xem danh sách, Khóa/Mở khóa tài khoản người dùng.
- **Danh mục Mẫu**: Tạo và quản lý các danh mục hệ thống (dùng chung cho tất cả user).
- **Tối ưu hóa Mobile**: Giao diện được tinh chỉnh để hiển thị tốt trên các thiết bị di động.

## 🛠️ Tech Stack

- **Backend**: Laravel 10 (PHP 8.1+)
- **Frontend**: Blade Templates, Vanilla JS, Custom CSS (Glassmorphism UI)
- **Database**: MySQL
- **Build Tool**: Vite
- **Libraries**:
  - Chart.js (Biểu đồ)
  - RemixIcon (Icons)
  - Laravel Socialite (Google Auth)

## ⚙️ Cài Đặt & Chạy Dự Án

### Yêu cầu
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL

### Các bước thực hiện

1. **Clone dự án**
   ```bash
   git clone https://github.com/YOUR_USERNAME/QuanLyChiTieu.git
   cd QuanLyChiTieu
   ```

2. **Cài đặt Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Cấu hình Môi trường**
   - Copy file `.env.example` thành `.env`
   ```bash
   cp .env.example .env
   ```
   - Mở file `.env` và cấu hình thông tin Database (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

4. **Tạo Key & Database**
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```
   *(Lệnh `--seed` sẽ tạo dữ liệu mẫu và tài khoản Admin mặc định)*

5. **Chạy Dự Án**
   Bạn cần mở 2 terminal:

   - Terminal 1 (Backend):
     ```bash
     php artisan serve
     ```
   - Terminal 2 (Frontend Build):
     ```bash
     npm run dev
     ```

6. **Truy cập**
   - Web: `http://127.0.0.1:8000`
   - Tài khoản Admin mặc định:
     - Email: `admin@example.com`
     - Password: `password`

## 📝 License

Dự án này là mã nguồn mở.
