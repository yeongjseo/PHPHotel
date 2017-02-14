<?php
class Room_model extends MY_Model {

	private $_type_descs = array('1'=>'표준', '2'=>'디럭스', '3'=>'트윈-디럭스', 
								'4'=>'슈피리어', '5'=>'럭셔리');
	
	function __construct() {
		parent::__construct();
	}
	
	function _get_type_from_desc($desc) {
		foreach ($this->_type_descs as $key=>$val) {
			if ($val == $desc)
				return $key;
		}
		log_message('error', 'desc='.$desc);
		assert(false);
	}
	
	function _get_desc_from_type($type) {
		return $this->_type_descs[$type];
	}
	
	
	
	/*
	 * Room Type
	 */
	function count_room_type($arr) {
		$sql = "SELECT COUNT(*) CNT FROM {$this->_table_room_type}";
		log_message('debug', $sql);
		$result = $this->db->query($sql)->row();
		return $result->CNT;
	}
	
	function list_room_type($args) {

		/*
		$row_start = $args['paging']->row_start;
		$row_end = $args['paging']->row_end;
		$search_key = $args['paging']->search_key;
		$search_val = $args['paging']->search_val;
		if ($search_key != '')
			$search = " and {$search_key} like '%{$search_val}%' ";
		else
			$search = '';
	
		$sql = "SELECT B.*, TO_CHAR (WRITE_DATE,  'yyyy/mm/dd hh24:mi:ss') AS WRITE_TIME, U.ACCOUNT FROM (
			SELECT * FROM (
				SELECT ROWNUM RN, T.* FROM (
					SELECT * FROM {$this->_table_board} WHERE TYPE = {$type}
						ORDER BY ID DESC
				) T
			) WHERE RN BETWEEN {$row_start} AND {$row_end} ORDER BY ID DESC
		) B, {$this->_table_user} U WHERE B.USER_ID = U.ID {$search}";
		log_message('debug', $sql);
		return $this->db->query($sql)->result();
		
		*/

		$this->db->select('*');
		$this->db->from("{$this->_table_room_type} rt");
		//$this->db->where('rt.bookid', $book->bookid);
		$this->db->order_by('ID', 'asc');
		return $this->db->get()->result();
		
	}
	
	function list_room_file($arr) {
		$this->db->select('*');
		$this->db->from("{$this->_table_room_file} rt");
		$this->db->where('ROOM_TYPE_ID', $arr['room_type_id']);

		return $this->db->get()->result();

	}

	
	function count_room($arr) {
		$search_key = $arr['paging']->search_key;
		$search_val = $arr['paging']->search_val;
		if ($search_key != '')
			$search = " WHERE {$search_key} LIKE '%{$search_val}%' ";
		else
			$search = '';
	
		$sql = "SELECT COUNT(*) CNT FROM {$this->_table_room} $search";
		log_message('debug', $sql);
		$result = $this->db->query($sql)->row();
		return $result->CNT;
	}
	
	/*
	function get_members($arr) {
	
		
	
		$sql = "SELECT U.*, TO_CHAR (BIRTHDAY,  'yyyy/mm/dd') AS BIRTHDAY FROM (
		SELECT ROWNUM RN, T.* FROM (
		SELECT * FROM {$this->_table_user} {$search}
		ORDER BY ID DESC
		) T
		) U WHERE RN BETWEEN {$arr['paging']->row_start} AND {$arr['paging']->row_end}";
	
		log_message('debug', $sql);
		return $this->db->query($sql)->result();

	}
	*/
	
	function get_rooms($arr) {
		if ($arr['paging']->search_key != '')
			$search = "AND {$arr['paging']->search_key} LIKE '%{$arr['paging']->search_val}%' ";
		else
			$search = '';
		
		$sql = "SELECT RT.* FROM (
					SELECT ROWNUM RN, R.ID, R.ROOM_TYPE_ID, R.ROOM_NUM, T.TYPE ROOM_TYPE, T.MAXPAX, T.COST 
						FROM PHOTEL_ROOM R, PHOTEL_ROOM_TYPE T 
						WHERE R.ROOM_TYPE_ID = T.ID {$search}
				) RT WHERE RN BETWEEN {$arr['paging']->row_start} AND {$arr['paging']->row_end}"; 
  
	
		log_message('debug', $sql);
		return $this->db->query($sql)->result();
	}
	

	
	
}