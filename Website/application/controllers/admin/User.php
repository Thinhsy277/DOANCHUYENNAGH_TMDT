<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('backend/Muser');
		$this->load->model('backend/Morders');
	}

	public function login()
	{
		// Nếu đã đăng nhập, redirect về dashboard
		if($this->session->userdata('sessionadmin'))
		{
			redirect('admin','refresh');
		}
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Tên đăng nhập', 'required|min_length[5]|max_length[32]');
		$this->form_validation->set_rules('password', 'Mật khẩu', 'required|min_length[5]|max_length[32]');
        if ($this->form_validation->run() ==TRUE)
        {
        	$username = trim($this->input->post('username'));
        	$password_input = $this->input->post('password');
        	$password = sha1($password_input);
        	
        	// Debug: Log thông tin đăng nhập (chỉ trong development)
        	if(ENVIRONMENT === 'development') {
        		log_message('debug', 'Login attempt - Username: ' . $username . ', Password hash: ' . $password);
        	}
        	
        	// Kiểm tra xem username có tồn tại không
        	$this->db->where('username', $username);
        	$user_check = $this->db->get($this->db->dbprefix('user'))->row_array();
        	
        	if(empty($user_check)) {
        		$data['error'] = 'Tên đăng nhập không tồn tại.';
        		$this->load->view('backend/components/user/login', $data);
        		return;
        	}
        	
        	// Debug: Log thông tin user trong database
        	if(ENVIRONMENT === 'development') {
        		log_message('debug', 'User found - Status: ' . $user_check['status'] . ', Trash: ' . $user_check['trash'] . ', Password in DB: ' . $user_check['password']);
        	}
        	
        	$row = $this->Muser->user_login($username, $password);
        	
        	if($row != FALSE && !empty($row))
        	{
        		// Kiểm tra thêm status và trash
        		if(isset($row['status']) && $row['status'] == 1 && isset($row['trash']) && $row['trash'] == 1)
        		{
        			$this->session->set_userdata('sessionadmin', $row);
        			$this->session->set_userdata('id', $row['id']);
        			redirect('admin','refresh');
        		}
        		else
        		{
        			$status_msg = isset($row['status']) ? ($row['status'] == 0 ? 'bị khóa' : 'active') : 'unknown';
        			$trash_msg = isset($row['trash']) ? ($row['trash'] == 0 ? 'đã bị xóa' : 'chưa bị xóa') : 'unknown';
        			$data['error'] = 'Tài khoản của bạn ' . $status_msg . ' hoặc ' . $trash_msg . '. Vui lòng liên hệ quản trị viên.';
        			$this->load->view('backend/components/user/login', $data);
        		}
        	}
        	else
	        {
	        	// Kiểm tra chi tiết hơn để đưa ra thông báo chính xác
	        	if(!empty($user_check)) {
	        		if($user_check['status'] == 0) {
	        			$data['error'] = 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.';
	        		} elseif($user_check['trash'] == 0) {
	        			$data['error'] = 'Tài khoản của bạn đã bị xóa. Vui lòng liên hệ quản trị viên.';
	        		} elseif($user_check['password'] != $password) {
	        			$data['error'] = 'Mật khẩu không chính xác. Vui lòng kiểm tra lại.';
	        		} else {
	        			$data['error'] = 'Thông tin đăng nhập không chính xác. Vui lòng kiểm tra lại username và password.';
	        		}
	        	} else {
	        		$data['error'] = 'Thông tin đăng nhập không chính xác. Vui lòng kiểm tra lại username và password.';
	        	}
	        	$this->load->view('backend/components/user/login', $data);
	        }
        }
        else
        {
        	$this->load->view('backend/components/user/login');
        }
	}

	public function logout()
	{
		$array_items = array('sessionadmin', 'id');
        $this->session->unset_userdata($array_items);
		redirect('admin','refresh');
	}

}

/* End of file User.php */
/* Location: ./application/controllers/User.php */