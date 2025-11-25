<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mchatbox extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->table = $this->db->dbprefix('chatbox');
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

