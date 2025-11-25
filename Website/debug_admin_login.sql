-- ============================================
-- SCRIPT DEBUG VÀ SỬA LỖI ĐĂNG NHẬP ADMIN
-- ============================================

-- 1. KIỂM TRA TẤT CẢ TÀI KHOẢN ADMIN
SELECT 
    id,
    fullname,
    username,
    password,
    role,
    status,
    trash,
    email,
    created
FROM db_user
WHERE role = 1 OR username = 'admin'
ORDER BY id;

-- 2. KIỂM TRA TÀI KHOẢN ADMIN CỤ THỂ
SELECT 
    id,
    fullname,
    username,
    password,
    role,
    status,
    trash,
    email,
    created,
    CASE 
        WHEN status = 0 THEN 'Tài khoản bị KHÓA'
        WHEN trash = 0 THEN 'Tài khoản bị XÓA'
        WHEN status = 1 AND trash = 1 THEN 'Tài khoản HOẠT ĐỘNG'
        ELSE 'Trạng thái không xác định'
    END AS trang_thai
FROM db_user
WHERE username = 'admin';

-- 3. KIỂM TRA MẬT KHẨU SHA1
-- Mật khẩu '123456' = SHA1('123456') = '7c4a8d09ca3762af61e59520943dc26494f8941b'
-- Mật khẩu 'admin' = SHA1('admin') = 'd033e22ae348aeb5660fc2140aec35850c4da997'
-- Mật khẩu 'password' = SHA1('password') = '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8'

SELECT 
    username,
    password,
    SHA1('123456') AS sha1_123456,
    SHA1('admin') AS sha1_admin,
    CASE 
        WHEN password = SHA1('123456') THEN 'Mật khẩu là: 123456'
        WHEN password = SHA1('admin') THEN 'Mật khẩu là: admin'
        ELSE 'Mật khẩu không phải 123456 hoặc admin'
    END AS mat_khau_doan
FROM db_user
WHERE username = 'admin';

-- 4. SỬA TÀI KHOẢN ADMIN (NẾU CẦN)
-- Kích hoạt lại tài khoản admin
UPDATE db_user
SET 
    status = 1,
    trash = 1
WHERE username = 'admin';

-- 5. RESET MẬT KHẨU ADMIN VỀ '123456'
UPDATE db_user
SET password = SHA1('123456')
WHERE username = 'admin';

-- 6. TẠO TÀI KHOẢN ADMIN MỚI (NẾU CHƯA CÓ)
-- Chỉ chạy nếu tài khoản admin chưa tồn tại
INSERT INTO db_user (
    fullname, 
    username, 
    password, 
    role, 
    email, 
    gender, 
    phone, 
    address, 
    img, 
    created, 
    trash, 
    status
)
SELECT 
    'ADMIN',
    'admin',
    SHA1('123456'),
    1,
    'admin@gmail.com',
    1,
    '0167892615',
    'Gò vấp',
    'user-group.png',
    NOW(),
    1,
    1
WHERE NOT EXISTS (
    SELECT 1 FROM db_user WHERE username = 'admin'
);

-- ============================================
-- HƯỚNG DẪN SỬ DỤNG:
-- ============================================
-- 1. Chạy câu lệnh số 1 để xem tất cả tài khoản admin
-- 2. Chạy câu lệnh số 2 để kiểm tra trạng thái tài khoản admin
-- 3. Chạy câu lệnh số 3 để kiểm tra mật khẩu
-- 4. Nếu status = 0 hoặc trash = 0, chạy câu lệnh số 4
-- 5. Nếu cần reset mật khẩu, chạy câu lệnh số 5
-- 6. Nếu tài khoản admin chưa tồn tại, chạy câu lệnh số 6
-- ============================================
