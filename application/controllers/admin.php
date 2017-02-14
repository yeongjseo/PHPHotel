<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class Admin extends MY_Controller {
	function __construct() {
		parent::__construct();
	
		$this->load->model('member_model');
		$this->load->model('room_model');
		$this->load->model('reserve_model');
		
		$this->load->library('paging');
		$this->load->library('search');
	}
	
	function member_list() {
		
		$this->_require_admin();
		
		$this->paging->init($this->input);
		
		$arr = array('paging'=>$this->paging);
		
		$this->paging->row_count = $this->member_model->count_member($arr);
		$members = $this->member_model->get_members($arr);

		$this->paging->calculate();
		
		$arr = array('paging'=>$this->paging, 'members'=>$members);
		
		$this->_load_view('/admin/admin_member_list', $arr);
		
	}
	
	function member_detail() {
		$this->_require_admin();
		
		$this->paging->init($this->input);
		
		if ($this->_isget()) {
			$member = $this->member_model->get_member_by_id(array('ID'=>$this->input->get_post('id')));
			
			list($BIRTH_YEAR, $BIRTH_MONTH, $BIRTH_DAY) = explode("/", $member->BIRTHDAY);
			$member->BIRTH_YEAR = $BIRTH_YEAR;
			$member->BIRTH_MONTH = $BIRTH_MONTH;
			$member->BIRTH_DAY = $BIRTH_DAY;
		
			$this->_set_member_form_rules();
		
		
			$member_array = (array) $member;
			$this->form_validation->run();
			
			$arr = array('paging'=>$this->paging, 'member'=>$member_array);
			$this->_load_view('admin/admin_member_detail', $arr);
			return;
		}
		
		/* POST */
		$this->_set_member_form_rules();
		if ($this->form_validation->run() === false) {
			if (($error = $this->_get_form_error($_POST)) != null)
				$this->session->set_flashdata('message', array('result'=>'error', 'reason'=>$error));
					
				$this->_load_view('admin/admin_member_detail');
				return;
		}
			
		$arr = $_POST;
		//$arr['ID'] = $member->ID;
		$arr['BIRTHDAY'] = "{$_POST['BIRTH_YEAR']}/{$_POST['BIRTH_MONTH']}/{$_POST['BIRTH_DAY']}";
		if (! array_key_exists('EMAIL_CONFIRM', $arr))
			$arr['EMAIL_CONFIRM'] = 0;
	
		$this->member_model->update_member($arr);

		redirect("/admin/member_list?{$this->paging->make_query()}");
	
	}
	
	function member_delete() {
		
		$this->_require_admin();
		
		$this->paging->init($this->input);
		
		$this->member_model->delete_member(array('ID'=>$this->input->post('ID')));
		
		redirect("/admin/member_list?{$this->paging->make_query()}");
	}

	function room_list() {
		$this->_require_admin();
		
		$this->paging->init($this->input);
	
		$arr = array('paging'=>$this->paging);
	
		$this->paging->row_count = $this->room_model->count_room($arr);
		$rooms = $this->room_model->get_rooms($arr);
	
		foreach ($rooms as $room) {
			$room->TYPE_DESC = $this->room_model->_get_desc_from_type($room->ROOM_TYPE_ID);
		}
		
		$this->paging->calculate();
	
		$arr = array('paging'=>$this->paging, 'rooms'=>$rooms);
	
		$this->_load_view('/admin/admin_room_list', $arr);
	
	}

	function reserve_list() {
		$this->_require_admin();
		
		$this->paging->init($this->input);
		
		$arr = array('paging'=>$this->paging);
		
		$this->paging->row_count = $this->reserve_model->count_reserve($arr);
		$reserves = $this->reserve_model->get_reserves($arr);
		
		foreach ($reserves as $reserve) {
			$reserve->TYPE_DESC = $this->room_model->_get_desc_from_type($reserve->TYPE);
		}
		
		$this->paging->calculate();
		
		$arr = array('paging'=>$this->paging, 'reserves'=>$reserves);
		
		$this->_load_view('/admin/admin_reserve_list', $arr);
		
	
	}
	
	function reserve_calendar() {
		$this->_require_admin();
		
		$this->paging->init($this->input);
		$this->paging->set_rowmax(100);
		
		$this->search->init($this->input);
		
		$arr = array('paging'=>$this->paging);
		$this->paging->row_count = $this->room_model->count_room($arr);
		$rooms = $this->room_model->get_rooms($arr);
		
		foreach ($rooms as $room) {
			$arr = array('room_id'=>$room->ID, 'search'=>$this->search);
			$room->TYPE_DESC = $this->room_model->_get_desc_from_type($room->ROOM_TYPE_ID);
			$room->reserves = $this->reserve_model->get_reserves_by_room_date($arr); 
		}
		
		$arr = array('search'=>$this->search, 'rooms'=>$rooms);
		$this->_load_view('/admin/admin_reserve_calendar', $arr);
	}

	function reserve_detail() {
		$this->_require_admin();
		
		$reserve_id = $this->input->post_get('reserve_id');
	
		$return_url = $this->input->get('return_url');
		
		$room = $this->reserve_model->get_reserve_by_id(array('id'=>$reserve_id));
		$room->TYPE_DESC = $this->room_model->_get_desc_from_type($room->TYPE);
	
		$arr = array('room'=>$room, 'return_url'=>$return_url);
		$this->_load_view('/admin/admin_reserve_detail', $arr);
	}
	
	

}
?>
