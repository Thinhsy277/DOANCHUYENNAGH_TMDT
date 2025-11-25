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
}

