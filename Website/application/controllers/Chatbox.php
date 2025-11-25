<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chatbox extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('frontend/Mchatbox');
        $this->load->model('frontend/Mproduct');
        $this->load->model('frontend/Mcategory');
        $this->load->library('session');
        
        // Tự động tạo table nếu chưa tồn tại
        $this->create_chatbox_table_if_not_exists();
    }
    
    /**
     * Tự động tạo table db_chatbox nếu chưa tồn tại
     */
    private function create_chatbox_table_if_not_exists() {
        $table_name = $this->db->dbprefix('chatbox');
        
        // Kiểm tra xem table đã tồn tại chưa
        if (!$this->db->table_exists($table_name)) {
            $sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL DEFAULT 0 COMMENT 'ID người dùng (0 nếu chưa đăng nhập)',
                `sender` enum('user','admin','bot') DEFAULT 'user' COMMENT 'Người gửi: user, admin, hoặc bot',
                `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT 'Nội dung tin nhắn',
                `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Đường dẫn ảnh (nếu có)',
                `msg_type` enum('text','button') DEFAULT 'text' COMMENT 'Loại tin nhắn: text hoặc button',
                `intent` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Intent/keyword: duoi_10_tr, gia_re, sale, chao_hoi, xem_san_pham, etc.',
                `is_bot_reply` tinyint(1) DEFAULT 0 COMMENT '1 nếu là phản hồi tự động của bot, 0 nếu là tin nhắn người dùng',
                `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
                PRIMARY KEY (`id`),
                KEY `idx_user_id` (`user_id`),
                KEY `idx_created_at` (`created_at`),
                KEY `idx_intent` (`intent`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Bảng lưu trữ lịch sử chat giữa người dùng và bot'";
            
            $this->db->query($sql);
        }
    }
    
    /**
     * Xử lý tin nhắn từ người dùng và trả về phản hồi
     */
    public function send_message() {
        // Clean any previous output
        if (ob_get_length()) ob_clean();
        
        // Set headers first
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type');
        
        try {
            $message = $this->input->post('message');
            $user_id = 0;
            
            // Lấy user_id nếu đã đăng nhập
            if($this->session->userdata('sessionKhachHang')) {
                $user = $this->session->userdata('sessionKhachHang');
                $user_id = isset($user['id']) ? $user['id'] : 0;
            }
            
            if(empty($message)) {
                http_response_code(200);
                echo json_encode(['success' => false, 'error' => 'Tin nhắn không được để trống'], JSON_UNESCAPED_UNICODE);
                return;
            }
            
            // Lưu tin nhắn của người dùng vào database
            $user_message = [
                'user_id' => $user_id,
                'sender' => 'user',
                'message' => $message,
                'msg_type' => 'text',
                'is_bot_reply' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Try to insert, but don't fail if table doesn't exist yet
            try {
                $msg_id = $this->Mchatbox->chatbox_insert($user_message);
            } catch(Exception $e) {
                // Log error but continue
                log_message('error', 'Chatbox insert error: ' . $e->getMessage());
            }
            
            // Xử lý và tạo phản hồi từ bot
            try {
                $bot_response = $this->process_message($message, $user_id);
            } catch(Exception $e) {
                log_message('error', 'Chatbox process_message error: ' . $e->getMessage());
                $bot_response = [
                    'message' => 'Xin lỗi, có lỗi xảy ra khi xử lý tin nhắn. Vui lòng thử lại sau.',
                    'msg_type' => 'text'
                ];
            } catch(Error $e) {
                log_message('error', 'Chatbox process_message fatal error: ' . $e->getMessage());
                $bot_response = [
                    'message' => 'Xin lỗi, có lỗi xảy ra khi xử lý tin nhắn. Vui lòng thử lại sau.',
                    'msg_type' => 'text'
                ];
            }
            
            // Ensure bot_response is always an array
            if(!is_array($bot_response)) {
                $bot_response = ['message' => 'Xin lỗi, có lỗi xảy ra khi xử lý tin nhắn.', 'msg_type' => 'text'];
            }
            
            // Lưu phản hồi của bot vào database
            $bot_message = [
                'user_id' => $user_id,
                'sender' => 'bot',
                'message' => isset($bot_response['message']) ? $bot_response['message'] : json_encode($bot_response),
                'msg_type' => isset($bot_response['msg_type']) ? $bot_response['msg_type'] : 'text',
                'intent' => isset($bot_response['intent']) ? $bot_response['intent'] : null,
                'is_bot_reply' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            try {
                $this->Mchatbox->chatbox_insert($bot_message);
            } catch(Exception $e) {
                // Log error but continue
                log_message('error', 'Chatbox insert error: ' . $e->getMessage());
            }
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'user_message' => $user_message,
                'bot_response' => $bot_response
            ], JSON_UNESCAPED_UNICODE);
            exit;
            
        } catch(Exception $e) {
            log_message('error', 'Chatbox send_message error: ' . $e->getMessage());
            http_response_code(200);
            echo json_encode([
                'success' => false,
                'error' => 'Có lỗi xảy ra. Vui lòng thử lại sau.',
                'debug' => ENVIRONMENT === 'development' ? $e->getMessage() : null
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } catch(Error $e) {
            log_message('error', 'Chatbox send_message fatal error: ' . $e->getMessage());
            http_response_code(200);
            echo json_encode([
                'success' => false,
                'error' => 'Có lỗi xảy ra. Vui lòng thử lại sau.',
                'debug' => ENVIRONMENT === 'development' ? $e->getMessage() : null
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    
    /**
     * Xử lý tin nhắn và tạo phản hồi thông minh
     */
    private function process_message($message, $user_id = 0) {
        $message_lower = mb_strtolower($message, 'UTF-8');
        $message_lower = trim($message_lower);
        
        // Chào hỏi
        if($this->check_intent($message_lower, ['xin chào', 'chào', 'hello', 'hi', 'chào bạn', 'chào bot', 'hey'])) {
            return [
                'message' => '👋 <strong>Xin chào!</strong> Tôi là trợ lý ảo của cửa hàng. Tôi có thể giúp bạn:<br><br>✨ <strong>Tìm sản phẩm theo giá:</strong> "sản phẩm 20 triệu", "dưới 10 triệu"<br>🔥 <strong>Tìm sản phẩm đang giảm giá:</strong> "sale", "giảm giá"<br>🔍 <strong>Tìm sản phẩm theo tên:</strong> "iPhone", "Samsung", "Laptop"<br>📱 <strong>Tìm theo danh mục:</strong> "điện thoại", "laptop", "tablet"<br><br>💬 <strong>Bạn muốn tìm gì hôm nay?</strong>',
                'intent' => 'chao_hoi',
                'msg_type' => 'button',
                'buttons' => [
                    ['text' => '💰 Sản phẩm dưới 10 triệu', 'value' => 'dưới 10 triệu'],
                    ['text' => '💵 Sản phẩm giá rẻ', 'value' => 'giá rẻ'],
                    ['text' => '🔥 Sản phẩm đang sale', 'value' => 'sale'],
                    ['text' => '📦 Xem tất cả sản phẩm', 'value' => 'xem tất cả sản phẩm']
                ]
            ];
        }
        
        // Nếu người dùng hỏi giá một sản phẩm cụ thể (không kèm số)
        if($this->check_intent($message_lower, ['giá', 'gia', 'bao nhiêu', 'bao nhieu'])) {
            $price_keywords = trim($this->extract_keywords($message_lower));
            // Nếu câu hỏi không chứa số cụ thể thì xử lý như hỏi giá sản phẩm
            if(!empty($price_keywords) && !preg_match('/\d+\s*(triệu|tr|million|m)/i', $message_lower)) {
                $price_response = $this->get_product_price_response($price_keywords);
                if($price_response) {
                    return $price_response;
                }
            }
        }
        
        // Tìm giá trong tin nhắn (ví dụ: "20 triệu", "15 triệu", "sản phẩm 20 triệu")
        if(preg_match('/(\d+)\s*(triệu|tr|million|m)/i', $message_lower, $matches)) {
            $price = intval($matches[1]) * 1000000; // Chuyển triệu thành VNĐ
            $max_price = $price;
            $min_price = 0;
            
            // Kiểm tra "dưới" hoặc "từ ... đến"
            if(strpos($message_lower, 'dưới') !== false || strpos($message_lower, 'duoi') !== false) {
                $max_price = $price;
                $min_price = 0;
            } elseif(strpos($message_lower, 'từ') !== false && preg_match('/từ\s*(\d+).*đến\s*(\d+)/i', $message_lower, $range_matches)) {
                $min_price = intval($range_matches[1]) * 1000000;
                $max_price = intval($range_matches[2]) * 1000000;
            } elseif(strpos($message_lower, 'trên') !== false || strpos($message_lower, 'tren') !== false) {
                $min_price = $price;
                $max_price = 999999999; // Giá cao nhất
            } else {
                // Nếu chỉ có số, tìm sản phẩm trong khoảng ±30% để linh hoạt hơn
                $min_price = max(0, $price * 0.7);
                $max_price = $price * 1.3;
            }
            
            $price_keywords = trim($this->extract_keywords($message_lower));
            return $this->get_products_by_price($min_price, $max_price, 'price_search', $price_keywords);
        }
        
        // Xem tất cả sản phẩm - chuyển hướng
        if($this->check_intent($message_lower, ['xem tất cả sản phẩm', 'xem tat ca san pham', 'tất cả sản phẩm', 'tat ca san pham'])) {
            return [
                'message' => 'Đang chuyển hướng đến trang sản phẩm...',
                'intent' => 'redirect',
                'msg_type' => 'redirect',
                'url' => base_url('san-pham/1')
            ];
        }
        
        // Tìm sản phẩm dưới 10 triệu
        if($this->check_intent($message_lower, ['dưới 10 triệu', 'duoi 10 trieu', 'dưới 10tr', 'duoi 10tr', 'giá dưới 10 triệu', 'duoi 10 trieu'])) {
            return $this->get_products_by_price(0, 10000000, 'duoi_10_tr');
        }
        
        // Tìm sản phẩm giá rẻ
        if($this->check_intent($message_lower, ['giá rẻ', 'gia re', 'rẻ', 're', 'giá thấp', 'gia thap', 'rẻ nhất', 'gia re'])) {
            return $this->get_products_by_price(0, 5000000, 'gia_re');
        }
        
        // Tìm sản phẩm đang sale
        if($this->check_intent($message_lower, ['sale', 'giảm giá', 'giam gia', 'khuyến mãi', 'khuyen mai', 'đang sale', 'giảm'])) {
            return $this->get_products_on_sale('sale');
        }
        
        // Tìm sản phẩm theo danh mục (ưu tiên) - kiểm tra trước khi tìm theo tên
        $category_keywords = [
            'laptop' => 'laptop',
            'điện thoại' => 'dien-thoai',
            'dien thoai' => 'dien-thoai',
            'tablet' => 'tablet',
            'đồng hồ' => 'dong-ho',
            'dong ho' => 'dong-ho',
            'phụ kiện' => 'phu-kien',
            'phu kien' => 'phu-kien'
        ];
        
        foreach($category_keywords as $keyword => $category_link) {
            if(strpos($message_lower, $keyword) !== false) {
                return $this->get_products_by_category($category_link);
            }
        }
        
        // Tìm sản phẩm theo tên (tìm kiếm thông minh hơn)
        $keywords = $this->extract_keywords($message_lower);
        if(!empty($keywords) && strlen($keywords) > 2) {
            return $this->search_products($keywords);
        }
        
        // Phản hồi thông minh hơn
        // Nếu vẫn chưa xác định được ý định -> gợi ý sản phẩm mới nhất
        return $this->get_default_products('default');
    }
    
    /**
     * Kiểm tra intent trong tin nhắn
     */
    private function check_intent($message, $keywords) {
        foreach($keywords as $keyword) {
            if(strpos($message, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Lấy sản phẩm theo giá
     */
    private function get_products_by_price($min_price, $max_price, $intent, $keywords = '') {
        $this->load->model('frontend/Mproduct');
        
        // Lấy sản phẩm trong khoảng giá (ưu tiên price_sale, nếu không có thì dùng price)
        $this->db->where('status', 1);
        $this->db->where('trash', 1);
        $this->db->group_start();
        // Sản phẩm có price_sale trong khoảng
        $this->db->group_start();
        $this->db->where('price_sale >', 0);
        $this->db->where('price_sale >=', $min_price);
        $this->db->where('price_sale <=', $max_price);
        $this->db->group_end();
        // Hoặc sản phẩm có price trong khoảng (nếu không có price_sale hoặc price_sale = 0)
        $this->db->or_group_start();
        $this->db->where('(price_sale = 0 OR price_sale IS NULL)', null, false);
        $this->db->where('price >=', $min_price);
        $this->db->where('price <=', $max_price);
        $this->db->group_end();
        $this->db->group_end();
        if(!empty($keywords)) {
            $normalized = $this->normalize_text($keywords);
            $this->db->group_start();
            $this->db->like('name', $keywords);
            $keyword_parts = preg_split('/\s+/', $keywords);
            if(is_array($keyword_parts)) {
                foreach($keyword_parts as $part) {
                    $part = trim($part);
                    if(strlen($part) > 1) {
                        $this->db->or_like('name', $part);
                    }
                }
            }
            if(!empty($normalized)) {
                $this->db->or_like('alias', $this->slugify($normalized));
            }
            $this->db->group_end();
        }

        // Sử dụng order_by đơn giản hơn
        $this->db->order_by('price_sale', 'ASC');
        $this->db->order_by('price', 'ASC');
        $this->db->limit(6);
        
        try {
            $query = $this->db->get('db_product');
            $products = $query->result_array();
        } catch(Exception $e) {
            log_message('error', 'Chatbox get_products_by_price error: ' . $e->getMessage());
            return [
                'message' => 'Xin lỗi, có lỗi xảy ra khi tìm kiếm sản phẩm. Vui lòng thử lại sau.',
                'intent' => $intent,
                'msg_type' => 'text'
            ];
        }
        
        if(empty($products)) {
            return $this->get_default_products($intent);
        }
        
        $message = "Tôi tìm thấy " . count($products) . " sản phẩm phù hợp:\n\n";
        $products_html = [];
        
        foreach($products as $product) {
            // Xác định giá hiển thị
            $display_price = ($product['price_sale'] > 0) ? $product['price_sale'] : $product['price'];
            $price = number_format($display_price);
            $original_price = ($product['price_sale'] > 0 && $product['price'] > $product['price_sale']) 
                ? number_format($product['price']) . ' VNĐ' : '';
            $discount = ($product['price'] > 0 && $product['price_sale'] > 0 && $product['price_sale'] < $product['price']) 
                ? round((($product['price'] - $product['price_sale']) / $product['price']) * 100) 
                : 0;
            
            // URL sản phẩm - route là (:any) = sanpham/detail/$1
            $product_url = base_url($product['alias']);
            $image_url = base_url('public/images/products/' . $product['avatar']);
            
            $products_html[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $price . ' VNĐ',
                'original_price' => $original_price,
                'discount' => $discount > 0 ? $discount . '%' : '',
                'image' => $image_url,
                'url' => $product_url,
                'alias' => $product['alias']
            ];
            
            $message .= "• " . $product['name'] . " - " . $price . " VNĐ";
            if($discount > 0) {
                $message .= " (Giảm " . $discount . "%)";
            }
            $message .= "\n";
        }
        
        return [
            'message' => $message,
            'intent' => $intent,
            'msg_type' => 'product_list',
            'products' => $products_html
        ];
    }
    
    /**
     * Lấy sản phẩm đang sale
     */
    private function get_products_on_sale($intent) {
        $this->load->model('frontend/Mproduct');
        
        $this->db->where('status', 1);
        $this->db->where('trash', 1);
        $this->db->where('price_sale >', 0);
        $this->db->where('price_sale < price', null, false);
        $this->db->order_by('sale', 'DESC');
        $this->db->limit(6);
        $query = $this->db->get('db_product');
        $products = $query->result_array();
        
        if(empty($products)) {
            return $this->get_default_products($intent);
        }
        
        $message = "Các sản phẩm đang giảm giá:\n\n";
        $products_html = [];
        
        foreach($products as $product) {
            $price = number_format($product['price_sale']);
            $original_price = number_format($product['price']);
            $discount = round((($product['price'] - $product['price_sale']) / $product['price']) * 100);
            
            // URL sản phẩm - route là (:any) = sanpham/detail/$1
            $product_url = base_url($product['alias']);
            $image_url = base_url('public/images/products/' . $product['avatar']);
            
            $products_html[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $price . ' VNĐ',
                'original_price' => $original_price . ' VNĐ',
                'discount' => $discount . '%',
                'image' => $image_url,
                'url' => $product_url,
                'alias' => $product['alias']
            ];
            
            $message .= "• " . $product['name'] . " - " . $price . " VNĐ (Giảm " . $discount . "%)\n";
        }
        
        return [
            'message' => $message,
            'intent' => $intent,
            'msg_type' => 'product_list',
            'products' => $products_html
        ];
    }
    
    /**
     * Tìm kiếm sản phẩm theo từ khóa
     */
    private function search_products($keywords) {
        $this->load->model('frontend/Mproduct');
        $normalized_keywords = $this->normalize_text($keywords);
        
        $this->db->where('status', 1);
        $this->db->where('trash', 1);
        $this->db->group_start();
        $this->db->like('name', $keywords);
        // Tách từng từ khóa để tăng khả năng match
        $keyword_parts = preg_split('/\s+/', $keywords);
        if(is_array($keyword_parts)) {
            foreach($keyword_parts as $part) {
                $part = trim($part);
                if(strlen($part) > 1) {
                    $this->db->or_like('name', $part);
                }
            }
        }
        // So khớp alias theo phiên bản không dấu
        if(!empty($normalized_keywords)) {
            $this->db->or_like('alias', $this->slugify($normalized_keywords));
        }
        $this->db->group_end();
        $this->db->order_by('id', 'DESC');
        $this->db->limit(6);
        
        try {
            $query = $this->db->get('db_product');
            $products = $query->result_array();
        } catch(Exception $e) {
            log_message('error', 'Chatbox search_products error: ' . $e->getMessage());
            return [
                'message' => 'Xin lỗi, có lỗi xảy ra khi tìm kiếm sản phẩm. Vui lòng thử lại sau.',
                'intent' => 'search',
                'msg_type' => 'text'
            ];
        }
        
        if(empty($products)) {
            return $this->get_default_products('default');
        }
        
        $message = "Tìm thấy " . count($products) . " sản phẩm:\n\n";
        $products_html = [];
        
        foreach($products as $product) {
            $price = $product['price_sale'] > 0 ? number_format($product['price_sale']) : number_format($product['price']);
            $price_text = $price . ' VNĐ';
            
            // URL sản phẩm - route là (:any) = sanpham/detail/$1
            $product_url = base_url($product['alias']);
            $image_url = base_url('public/images/products/' . $product['avatar']);
            
            $products_html[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $price_text,
                'image' => $image_url,
                'url' => $product_url,
                'alias' => $product['alias']
            ];
            
            $message .= "• " . $product['name'] . " - " . $price_text . "\n";
        }
        
        return [
            'message' => $message,
            'intent' => 'search',
            'msg_type' => 'product_list',
            'products' => $products_html
        ];
    }
    
    /**
     * Lấy sản phẩm theo danh mục
     */
    private function get_products_by_category($category_link) {
        $this->load->model('frontend/Mcategory');
        try {
            $cat = $this->Mcategory->category_id($category_link);
        } catch(Exception $e) {
            log_message('error', 'Chatbox get_products_by_category error: ' . $e->getMessage());
            $cat = null;
        }
        
        if(!$cat || $cat <= 0) {
        return $this->get_default_products('default');
        }
        
        $this->db->where('status', 1);
        $this->db->where('trash', 1);
        $this->db->where('catid', $cat);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(6);
        
        try {
            $query = $this->db->get('db_product');
            $products = $query->result_array();
        } catch(Exception $e) {
            log_message('error', 'Chatbox get_products_by_category query error: ' . $e->getMessage());
            return [
                'message' => 'Xin lỗi, có lỗi xảy ra khi tìm kiếm sản phẩm theo danh mục. Vui lòng thử lại sau.',
                'intent' => 'category',
                'msg_type' => 'text'
            ];
        }
        
        if(empty($products)) {
            return $this->get_default_products('default');
        }
        
        $message = "Sản phẩm trong danh mục " . $category_link . ":\n\n";
        $products_html = [];
        
        foreach($products as $product) {
            $price = $product['price_sale'] > 0 ? number_format($product['price_sale']) : number_format($product['price']);
            
            // URL sản phẩm - route là (:any) = sanpham/detail/$1
            $product_url = base_url($product['alias']);
            $image_url = base_url('public/images/products/' . $product['avatar']);
            
            $products_html[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $price . ' VNĐ',
                'image' => $image_url,
                'url' => $product_url,
                'alias' => $product['alias']
            ];
            
            $message .= "• " . $product['name'] . " - " . $price . " VNĐ\n";
        }
        
        return [
            'message' => $message,
            'intent' => 'category',
            'msg_type' => 'product_list',
            'products' => $products_html
        ];
    }
    
    /**
     * Trích xuất từ khóa từ tin nhắn
     */
    private function extract_keywords($message) {
        // Loại bỏ các từ không cần thiết
        $stop_words = ['tìm', 'tim', 'xem', 'mua', 'sản phẩm', 'san pham', 'cho tôi', 'cho toi', 'bạn', 'ban', 'có', 'co'];
        $words = explode(' ', $message);
        $keywords = [];
        
        foreach($words as $word) {
            $word = trim($word);
            if(!empty($word) && !in_array($word, $stop_words) && strlen($word) > 2) {
                $keywords[] = $word;
            }
        }
        
        return implode(' ', $keywords);
    }

    /**
     * Gợi ý danh sách sản phẩm mặc định (ví dụ sản phẩm mới nhất)
     */
    private function get_default_products($intent = 'default') {
        $this->load->model('frontend/Mproduct');
        $this->db->where('status', 1);
        $this->db->where('trash', 1);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(6);
        $query = $this->db->get('db_product');
        $products = $query->result_array();

        if(empty($products)) {
            return [
                'message' => 'Xin lỗi, hiện tại tôi chưa tìm thấy sản phẩm phù hợp. Bạn có thể thử tìm với từ khóa khác nhé!',
                'intent' => $intent,
                'msg_type' => 'text'
            ];
        }

        $products_html = [];
        foreach($products as $product) {
            $price = $product['price_sale'] > 0 ? number_format($product['price_sale']) : number_format($product['price']);
            $products_html[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $price . ' VNĐ',
                'image' => base_url('public/images/products/' . $product['avatar']),
                'url' => base_url($product['alias']),
                'alias' => $product['alias']
            ];
        }

        return [
            'message' => 'Tôi gợi ý cho bạn một số sản phẩm nổi bật hiện tại:',
            'intent' => $intent,
            'msg_type' => 'product_list',
            'products' => $products_html
        ];
    }

    /**
     * Chuẩn hóa chuỗi (loại bỏ dấu, ký tự đặc biệt) để so khớp alias
     */
    private function normalize_text($text) {
        $text = trim(mb_strtolower($text, 'UTF-8'));
        $text = $this->remove_accents($text);
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    private function slugify($text) {
        $text = $this->normalize_text($text);
        $text = str_replace(' ', '-', $text);
        return $text;
    }

    private function remove_accents($str) {
        $unicode = [
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ'
        ];
        foreach($unicode as $nonAccent=>$accent){
            $str = preg_replace("/($accent)/u", $nonAccent, $str);
            $str = preg_replace("/".mb_strtoupper($accent)."/u", strtoupper($nonAccent), $str);
        }
        return $str;
    }

    /**
     * Trả về thông tin giá của một sản phẩm cụ thể
     */
    private function get_product_price_response($keywords) {
        $this->load->model('frontend/Mproduct');
        $normalized = $this->normalize_text($keywords);

        $this->db->where('status', 1);
        $this->db->where('trash', 1);
        $this->db->group_start();
        $this->db->like('name', $keywords);
        $keyword_parts = preg_split('/\s+/', $keywords);
        if(is_array($keyword_parts)) {
            foreach($keyword_parts as $part) {
                $part = trim($part);
                if(strlen($part) > 1) {
                    $this->db->or_like('name', $part);
                }
            }
        }
        if(!empty($normalized)) {
            $this->db->or_like('alias', $this->slugify($normalized));
        }
        $this->db->group_end();
        $this->db->order_by('price', 'ASC');
        $this->db->limit(1);

        $query = $this->db->get('db_product');
        $product = $query->row_array();

        if(!$product) {
            return null;
        }

        $display_price = $product['price_sale'] > 0 ? $product['price_sale'] : $product['price'];
        $price_text = number_format($display_price) . ' VNĐ';
        $message = $product['name'] . ' hiện có giá ' . $price_text . '.';
        if($product['price_sale'] > 0 && $product['price_sale'] < $product['price']) {
            $message .= ' (Giá gốc ' . number_format($product['price']) . ' VNĐ)';
        }

        return [
            'message' => $message,
            'intent' => 'price_single',
            'msg_type' => 'product_list',
            'products' => [[
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $price_text,
                'original_price' => ($product['price_sale'] > 0 && $product['price_sale'] < $product['price']) ? number_format($product['price']) . ' VNĐ' : '',
                'image' => base_url('public/images/products/' . $product['avatar']),
                'url' => base_url($product['alias']),
                'alias' => $product['alias']
            ]]
        ];
    }
    
    /**
     * Lấy lịch sử chat
     */
    public function get_history() {
        // Clean any previous output
        if (ob_get_length()) ob_clean();
        
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        
        try {
            $user_id = 0;
            if($this->session->userdata('sessionKhachHang')) {
                $user = $this->session->userdata('sessionKhachHang');
                $user_id = isset($user['id']) ? $user['id'] : 0;
            }
            
            $history = $this->Mchatbox->chatbox_get_history($user_id, 50);
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'history' => $history
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } catch(Exception $e) {
            log_message('error', 'Chatbox get_history error: ' . $e->getMessage());
            http_response_code(200);
            echo json_encode([
                'success' => false,
                'history' => [],
                'error' => 'Có lỗi xảy ra khi tải lịch sử.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}

