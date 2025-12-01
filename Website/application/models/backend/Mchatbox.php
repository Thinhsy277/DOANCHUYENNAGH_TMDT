<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mchatbox extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->table = $this->db->dbprefix('chatbox');
		$this->customer_table = $this->db->dbprefix('customer');
	}

	// Lưu tin nhắn vào database
	public function chatbox_insert($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	// Lấy lịch sử chat của user
	public function chatbox_get_history($user_id = 0, $limit = 50)
	{
		$this->db->where('user_id', $user_id);
		$this->db->order_by('created_at', 'ASC');
		$this->db->limit($limit);
		$query = $this->db->get($this->table);
		return $query->result_array();
	}

	// Lấy tin nhắn mới nhất
	public function chatbox_get_latest($user_id = 0, $limit = 10)
	{
		$this->db->where('user_id', $user_id);
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit($limit);
		$query = $this->db->get($this->table);
		return $query->result_array();
	}

	// Lấy danh sách user đã tương tác cùng số liệu thống kê
	public function get_conversation_users($limit = 100)
	{
		$this->db->select("{$this->table}.user_id,
			COUNT({$this->table}.id) AS total_messages,
			SUM(CASE WHEN {$this->table}.sender = 'user' THEN 1 ELSE 0 END) AS user_messages,
			SUM(CASE WHEN {$this->table}.sender = 'bot' THEN 1 ELSE 0 END) AS bot_messages,
			MAX({$this->table}.created_at) AS last_message_at,
			MAX(CASE WHEN {$this->table}.sender = 'user' THEN {$this->table}.message ELSE NULL END) AS last_user_message,
			c.fullname,
			c.email,
			c.phone");
		$this->db->from($this->table);
		$this->db->join("{$this->customer_table} AS c", "c.id = {$this->table}.user_id", 'left');
		$this->db->group_by("{$this->table}.user_id");
		$this->db->order_by('last_message_at', 'DESC');
		$this->db->limit($limit);
		$query = $this->db->get();
		return $query->result_array();
	}

	// Tìm kiếm lịch sử chat theo bộ lọc
	public function search_history($filters = [], $limit = 200)
	{
		$filters = is_array($filters) ? $filters : [];
		$this->db->select("{$this->table}.*, c.fullname, c.email, c.phone");
		$this->db->from($this->table);
		$this->db->join("{$this->customer_table} AS c", "c.id = {$this->table}.user_id", 'left');

		if(isset($filters['user_id']) && $filters['user_id'] !== '' && $filters['user_id'] !== null){
			$this->db->where("{$this->table}.user_id", (int)$filters['user_id']);
		}

		if(!empty($filters['sender'])){
			$this->db->where("{$this->table}.sender", $filters['sender']);
		}

		if(!empty($filters['keyword'])){
			$this->db->like("{$this->table}.message", trim($filters['keyword']));
		}

		$this->db->order_by("{$this->table}.created_at", 'DESC');
		$this->db->limit($limit);
		$query = $this->db->get();
		$rows = $query->result_array();

		// Đảo ngược để hiển thị theo thứ tự thời gian tăng dần
		return array_reverse($rows);
	}

	// Đếm số tin nhắn chưa đọc (cho admin)
	public function chatbox_unread_count()
	{
		$this->db->where('sender', 'user');
		$this->db->where('is_bot_reply', 0);
		$query = $this->db->get($this->table);
		return $query->num_rows();
	}

	// Lấy tất cả tin nhắn (cho admin)
	public function chatbox_get_all($limit = 100, $offset = 0)
	{
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit($limit, $offset);
		$query = $this->db->get($this->table);
		return $query->result_array();
	}

	// Xóa tin nhắn
	public function chatbox_delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->table);
		return $this->db->affected_rows();
	}

	// Lấy tin nhắn theo ID
	public function chatbox_detail($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get($this->table);
		return $query->row_array();
	}
}

