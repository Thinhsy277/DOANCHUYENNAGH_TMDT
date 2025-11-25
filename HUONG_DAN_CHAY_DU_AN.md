# Hướng Dẫn Chạy Dự Án Website CodeIgniter

## Yêu Cầu Hệ Thống

- **PHP**: >= 5.3.7 (khuyến nghị PHP 7.4 trở lên)
- **MySQL/MariaDB**: 5.6 trở lên
- **Web Server**: Apache hoặc Nginx (hoặc PHP Built-in Server)
- **PHP Extensions**: mysqli, mbstring, gd, curl

## Các Bước Cài Đặt

### Bước 1: Cài Đặt XAMPP/WAMP/LAMP

Nếu chưa có môi trường PHP, cài đặt một trong các phần mềm sau:
- **XAMPP** (Windows/Mac/Linux): https://www.apachefriends.org/
- **WAMP** (Windows): https://www.wampserver.com/
- **LAMP** (Linux): Cài đặt qua package manager

### Bước 2: Copy Dự Án Vào Thư Mục Web Server

**Với XAMPP:**
- Copy thư mục `Website` vào `C:\xampp\htdocs\`
- Đường dẫn sẽ là: `C:\xampp\htdocs\Website\`

**Với WAMP:**
- Copy thư mục `Website` vào `C:\wamp64\www\`
- Đường dẫn sẽ là: `C:\wamp64\www\Website\`

### Bước 3: Tạo Database

1. Mở **phpMyAdmin** (thường tại: http://localhost/phpmyadmin)
2. Tạo database mới tên: `db_shop`
3. Import file SQL:
   - Chọn database `db_shop` vừa tạo
   - Click tab **Import**
   - Chọn file `Website/db_shop.sql`
   - Click **Go** để import

### Bước 4: Cấu Hình Database

Mở file: `Website/application/config/database.php`

Kiểm tra và chỉnh sửa nếu cần:
```php
$db['default'] = array(
    'hostname' => 'localhost',    // Thường là localhost
    'username' => 'root',         // Username MySQL của bạn
    'password' => '',             // Password MySQL của bạn (mặc định XAMPP là trống)
    'database' => 'db_shop',      // Tên database
    'dbdriver' => 'mysqli',
    // ... các cấu hình khác
);
```

### Bước 5: Cấu Hình Base URL

Mở file: `Website/application/config/config.php`

Kiểm tra dòng 26:
```php
$config['base_url'] = 'http://localhost/Website/';
```

**Lưu ý:** 
- Nếu bạn đặt thư mục ở vị trí khác, sửa lại đường dẫn cho đúng
- Nếu dùng port khác (ví dụ: 8080), sửa thành: `http://localhost:8080/Website/`

### Bước 6: Khởi Động Web Server

**Với XAMPP:**
1. Mở **XAMPP Control Panel**
2. Start **Apache** và **MySQL**

**Với WAMP:**
1. Mở **WAMP Server**
2. Đảm bảo icon màu xanh (cả Apache và MySQL đều chạy)

**Với PHP Built-in Server (nếu không dùng XAMPP/WAMP):**
```bash
cd Website
php -S localhost:8000
```
Sau đó truy cập: http://localhost:8000

### Bước 7: Truy Cập Website

Mở trình duyệt và truy cập:
- **Frontend**: http://localhost/Website/
- **Backend/Admin**: http://localhost/Website/admin (nếu có)

## Kiểm Tra Lỗi Thường Gặp

### Lỗi: "Database connection error"
- Kiểm tra MySQL đã chạy chưa
- Kiểm tra thông tin database trong `database.php`
- Kiểm tra database `db_shop` đã được tạo và import chưa

### Lỗi: "404 Not Found"
- Kiểm tra `base_url` trong `config.php` có đúng không
- Kiểm tra file `.htaccess` (nếu có) có đúng cấu hình không
- Kiểm tra Apache mod_rewrite đã bật chưa

### Lỗi: "Permission denied"
- Kiểm tra quyền ghi cho thư mục `application/cache/` và `application/logs/`
- Trên Linux/Mac: `chmod -R 777 application/cache application/logs`

### Lỗi: "Class not found"
- Kiểm tra PHP version (>= 5.3.7)
- Kiểm tra các PHP extensions đã được cài đặt chưa

## Cấu Trúc Thư Mục Quan Trọng

```
Website/
├── application/          # Code ứng dụng
│   ├── config/          # Cấu hình (database, config, routes...)
│   ├── controllers/     # Controllers
│   ├── models/          # Models
│   ├── views/           # Views/Templates
│   └── cache/           # Cache (cần quyền ghi)
├── system/              # CodeIgniter Framework
├── public/              # Assets (CSS, JS, images)
│   ├── css/
│   ├── js/
│   └── images/
├── index.php            # Entry point
└── db_shop.sql          # Database file
```

## Tài Khoản Demo

Kiểm tra file `tài khoản demo.txt` trong thư mục gốc để xem thông tin đăng nhập (nếu có).

## Hỗ Trợ

Nếu gặp vấn đề, kiểm tra:
1. File log: `Website/application/logs/`
2. PHP error log trong XAMPP/WAMP
3. Apache error log

---

**Chúc bạn chạy dự án thành công!** 🚀

