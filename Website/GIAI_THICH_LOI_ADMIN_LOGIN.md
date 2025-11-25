# 🔍 GIẢI THÍCH CHI TIẾT LỖI ĐĂNG NHẬP ADMIN

## 📋 TÓM TẮT VẤN ĐỀ

Khi đăng nhập admin, bạn nhập **đúng tài khoản và mật khẩu** nhưng vẫn báo **"Sai tài khoản mật khẩu"** dù **status code là 200**.

**Status code 200** chỉ có nghĩa là **HTTP request thành công**, không có nghĩa là **đăng nhập thành công**. Lỗi xảy ra ở **logic xác thực** trong code PHP.

---

## 🔎 NGUYÊN NHÂN CÓ THỂ

### 1. **Vấn đề với Password Hashing**

#### Cách hoạt động:
- Khi nhập mật khẩu, code sẽ hash bằng **SHA1**: `sha1($password)`
- So sánh với password đã hash trong database
- Nếu không khớp → báo lỗi "Sai mật khẩu"

#### Vấn đề có thể xảy ra:
```php
// Trong User.php (dòng 25)
$password = sha1($this->input->post('password'));

// Nếu bạn đăng ký tài khoản trong SQL mà không hash password:
INSERT INTO db_user (username, password, ...) 
VALUES ('admin', '123456', ...);  // ❌ SAI - password chưa hash

// Phải là:
INSERT INTO db_user (username, password, ...) 
VALUES ('admin', SHA1('123456'), ...);  // ✅ ĐÚNG
```

**Mật khẩu mặc định trong database:**
- `'7c4a8d09ca3762af61e59520943dc26494f8941b'` = SHA1('123456')
- Nếu bạn đăng ký với password khác mà không hash → sẽ không khớp

---

### 2. **Vấn đề với Status và Trash**

#### Logic kiểm tra:
```php
// Trong Muser.php (dòng 13-14)
$this->db->where('status', 1); // Tài khoản phải active
$this->db->where('trash', 1);  // Tài khoản không bị xóa
```

#### Vấn đề:
- Nếu `status = 0` → Tài khoản bị khóa → Không đăng nhập được
- Nếu `trash = 0` → Tài khoản bị xóa → Không đăng nhập được
- Nếu cả hai đều = 1 → Mới đăng nhập được

**Khi đăng ký tài khoản trong SQL, phải đảm bảo:**
```sql
INSERT INTO db_user (username, password, status, trash, ...) 
VALUES ('admin', SHA1('123456'), 1, 1, ...);  -- status=1, trash=1
```

---

### 3. **Vấn đề với Username (Khoảng trắng)**

#### Vấn đề:
- Nếu username có khoảng trắng ở đầu/cuối: `" admin "` ≠ `"admin"`
- Code đã được sửa để trim username, nhưng nếu database có khoảng trắng → vẫn lỗi

---

### 4. **Vấn đề với Session**

#### Logic:
```php
// Trong User.php (dòng 33-34)
$this->session->set_userdata('sessionadmin', $row);
$this->session->set_userdata('id', $row['id']);
redirect('admin','refresh');
```

#### Vấn đề có thể:
- Session không được lưu đúng cách
- Redirect không hoạt động
- Session bị mất sau khi redirect

---

## 🛠️ CÁCH KIỂM TRA VÀ SỬA

### Bước 1: Kiểm tra tài khoản trong Database

Chạy SQL sau để kiểm tra:

```sql
SELECT 
    id,
    username,
    password,
    status,
    trash,
    CASE 
        WHEN status = 0 THEN 'Tài khoản bị KHÓA'
        WHEN trash = 0 THEN 'Tài khoản bị XÓA'
        WHEN status = 1 AND trash = 1 THEN 'Tài khoản HOẠT ĐỘNG'
        ELSE 'Trạng thái không xác định'
    END AS trang_thai
FROM db_user
WHERE username = 'admin';
```

### Bước 2: Kiểm tra Password Hash

```sql
SELECT 
    username,
    password,
    SHA1('123456') AS sha1_123456,
    CASE 
        WHEN password = SHA1('123456') THEN 'Mật khẩu là: 123456'
        WHEN password = SHA1('admin') THEN 'Mật khẩu là: admin'
        ELSE 'Mật khẩu không phải 123456 hoặc admin'
    END AS mat_khau_doan
FROM db_user
WHERE username = 'admin';
```

### Bước 3: Sửa tài khoản (Nếu cần)

#### Sửa Status và Trash:
```sql
UPDATE db_user
SET status = 1, trash = 1
WHERE username = 'admin';
```

#### Reset mật khẩu về '123456':
```sql
UPDATE db_user
SET password = SHA1('123456')
WHERE username = 'admin';
```

#### Tạo tài khoản mới (Nếu chưa có):
```sql
INSERT INTO db_user (
    fullname, username, password, role, email, 
    gender, phone, address, img, created, trash, status
)
VALUES (
    'ADMIN', 'admin', SHA1('123456'), 1, 'admin@gmail.com',
    1, '0167892615', 'Gò vấp', 'user-group.png', NOW(), 1, 1
);
```

---

## 📝 CÁC THAY ĐỔI ĐÃ THỰC HIỆN

### 1. **Cải thiện Error Messages** (`User.php`)

**Trước:**
```php
$data['error'] = 'Thông tin đăng nhập không chính xác.';
```

**Sau:**
```php
if($user_check['status'] == 0) {
    $data['error'] = 'Tài khoản của bạn đã bị khóa.';
} elseif($user_check['trash'] == 0) {
    $data['error'] = 'Tài khoản của bạn đã bị xóa.';
} elseif($user_check['password'] != $password) {
    $data['error'] = 'Mật khẩu không chính xác.';
}
```

### 2. **Thêm Debug Logging** (`User.php` và `Muser.php`)

- Log username và password hash khi đăng nhập
- Log trạng thái user trong database
- Log số lượng rows tìm được

**Xem log tại:** `application/logs/log-YYYY-MM-DD.php`

### 3. **Cải thiện Model** (`Muser.php`)

- Thêm `trim()` cho username
- Sử dụng `$query->num_rows()` thay vì `count($query->result_array())`
- Thêm `limit(1)` để tối ưu query

### 4. **Bật Logging** (`config.php`)

```php
$config['log_threshold'] = 4; // Enable all logging
```

---

## 🧪 CÁCH TEST

### Test 1: Kiểm tra với tài khoản mặc định

1. **Username:** `admin`
2. **Password:** `123456`
3. **Kiểm tra trong database:**
   - `status = 1`
   - `trash = 1`
   - `password = SHA1('123456')`

### Test 2: Xem Log

1. Mở file log: `application/logs/log-YYYY-MM-DD.php`
2. Tìm dòng có `Login attempt` hoặc `User login query`
3. Xem thông tin debug

### Test 3: Test với SQL

Chạy file `debug_admin_login.sql` để kiểm tra và sửa tự động.

---

## ✅ CHECKLIST KIỂM TRA

- [ ] Tài khoản `admin` tồn tại trong database
- [ ] `status = 1` (tài khoản active)
- [ ] `trash = 1` (tài khoản chưa bị xóa)
- [ ] `password` đã được hash bằng SHA1
- [ ] Username không có khoảng trắng thừa
- [ ] Session được lưu đúng cách
- [ ] Redirect hoạt động

---

## 🚨 LƯU Ý QUAN TRỌNG

1. **Status code 200 ≠ Đăng nhập thành công**
   - 200 chỉ có nghĩa là HTTP request thành công
   - Logic xác thực có thể fail → vẫn trả về 200 nhưng hiển thị lỗi

2. **Password phải được hash bằng SHA1**
   - Không được lưu plain text
   - Phải dùng `SHA1()` trong SQL hoặc `sha1()` trong PHP

3. **Status và Trash phải = 1**
   - Nếu một trong hai = 0 → Không đăng nhập được

4. **Xem log để debug**
   - Bật logging trong `config.php`
   - Xem file log để biết chính xác lỗi ở đâu

---

## 📞 NẾU VẪN KHÔNG ĐƯỢC

1. Chạy file `debug_admin_login.sql` để kiểm tra và sửa
2. Xem log file: `application/logs/log-YYYY-MM-DD.php`
3. Kiểm tra lại username và password trong database
4. Đảm bảo `status = 1` và `trash = 1`

---

**Tác giả:** Auto (AI Assistant)  
**Ngày:** 2024  
**Phiên bản:** 1.0

