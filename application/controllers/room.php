<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class Room extends MY_Controller {
	function __construct() {
		parent::__construct();
	
		$this->load->model('room_model');

		$this->load->library('paging');
	}
	
	function show_room_type() {
		$this->load->model('room_model');
		$this->load->library('paging');
		
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
		
		$arr = array('paging'=>$this->paging, 'room_types'=>$room_types);
		
		$this->_load_view('/room/room_type_show', $arr);
	}
	
	
	
}

