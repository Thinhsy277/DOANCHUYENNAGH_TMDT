<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller để debug và sửa lỗi đăng nhập admin
 * Truy cập: http://localhost:8000/admin/debug/fix_admin
 * 
 * LƯU Ý: XÓA FILE NÀY SAU KHI ĐÃ SỬA XONG ĐỂ BẢO MẬT!
 */
class Debug extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        // Chỉ cho phép trong development mode
        if(ENVIRONMENT !== 'development') {
            show_404();
        }
    }
    
    /**
     * Kiểm tra và sửa tài khoản admin
     */
    public function fix_admin()
    {
        $results = [];
        $fixes = [];
        
        // 1. Kiểm tra tài khoản admin
        $this->db->where('username', 'admin');
        $query = $this->db->get('db_user');
        $admin = $query->row_array();
        
        if(empty($admin)) {
            $results[] = "❌ Tài khoản 'admin' KHÔNG TỒN TẠI trong database!";
            $results[] = "🔧 Đang tạo tài khoản admin mới...";
            
            // Tạo tài khoản admin mới
            $data = [
                'fullname' => 'ADMIN',
                'username' => 'admin',
                'password' => sha1('123456'),
                'role' => 1,
                'email' => 'admin@gmail.com',
                'gender' => 1,
                'phone' => '0167892615',
                'address' => 'Gò vấp',
                'img' => 'user-group.png',
                'created' => date('Y-m-d H:i:s'),
                'trash' => 1,
                'status' => 1
            ];
            
            if($this->db->insert('db_user', $data)) {
                $fixes[] = "✅ Đã tạo tài khoản admin mới thành công!";
                $fixes[] = "   Username: admin";
                $fixes[] = "   Password: 123456";
            } else {
                $results[] = "❌ Lỗi khi tạo tài khoản: " . $this->db->error()['message'];
            }
        } else {
            $results[] = "✅ Tài khoản 'admin' đã tồn tại (ID: {$admin['id']})";
            
            // 2. Kiểm tra status
            if($admin['status'] != 1) {
                $results[] = "⚠️ Status = {$admin['status']} (phải = 1)";
                $this->db->where('id', $admin['id']);
                $this->db->update('db_user', ['status' => 1]);
                $fixes[] = "✅ Đã sửa status = 1";
            } else {
                $results[] = "✅ Status = 1 (OK)";
            }
            
            // 3. Kiểm tra trash
            if($admin['trash'] != 1) {
                $results[] = "⚠️ Trash = {$admin['trash']} (phải = 1)";
                $this->db->where('id', $admin['id']);
                $this->db->update('db_user', ['trash' => 1]);
                $fixes[] = "✅ Đã sửa trash = 1";
            } else {
                $results[] = "✅ Trash = 1 (OK)";
            }
            
            // 4. Kiểm tra password
            $password_123456 = sha1('123456');
            $password_admin = sha1('admin');
            
            if($admin['password'] != $password_123456 && $admin['password'] != $password_admin) {
                $results[] = "⚠️ Password không khớp với '123456' hoặc 'admin'";
                $results[] = "   Password hiện tại: {$admin['password']}";
                $results[] = "   Password mong đợi (123456): {$password_123456}";
                
                // Reset password về '123456'
                $this->db->where('id', $admin['id']);
                $this->db->update('db_user', ['password' => $password_123456]);
                $fixes[] = "✅ Đã reset password về '123456'";
            } else {
                if($admin['password'] == $password_123456) {
                    $results[] = "✅ Password = SHA1('123456') (OK)";
                } else {
                    $results[] = "✅ Password = SHA1('admin') (OK)";
                }
            }
            
            // 5. Hiển thị thông tin tài khoản
            $results[] = "";
            $results[] = "📋 THÔNG TIN TÀI KHOẢN:";
            $results[] = "   Fullname: {$admin['fullname']}";
            $results[] = "   Username: {$admin['username']}";
            $results[] = "   Email: {$admin['email']}";
            $results[] = "   Role: {$admin['role']}";
            $results[] = "   Status: {$admin['status']}";
            $results[] = "   Trash: {$admin['trash']}";
        }
        
        // Hiển thị kết quả
        echo "<!DOCTYPE html>";
        echo "<html><head><meta charset='UTF-8'><title>Debug Admin Login</title>";
        echo "<style>
            body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
            .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
            .result { margin: 10px 0; padding: 10px; background: #f8f9fa; border-left: 4px solid #007bff; }
            .fix { margin: 10px 0; padding: 10px; background: #d4edda; border-left: 4px solid #28a745; }
            .warning { color: #856404; background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; }
            .success { color: #155724; background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0; }
            .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
            .btn:hover { background: #0056b3; }
        </style></head><body>";
        echo "<div class='container'>";
        echo "<h1>🔧 Debug và Sửa Lỗi Đăng Nhập Admin</h1>";
        
        echo "<div class='warning'>";
        echo "<strong>⚠️ LƯU Ý:</strong> File này chỉ nên được sử dụng trong môi trường development. ";
        echo "Hãy xóa file này sau khi đã sửa xong để bảo mật!";
        echo "</div>";
        
        echo "<h2>📊 Kết Quả Kiểm Tra:</h2>";
        foreach($results as $result) {
            echo "<div class='result'>" . htmlspecialchars($result) . "</div>";
        }
        
        if(!empty($fixes)) {
            echo "<h2>🔧 Các Thay Đổi Đã Thực Hiện:</h2>";
            foreach($fixes as $fix) {
                echo "<div class='fix'>" . htmlspecialchars($fix) . "</div>";
            }
        }
        
        echo "<div class='success'>";
        echo "<strong>✅ Hoàn tất!</strong><br>";
        echo "Bây giờ bạn có thể thử đăng nhập với:<br>";
        echo "<strong>Username:</strong> admin<br>";
        echo "<strong>Password:</strong> 123456<br><br>";
        echo "<a href='" . base_url('admin/user/login') . "' class='btn'>Đi đến trang đăng nhập</a>";
        echo "</div>";
        
        echo "</div></body></html>";
    }
    
    /**
     * Chỉ kiểm tra, không sửa
     */
    public function check_admin()
    {
        $results = [];
        
        // Kiểm tra tài khoản admin
        $this->db->where('username', 'admin');
        $query = $this->db->get('db_user');
        $admin = $query->row_array();
        
        if(empty($admin)) {
            $results[] = "❌ Tài khoản 'admin' KHÔNG TỒN TẠI";
        } else {
            $results[] = "✅ Tài khoản 'admin' tồn tại (ID: {$admin['id']})";
            $results[] = "   Status: " . ($admin['status'] == 1 ? "✅ OK" : "❌ = {$admin['status']} (phải = 1)");
            $results[] = "   Trash: " . ($admin['trash'] == 1 ? "✅ OK" : "❌ = {$admin['trash']} (phải = 1)");
            
            $password_123456 = sha1('123456');
            $password_admin = sha1('admin');
            
            if($admin['password'] == $password_123456) {
                $results[] = "   Password: ✅ Khớp với '123456'";
            } elseif($admin['password'] == $password_admin) {
                $results[] = "   Password: ✅ Khớp với 'admin'";
            } else {
                $results[] = "   Password: ❌ Không khớp với '123456' hoặc 'admin'";
                $results[] = "   Password hiện tại: {$admin['password']}";
            }
        }
        
        echo "<pre>";
        echo "=== KIỂM TRA TÀI KHOẢN ADMIN ===\n\n";
        foreach($results as $result) {
            echo $result . "\n";
        }
        echo "\n=== KẾT THÚC ===\n";
        echo "</pre>";
    }
}

