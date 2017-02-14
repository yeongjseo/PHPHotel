<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		if ($peak = $this->config->item('peak_page_cache')) {
			if ($peak == uri_string()) {
				log_message('debug', "caching ".uri_string());
				$this->output->cache(5);
			}
		}
		
		$this->load->database();
		
	}
	
	function _load_view($page, $options = NULL) {
		$this->load->view('main/main_menu');
		$this->load->view($page, $options);
		$this->load->view('main/main_footer');
	}
	
	function _require_login() {
		$return_url = $_SERVER['REQUEST_URI'];
		
		if (! $this->session->userdata('user')) {
			redirect('/member/login?return_url='.rawurlencode($return_url));
		}
	}
	
	function _redirect() {
		redirect('/main');
	}

	function _require_admin() {
		$user = $this->session->userdata('user');
		
		if (! $user || $user->ID != 1) {
			redirect('/errors/auth');
		}
	}
	
	function _ispost() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
			return true;
		return false;
	}
	
	function _isget() {
		return ! $this->_ispost();
	}
	
	function _load_library_upload() {
		$config['upload_path'] = './static/user';
		//$config['allowed_types'] = 'gif|jpg|png';
		$config['allowed_types'] = '*|gif|jpg|jpeg|png';
		$config['max_size'] = '100000'; // KB
		//$config['max_width']  = '1024';
		//$config['max_height']  = '768';
		$this->load->library('upload', $config);
	}
	
	function _upload_board_file() {
		$this->_load_library_upload();
		$i = 0;
		$files = array();
		while ($i < 100) {
			$name = "file_{$i}";
			
			if (! isset($_FILES[$name])) {
				$i++;
				continue;
			}
			
			if (! $this->upload->do_upload($name)) {
				return null;				
			}
					
			$data = $this->upload->data();
			$file = array('filename'=>$data['orig_name'], 
							'saved_filename'=>$data['file_name'],
							'filesize'=>($data['file_size'] * 1000));
			
			//array_push($files, $file);
			$files[$name] = $file;
			$i++;
		}
		return $files;
	}
	
	function _set_member_form_rules() {
		$this->load->library('form_validation');
	
		$this->form_validation->set_rules('ACCOUNT', '아이디', 'required|min_length[4]|max_length[20]');
		$this->form_validation->set_rules('PASSWORD', '비밀번호', 'required|min_length[4]|max_length[20]');
		$this->form_validation->set_rules('NICKNAME', '별명', 'required|min_length[4]|max_length[20]');
		//$this->form_validation->set_rules('BIRTHDAY', '생일', 'required|min_length[4]|max_length[20]');
		$this->form_validation->set_rules('BIRTH_YEAR', '생년', 'required|exact_length[4]');
		$this->form_validation->set_rules('BIRTH_MONTH', '생월', 'required|min_length[1]|max_length[2]');
		$this->form_validation->set_rules('BIRTH_DAY', '생일', 'required|min_length[1]|max_length[2]');
		$this->form_validation->set_rules('ADDRESS1', '주소', 'required|min_length[2]|max_length[200]');
		$this->form_validation->set_rules('ADDRESS2', '상세주소', 'required|min_length[2]|max_length[200]');
		$this->form_validation->set_rules('EMAIL', '이메일', 'required|min_length[4]|max_length[80]');
		//$this->form_validation->set_rules('EMAIL_CONFIRM', '이메일 정보수신', 'required|min_length[4]|max_length[80]');
	}
	
	function _get_form_error($arr) {
		foreach ($arr as $key=>$value) {
			if ($this->form_validation->error($key))
				return $this->form_validation->error($key);
		}
		return null;
	}
	
	

}