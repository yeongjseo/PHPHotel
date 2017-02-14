<?php
class Reserve_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}
	
	/*
	 * Room Type
	 */
	function count_reserve($arr) {
		$search_key = $arr['paging']->search_key;
		$search_val = $arr['paging']->search_val;
		if ($search_key != '')
			$search = " WHERE {$search_key} LIKE '%{$search_val}%' ";
		else
			$search = '';
		
		$sql = "SELECT COUNT(*) CNT FROM {$this->_table_room_type} $search";
		log_debug($sql);
		$result = $this->db->query($sql)->row();
		return $result->CNT;
	}
	
	function list_room_type($args) {

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

	function _get_date_condition($arr) {
		return "({$this->_to_date($arr['date_start'])} >= DATE_START AND {$this->_to_date($arr['date_start'])} <  DATE_END) OR
				({$this->_to_date($arr['date_start'])} <= DATE_START AND {$this->_to_date($arr['date_end'])}   >= DATE_END) OR
				({$this->_to_date($arr['date_end'])}   >  DATE_START AND {$this->_to_date($arr['date_end'])}   <= DATE_END)";
	}
	
	function __get_date_condition($search) {
		return "(({$this->_to_date($search->date_start)} >= DATE_START AND 
				 {$this->_to_date($search->date_start)} <  DATE_END) 
				 OR
				({$this->_to_date($search->date_start)} <= DATE_START AND 
				 {$this->_to_date($search->date_end)} >= DATE_END) 
				 OR
				({$this->_to_date($search->date_end)} >  DATE_START AND 
				 {$this->_to_date($search->date_end)} <= DATE_END))";
	}
	
	
	
	function get_vacant_room_types($arr) {
		$sql = "SELECT *  FROM {$this->_table_room_type} WHERE TYPE IN ( 
				SELECT DISTINCT(ROOM_TYPE_ID) FROM ( 
					SELECT * FROM {$this->_table_room} WHERE ID NOT IN ( 
						SELECT ROOM_ID FROM {$this->_table_reserve} WHERE {$this->__get_date_condition($arr['search'])}
					) 
				) 
			) ORDER BY TYPE";
		
		log_debug($sql);
		return $this->db->query($sql)->result();
		
	}
	
	function get_any_vacant_room($arr) {
		$sql = "SELECT * FROM {$this->_table_room} WHERE ID NOT IN (
					SELECT ROOM_ID FROM {$this->_table_reserve} WHERE {$this->_get_date_condition($arr)}
				) AND ROOM_TYPE_ID={$arr['room_type_id']} ORDER BY ROOM_NUM";

		log_debug($sql);
		return $this->db->query($sql)->row();
	
	}
	
	function get_next_reserve_id() {
		$sql = "SELECT {$this->_seq_reserve} AS NEXTVAL FROM DUAL";
		$result = $this->db->query($sql)->row();
		return $result->NEXTVAL;

	}
	
	function insert_reserve_by_next_id($arr) {
		$sql = "INSERT INTO {$this->_table_reserve} VALUES ({$arr['id']},
					{$arr['user_id']}, {$arr['room_id']}, 
					{$this->_to_date($arr['date_start'])}, {$this->_to_date($arr['date_end'])}, 
					SYSDATE, {$arr['pax']}, {$arr['breakfast']})";
		log_debug($sql);
		$this->db->query($sql);
		
	}
	
	function delete_reserve($arr) {
		$sql = "DELETE FROM {$this->_table_reserve} WHERE ID={$arr['id']}";
		log_message('debug', $sql);
		
		$this->db->query($sql);
	}
	
	function get_reserves_by_member_id($arr) {
		$sql = "SELECT RE.ID, RE.ROOM_ID, 
					{$this->_to_char_date('RE.DATE_START', 'DATE_START')}, 
					{$this->_to_char_date('RE.DATE_END', 'DATE_END')},
					{$this->_to_char_date_time('RE.DATE_RESERVE', 'DATE_RESERVE')},
					U.ACCOUNT, R.ROOM_TYPE_ID, R.ROOM_NUM, RT.TYPE FROM (
						SELECT * FROM {$this->_table_reserve} WHERE USER_ID = {$arr['user_id']}
					) RE, {$this->_table_user} U, {$this->_table_room} R, {$this->_table_room_type} RT
				WHERE RE.USER_ID = U.ID AND RE.ROOM_ID = R.ID AND R.ROOM_TYPE_ID = RT.ID ORDER BY ID";
		
		log_message('debug', $sql);
		return $this->db->query($sql)->result();
		
	}
	
	function get_reserves_by_room_date($arr) {
		$sql = "SELECT ID, USER_ID, ROOM_ID, PAX, BREAKFAST,
					{$this->_to_char_date('DATE_START', 'DATE_START')}, 
					{$this->_to_char_date('DATE_END', 'DATE_END')},
					{$this->_to_char_date_time('DATE_RESERVE', 'DATE_RESERVE')}
					FROM {$this->_table_reserve} WHERE 
					ROOM_ID={$arr['room_id']} AND {$this->__get_date_condition($arr['search'])}";

		log_message('debug', $sql);
		return $this->db->query($sql)->result();

	}
	
	function get_reserve_by_id($arr) {
		$sql = "SELECT RE.ID, RE.USER_ID, U.ACCOUNT, R.ROOM_NUM, RE.PAX, RT.TYPE,
					{$this->_to_char_date('DATE_START', 'DATE_START')}, 
					{$this->_to_char_date('DATE_END', 'DATE_END')},
					{$this->_to_char_date_time('DATE_RESERVE', 'DATE_RESERVE')}
						FROM {$this->_table_reserve} RE, {$this->_table_user} U, 
							{$this->_table_room_type} RT, {$this->_table_room} R
						WHERE RE.USER_ID=U.ID AND RE.ROOM_ID=R.ID AND 
							R.ROOM_TYPE_ID=RT.ID AND RE.ID={$arr['id']}";
		log_message('debug', $sql);
		return $this->db->query($sql)->row();
		
	}
	
	function get_reserves($arr) {
		$row_start = $arr['paging']->row_start;
		$row_end = $arr['paging']->row_end;
		$search_key = $arr['paging']->search_key;
		$search_val = $arr['paging']->search_val;
		if ($search_key != '' && $search_key != 'ACCOUNT')
			$search = " AND {$search_key} LIKE '%{$search_val}%' ";
		else
			$search = '';
		
		if ($search_key != '' && $search_key == 'ACCOUNT')
			$search2 = " AND {$search_key} LIKE '%{$search_val}%' ";
		else
			$search2 = '';
		
		
		$sql = "SELECT * FROM (
					SELECT ROWNUM RN, RE.ID, RE.USER_ID, U.ACCOUNT, R.ROOM_NUM, RE.PAX, RT.TYPE,
						{$this->_to_char_date('DATE_START', 'DATE_START')},
						{$this->_to_char_date('DATE_END', 'DATE_END')},
						{$this->_to_char_date_time('DATE_RESERVE', 'DATE_RESERVE')}
							FROM {$this->_table_reserve} RE, {$this->_table_user} U,
								{$this->_table_room_type} RT, {$this->_table_room} R
							WHERE RE.USER_ID=U.ID AND RE.ROOM_ID=R.ID AND
								R.ROOM_TYPE_ID=RT.ID {$search}
				) WHERE RN BETWEEN {$row_start} AND {$row_end}";
		log_message('debug', $sql);
		return $this->db->query($sql)->result();
	}
	
	
}