-- Script kiểm tra và sửa tài khoản admin
-- Chạy script này để kiểm tra tài khoản admin trong database

-- 1. Kiểm tra tất cả tài khoản admin
SELECT 
    id, 
    username, 
    fullname, 
    email,
    role,
    status,
    trash,
    CASE 
        WHEN status = 0 THEN 'Tài khoản bị khóa'
        WHEN trash = 0 THEN 'Tài khoản đã bị xóa'
        WHEN status = 1 AND trash = 1 THEN 'Tài khoản hoạt động bình thường'
        ELSE 'Tài khoản có vấn đề'
    END AS trang_thai
FROM db_user
WHERE username = 'admin' OR role = 1;

-- 2. Sửa tài khoản admin nếu cần (uncomment để chạy)
-- UPDATE db_user 
-- SET status = 1, trash = 1 
-- WHERE username = 'admin' AND (status = 0 OR trash = 0);

-- 3. Tạo tài khoản admin mới nếu chưa có (password: 123456)
-- INSERT INTO db_user (fullname, username, password, role, email, gender, phone, address, img, created, trash, status) 
-- VALUES ('ADMIN', 'admin', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1, 'admin@gmail.com', 1, '0167892615', 'Gò vấp', 'user-group.png', NOW(), 1, 1)
-- ON DUPLICATE KEY UPDATE status = 1, trash = 1;

-- 4. Kiểm tra password hash (password: 123456 = sha1('123456'))
-- SELECT SHA1('123456') AS password_hash;
-- Kết quả: 7c4a8d09ca3762af61e59520943dc26494f8941b

