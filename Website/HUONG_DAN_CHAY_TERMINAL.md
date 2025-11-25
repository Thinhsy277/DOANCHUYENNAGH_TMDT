# Hướng Dẫn Chạy Dự Án Bằng Terminal

## Yêu Cầu
- PHP >= 5.3.7 (đã kiểm tra: PHP 8.0.30 ✓)
- MySQL/MariaDB đang chạy
- Database `db_shop` đã được tạo và import

## Cách 1: Chạy Bằng Script (Dễ Nhất)

### Windows:
```bash
cd Website
start-server.bat
```

### Linux/Mac:
```bash
cd Website
chmod +x start-server.sh
./start-server.sh
```

## Cách 2: Chạy Trực Tiếp Bằng Lệnh

### Windows (PowerShell):
```powershell
cd "C:\Users\PC\Downloads\Website-xvn6mu (1)\Website"
php -S localhost:8000
```

### Windows (CMD):
```cmd
cd C:\Users\PC\Downloads\Website-xvn6mu (1)\Website
php -S localhost:8000
```

### Linux/Mac:
```bash
cd Website
php -S localhost:8000
```

## Sau Khi Chạy

1. **Mở trình duyệt** và truy cập:
   ```
   http://localhost:8000
   ```

2. **Để dừng server**: Nhấn `Ctrl + C` trong terminal

## Lưu Ý Quan Trọng

⚠️ **MySQL phải đang chạy!**

- Nếu dùng XAMPP: Start MySQL trong XAMPP Control Panel
- Nếu dùng WAMP: Đảm bảo MySQL service đang chạy
- Hoặc chạy MySQL service riêng:
  ```bash
  # Windows (nếu MySQL cài riêng)
  net start MySQL
  
  # Linux
  sudo systemctl start mysql
  # hoặc
  sudo service mysql start
  ```

## Kiểm Tra

- ✅ Server chạy thành công nếu thấy: `PHP 8.0.30 Development Server started`
- ✅ Website hiển thị bình thường
- ❌ Nếu lỗi database: Kiểm tra MySQL đã chạy chưa

## Thay Đổi Port

Nếu port 8000 bị chiếm, dùng port khác:
```bash
php -S localhost:8080
```
Sau đó truy cập: `http://localhost:8080`

