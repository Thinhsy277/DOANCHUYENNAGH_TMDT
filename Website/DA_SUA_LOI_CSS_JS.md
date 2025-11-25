# Đã Sửa Lỗi CSS và JavaScript Khi Chạy Bằng PHP Built-in Server

## Vấn Đề
Khi chạy `php -S localhost:8000`, CSS và JavaScript không load được, các chức năng không hoạt động.

## Nguyên Nhân
1. PHP built-in server không tự động xử lý file tĩnh (CSS, JS, images) như Apache
2. Base URL không được detect đúng
3. Routing không được xử lý đúng cách

## Giải Pháp Đã Áp Dụng

### 1. Tạo Router Script (`router.php`)
- File này xử lý tất cả các request
- Tự động serve file tĩnh (CSS, JS, images, fonts) trực tiếp
- Route các request khác về `index.php` của CodeIgniter
- Hỗ trợ cache headers để tối ưu hiệu suất

### 2. Sửa Base URL Tự Động (`application/config/config.php`)
- Tự động detect protocol (http/https)
- Tự động detect host và port
- Hoạt động với cả XAMPP/WAMP và PHP built-in server

### 3. Cập Nhật Script Khởi Động
- `start-server.bat` (Windows): Sử dụng router
- `start-server.sh` (Linux/Mac): Sử dụng router

## Cách Sử Dụng

### Cách 1: Dùng Script (Khuyến Nghị)
```bash
# Windows
cd Website
start-server.bat

# Linux/Mac
cd Website
chmod +x start-server.sh
./start-server.sh
```

### Cách 2: Chạy Trực Tiếp
```bash
cd Website
php -S localhost:8000 router.php
```

## Kiểm Tra

Sau khi chạy server:
1. Mở trình duyệt: http://localhost:8000
2. Kiểm tra:
   - ✅ CSS load đúng (giao diện đẹp)
   - ✅ JavaScript hoạt động (menu, slider, v.v.)
   - ✅ Images hiển thị
   - ✅ Tất cả chức năng hoạt động bình thường

## Lưu Ý

⚠️ **MySQL phải đang chạy!**
- Nếu dùng XAMPP: Start MySQL trong XAMPP Control Panel
- Nếu dùng WAMP: Đảm bảo MySQL service đang chạy

## Files Đã Thay Đổi

1. ✅ `Website/router.php` - Router script mới
2. ✅ `Website/application/config/config.php` - Base URL tự động
3. ✅ `Website/start-server.bat` - Cập nhật để dùng router
4. ✅ `Website/start-server.sh` - Cập nhật để dùng router

## Thay Đổi Port

Nếu port 8000 bị chiếm:
```bash
php -S localhost:8080 router.php
```
Base URL sẽ tự động detect port 8080.

---

**Giờ bạn có thể chạy dự án bằng terminal mà CSS và JavaScript vẫn hoạt động bình thường!** 🎉

