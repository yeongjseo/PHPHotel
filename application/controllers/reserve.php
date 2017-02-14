<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class Reserve extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('room_model');
		$this->load->model('reserve_model');
		$this->load->library('paging');
		$this->load->library('search');
	}
	
	function get_room_types() {

		$this->paging->init($this->input);
		
		$arr = array('paging'=>$this->paging);
		
		$this->paging->row_count = $this->room_model->count_room_type($arr);
		$room_types = $this->room_model->list_room_type($arr);
		foreach ($room_types as $room_type) {
			$room_type->TYPE_DESC = $this->room_model->_get_desc_from_type($room_type->TYPE);
				
			$arr = array('room_type_id'=>$room_type->ID);
			$room_type->files = $this->room_model->list_room_file($arr);
		}
		
		$this->paging->calculate();
		
		return array('paging'=>$this->paging, 'room_types'=>$room_types);
	}
	
	function search() {
	
		if (isset($_GET['date_start']) === false || isset($_GET['date_end']) === false) {
			$arr = $this->get_room_types();
			/*
			$arr['date_start'] = '';
			$arr['date_end'] = '';
			$arr['date_count'] = '';
			$arr['guest_num'] = '5';
			*/
			$this->search->init($this->input);
			$arr['search'] = $this->search; 
			$arr['vacant_room_types'] = '';
			$this->_load_view('/reserve/reserve_search', $arr);
			
			return;
		}
		
		$arr = $_GET;
		$this->search->init($this->input);
		$arr['search'] = $this->search;
		$room_types = $this->reserve_model->get_vacant_room_types($arr);

		foreach($room_types as $room_type) {
			$room_type->TYPE_DESC = $this->room_model->_get_desc_from_type($room_type->TYPE);
				
			$arr['room_type_id'] = $room_type->ID;
			$room_type->files = $this->room_model->list_room_file($arr);
		}
		
		$arr['vacant_room_types'] = $room_types; 		
		$this->_load_view('/reserve/reserve_search', $arr);
	}
	
	function insert() {
		header("Content-Type: application/json", true);
		
		/* json_body:{"date_start":"2017/02/11","date_end":"2017/02/21",
		 * 			  "date_count":"10","guest_num":"5","room_type_id":"1"} */
		$json_body = $this->input->post('json_body');
		$obj = json_decode($json_body);
		$arr = (array)$obj;
		$room = $this->reserve_model->get_any_vacant_room($arr);
		
		if ($room == null) {
			echo json_encode(array('result'=>'fail', 
									'reason'=>'해당 룸이 모두 예약되었습니다.'));
			return;
		}
		
		$reserve_id = $this->reserve_model->get_next_reserve_id();
		
		$user = $this->session->userdata('user');
		$arr['id'] = $reserve_id;
		$arr['room_id'] = $room->ID;
		$arr['user_id'] = $user->ID;
		$arr['pax'] = 0;
		$arr['breakfast'] = 0;
		
		$this->reserve_model->insert_reserve_by_next_id($arr);
		
		$room_type_desc = $this->room_model->_get_desc_from_type($room->ROOM_TYPE_ID);
		echo json_encode(array('result'=>'success', 'reserve_id'=>$reserve_id, 
								'room_type_desc'=>$room_type_desc));
		
	}
	
	
	
	function delete() {
		header("Content-Type: application/json", true);
		
		$json_body = $this->input->post('json_body');
		$obj = json_decode($json_body);
		$arr = (array)$obj;
		
		$this->reserve_model->delete_reserve($arr);

		echo json_encode(array('result'=>'success'));
		
	}
	
	
	
}

