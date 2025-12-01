<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chatbox extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('backend/Mchatbox');
		$this->load->model('backend/Mcustomer');
		$this->load->model('backend/Morders');
		if(!$this->session->userdata('sessionadmin')){
			redirect('admin/user/login','refresh');
		}
		$this->data['user']=$this->session->userdata('sessionadmin');
		$this->data['com']='chatbox';
	}

	public function index(){
		$filters = [
			'user_id' => $this->input->get('user_id', TRUE),
			'sender' => $this->input->get('sender', TRUE),
			'keyword' => $this->input->get('keyword', TRUE)
		];

		if($filters['user_id'] === ''){
			$filters['user_id'] = null;
		}

		$this->data['conversationUsers'] = $this->Mchatbox->get_conversation_users(150);
		$this->data['history'] = $this->Mchatbox->search_history($filters, 200);
		$this->data['filters'] = $filters;
		$this->data['title']='Lịch sử chatbot';
		$this->data['view']='index';
		$this->load->view('backend/layout', $this->data);
	}
}


