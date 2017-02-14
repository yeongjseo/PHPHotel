<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class Member extends MY_Controller {
	function __construct() {
		parent::__construct();
	
		$this->load->model('member_model');
		$this->load->model('room_model');
		$this->load->model('reserve_model');
	}
	
	function login() {
		if ($this->_isget()) {
			$options = array('return_url'=>$this->input->get('return_url'));
			$this->_load_view('member/member_login', $options);
			return;
		}

		// without content-type, json is considered as text.
		header("Content-Type: application/json", true);
		
		$account = $this->input->post('account');
		$password = $this->input->post('password');
		$return_url = $this->input->get('return_url');
		log_message('debug', 'account='.$account.', password='.$password);
		
		$member = $this->member_model->get_member_by_account(array('ACCOUNT'=>$account));
		if (! $member) {
			echo json_encode(array('result'=>'fail','reason'=>'아이디가 없습니다.'));
			return;
		}
		
		if ($member->PASSWORD != $password) {
			echo json_encode(array('result'=>'fail','reason'=>'비밀번호가 틀립니다.'));
			return;
		}

		$this->session->set_userdata('user', $member);
		
		echo json_encode(array("result"=>"success"));
	}
	
	function logout() {
		$this->session->sess_destroy();
		$this->_redirect();
	
	}
	
	function join() {
		$this->_set_member_form_rules();
		
		if ($this->form_validation->run() === false) {
			if (($error = $this->_get_form_error($_POST)) != null)
				$this->session->set_flashdata('message', array('result'=>'error', 'reason'=>$error));
			
			$this->_load_view('/member/member_join');
			return;
		}
		
		$arr = $_POST;
		$arr['BIRTHDAY'] = "{$_POST['BIRTH_YEAR']}/{$_POST['BIRTH_MONTH']}/{$_POST['BIRTH_DAY']}";
		$arr['EMAIL_CONFIRM'] = 0;

		$member = $this->member_model->get_member_by_account(array('ACCOUNT'=>$arr['ACCOUNT']));
		if ($member) {
			$this->session->set_flashdata('message', array('result'=>'error',
					'reason'=>'아이디가 중복됩니다.'));
			$this->_load_view('/member/member_join');
			return;
		}
		
		
		$this->member_model->insert_member($arr);
		redirect("/main");
	}
	
	
	function detail() {
		$user = $this->session->userdata('user');

		list($BIRTH_YEAR, $BIRTH_MONTH, $BIRTH_DAY) = explode("/", $user->BIRTHDAY);
		$user->BIRTH_YEAR = $BIRTH_YEAR; 
		$user->BIRTH_MONTH = $BIRTH_MONTH;
		$user->BIRTH_DAY = $BIRTH_DAY;
		
		$this->_set_member_form_rules();
		
		if ($this->_isget()) {
			$user_array = (array) $user;
			$this->form_validation->run();
			$this->_load_view('member/member_detail', array('member'=>$user_array));
			return;
		}
		
		if ($this->form_validation->run() === false) {
			if (($error = $this->_get_form_error($_POST)) != null)
				$this->session->set_flashdata('message', array('result'=>'error', 'reason'=>$error));
			
			$this->_load_view('member/member_detail');
			return;
		}
			
		$arr = $_POST;
		$arr['ID'] = $user->ID;
		$arr['BIRTHDAY'] = "{$_POST['BIRTH_YEAR']}/{$_POST['BIRTH_MONTH']}/{$_POST['BIRTH_DAY']}";
		if (! array_key_exists('EMAIL_CONFIRM', $arr))
			$arr['EMAIL_CONFIRM'] = 0; 
		
		$this->member_model->update_member($arr);
		
		$this->session->set_userdata('user', (object)$arr);
		
		redirect("/main");
		
	}
	
	function confirm_password() {
		// without content-type, json is considered as text.
		header("Content-Type: application/json", true);
	
		$account = $this->input->post('account');
		$password = $this->input->post('password');

		log_message('debug', 'account='.$account.', password='.$password);
	
		$user = $this->session->userdata('user');
		if (! $user) {
			echo json_encode(array('result'=>'fail','reason'=>'로그인하지 않았습니다.'));
			return;
		}
	
		if ($user->PASSWORD != $password) {
			echo json_encode(array('result'=>'fail','reason'=>'비밀번호가 틀립니다.'));
			return;
		}
		echo json_encode(array("result"=>"success"));
	}
	
	function delete() {
		$user = $this->session->userdata('user');
		
		$this->member_model->delete_member(array('ID'=>$user->ID));

		$this->session->sess_destroy();
		
		redirect("/main");
	}
	
	function reserve_detail() {
		$user = $this->session->userdata('user');
		
		$reserves = $this->reserve_model->get_reserves_by_member_id(array('user_id'=>$user->ID));
		
		foreach ($reserves as $reserve) {
			$reserve->ROOM_TYPE_DESC = $this->room_model->_get_desc_from_type($reserve->TYPE); 
		}
		
		
		$this->_load_view('member/member_reserve_detail', array('reserves'=>$reserves));
		
	}


}
?>

