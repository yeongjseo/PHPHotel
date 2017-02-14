<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class Company extends MY_Controller {
	function __construct() {
		parent::__construct();
	}

	function intro() {
		$this->_load_view('/company/company_intro');
	}
	
	function map() {
		$this->_load_view('/company/company_map');
	}
	
	function contact() {
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', '이름', 'required|min_length[2]|max_length[20]');
		$this->form_validation->set_rules('email', '이메일주소', 'required|valid_email');
		$this->form_validation->set_rules('tel', '전화번호', 'required|min_length[4]|max_length[20]');
		$this->form_validation->set_rules('title', '제목', 'required|min_length[4]|max_length[20]');
		$this->form_validation->set_rules('content', '내용', 'required|min_length[4]|max_length[20]');


		if ($this->_isget()) {
			$this->_load_view('company/company_contact');
			return;
		}

		header("Content-Type: application/json", true);
		
		if ($this->form_validation->run() === false) {
			if (($error = $this->_get_form_error($_POST)) != null)
				// $this->session->set_flashdata('message', array('result'=>'error', 'reason'=>$error));
				log_debug('error');
				echo json_encode(array('result'=>'fail', 'reason'=>$error));
				return;
		}
		log_debug('...');
		$arr = $_POST;
		
		$this->load->library('email');

		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'ssl://smtp.gmail.com';
		$config['smtp_port'] = '465';
		$config['smtp_timeout'] = '7';
		$config['smtp_user'] = 'suhlab.test@gmail.com';
		$config['smtp_pass'] = 'cizpxeqwmxglrqvw';
		$config['charset'] = 'utf-8';
		$config['newline'] = "\r\n";
		$config['mailtype'] = 'text'; // or html
		$config['validation'] = TRUE; // bool whether to validate email or not
		
		$this->email->initialize($config);

		$this->email->from($this->input->post('email'), $this->input->post('name'));
		$this->email->subject($this->input->post('title'));
		$this->email->message($this->input->post('content'));
		$this->email->to('suhlab.test@gmail.com');
		$this->email->send();
		
		echo json_encode(array('result'=>'success'));

	}
	
}
?>
