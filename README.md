# Quản Lý Chi Tiêu (Personal Finance Manager)

Ứng dụng web giúp quản lý tài chính cá nhân hiệu quả, theo dõi thu chi, lập ngân sách, chia bill nhóm và báo cáo thống kê trực quan.

## Tính Năng Chính

### Quản Lý Tài Chính Cá Nhân
- **Dashboard**: Tổng quan số dư từ tất cả ví, biểu đồ thu chi theo tháng, thống kê chi tiêu theo danh mục
- **Quản lý Ví**: Tạo và quản lý nhiều ví (Tiền mặt, Ngân hàng, Thẻ tín dụng), chuyển tiền giữa các ví
- **Giao dịch**: Thêm, sửa, xóa các khoản thu/chi/chuyển khoản, lọc và tìm kiếm theo ngày, loại, danh mục
- **Ngân sách**: Đặt giới hạn chi tiêu cho từng danh mục theo tháng, cảnh báo khi vượt 90% hoặc 100%
- **Giao dịch Định kỳ**: Tự động tạo giao dịch lặp lại (hàng ngày/tuần/tháng/năm) cho các khoản cố định
- **Lịch**: Xem giao dịch theo lịch với màu sắc phân biệt thu/chi
- **Danh mục**: Tùy chỉnh danh mục thu chi riêng với icon và màu sắc

### Nhóm Chi Tiêu (Chia Bill)
- **Tạo nhóm**: Quản lý chi tiêu chung với bạn bè, gia đình (du lịch, ăn nhóm, ở ghép...)
- **Thêm thành viên**: Mời thành viên qua email, phân quyền quản trị viên/thành viên
- **4 phương thức chia bill**:
  - **Chia đều**: Mỗi người trả bằng nhau
  - **Phần trăm**: Chia theo tỷ lệ % tùy chỉnh
  - **Tùy chỉnh**: Mỗi người số tiền khác nhau
  - **Tỷ lệ**: Chia theo phần share
- **Theo dõi số dư**: Tự động tính ai nợ ai bao nhiêu
- **Gợi ý thanh toán**: Đưa ra cách thanh toán tối ưu với ít giao dịch nhất
- **Ghi nhận thanh toán**: Cập nhật số dư tự động khi có người thanh toán
- **Lịch sử**: Xem chi tiết tất cả chi tiêu và thanh toán trong nhóm

> **Lưu ý quan trọng**: Nhóm chi tiêu và Ví cá nhân là 2 hệ thống riêng biệt, KHÔNG tự động đồng bộ. Chi tiêu trong nhóm chỉ theo dõi "ai nợ ai", không tự động trừ tiền từ ví cá nhân.

### Quản Trị Viên (Admin)
- **Dashboard Admin**: Thống kê tổng quan hệ thống (Users, Transactions, Volume)
- **Quản lý User**: Xem danh sách, khóa/mở khóa tài khoản người dùng
- **Danh mục Hệ thống**: Tạo và quản lý danh mục mặc định cho tất cả users

### Xác Thực & Bảo Mật
- Đăng ký/Đăng nhập thông thường
- Đăng nhập qua Google (OAuth)
- Mã hóa mật khẩu với Bcrypt
- Session management

### Giao Diện & UX
- **Responsive Design**: Tương thích mọi thiết bị (Desktop, Tablet, Mobile)
- **Modern UI**: Glassmorphism design với backdrop-filter
- **Dark Sidebar**: Navigation menu hiện đại
- **Icon**: RemixIcon library
- **Thông báo**: Real-time notifications

## Tech Stack

- **Backend**: Laravel 10.x (PHP 8.1+)
- **Frontend**: Blade Templates, Vanilla JavaScript, Custom CSS
- **Database**: MySQL 8.0+
- **Build Tool**: Vite
- **UI/UX**:
  - Glassmorphism Design
  - RemixIcon (2000+ icons)
  - Chart.js (Interactive charts)
  - Responsive Grid & Flexbox
- **Authentication**: Laravel Sanctum + Socialite (Google OAuth)
- **Notifications**: Laravel Notifications System

## Cài Đặt & Chạy Dự Án

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
   - Web: 

## Hướng Dẫn Sử Dụng

### Quản Lý Tài Chính Cá Nhân
1. Tạo ví 
2. Thêm giao dịch thu/chi vào từng ví
3. Đặt ngân sách cho các danh mục (Ăn uống, Di chuyển...)
4. Xem báo cáo và biểu đồ trên Dashboard

### Chia Bill Nhóm
1. Vào **Nhóm chi tiêu** → **Tạo nhóm mới**
2. Thêm thành viên qua email
3. Thêm chi tiêu, chọn người trả và phương thức chia
4. Xem **Số dư** để biết ai nợ ai
5. Click **Ghi nhận thanh toán** khi đã chuyển tiền

> **Chi tiết**: Xem trang `/help` trong ứng dụng để có hướng dẫn đầy đủ


## Cấu Trúc Dự Án

```
QuanLyChiTieu/
├── app/
│   ├── Http/Controllers/      # Controllers
│   ├── Models/                # Eloquent Models
│   ├── Services/              # Business Logic (GroupExpenseService)
│   └── Notifications/         # Email/System notifications
├── database/
│   ├── migrations/            # Database schema
│   └── seeders/               # Seed data
├── resources/
│   ├── views/                 # Blade templates
│   ├── css/                   # Custom CSS
│   └── js/                    # JavaScript
├── routes/
│   └── web.php                # Web routes
└── public/                    # Public assets
```

## Screenshots

### Dashboard
Tổng quan số dư, biểu đồ thu chi, thống kê theo danh mục

### Nhóm Chi Tiêu
Chia bill với 4 phương thức, theo dõi số dư tự động, gợi ý thanh toán tối ưu

### Quản Lý Ví & Giao Dịch
Nhiều ví, chuyển tiền, lọc và tìm kiếm linh hoạt

## Tính Năng Sắp Tới

- [ ] Xuất báo cáo Excel/PDF
- [ ] Thông báo push khi vượt ngân sách
- [ ] Dark mode
- [ ] Multi-currency support
- [ ] Mobile app (React Native)

## Đóng Góp

Contributions are welcome! Please feel free to submit a Pull Request.

## License

Dự án này là mã nguồn mở cho mục đích học tập và nghiên cứu.
