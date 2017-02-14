<?php
class Board_model extends MY_Model {

	private $_type_descs = array('1'=>'notice', '2'=>'event', '3'=>'guest');
	private $_type_long_descs = array('1'=>'게시판', '2'=>'이벤트', '3'=>'방명록');
	
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
		return $_type[$type];
	}
	
	function get_type_long_desc($desc) {
		
		$type = $this->_get_type_from_desc($desc); 				
		
		foreach ($this->_type_long_descs as $key=>$val) {
			if ($key == $type)
				return $val;
		}
		log_message('error', 'desc='.$desc);
		assert(false);
	}
	
	/*
	 * Board
	 */
	function count_board($args) {
		$type = $this->_get_type_from_desc($args['type']);
		$search_key = $args['paging']->search_key;
		$search_val = $args['paging']->search_val;
		if ($search_key != '')
			$search = " AND {$search_key} LIKE '%{$search_val}%' ";
		else
			$search = '';
		
		$sql = "SELECT COUNT(*) CNT FROM (
					SELECT * FROM {$this->_table_board} B, {$this->_table_user} U 
						WHERE TYPE={$type} AND B.USER_ID=U.ID {$search}
				)";
		
		log_message('debug', $sql);
		$result = $this->db->query($sql)->row();
		return $result->CNT;
	}
	
	function list_board($args) {
		
		$type = $this->_get_type_from_desc($args['type']);
		$row_start = $args['paging']->row_start;
		$row_end = $args['paging']->row_end;
		$search_key = $args['paging']->search_key;
		$search_val = $args['paging']->search_val;
		if ($search_key != '' && $search_key != 'ACCOUNT')
			$search = " AND {$search_key} LIKE '%{$search_val}%' ";
		else
			$search = '';
		
		if ($search_key != '' && $search_key == 'ACCOUNT')
			$search2 = " AND {$search_key} LIKE '%{$search_val}%' ";
		else
			$search2 = '';
			
		
		$sql = "SELECT B.*, TO_CHAR (WRITE_DATE,  'yyyy/mm/dd hh24:mi:ss') AS WRITE_TIME, U.ACCOUNT FROM (
					SELECT * FROM (
						SELECT ROWNUM RN, T.* FROM (
							SELECT * FROM {$this->_table_board} WHERE TYPE={$type} {$search} 
							ORDER BY ID DESC
							) T
						) WHERE RN BETWEEN {$row_start} AND {$row_end} ORDER BY ID DESC
				) B, {$this->_table_user} U WHERE B.USER_ID = U.ID {$search2}";
		
		log_message('debug', $sql);
		return $this->db->query($sql)->result();
	}

	function get_board($args) {
		$type = $this->_get_type_from_desc($args['type']);
		$id = $args['id'];
		
		$sql = "SELECT BU.*, TO_CHAR (WRITE_DATE,  'yyyy/mm/dd hh24:mi:ss') AS WRITE_TIME, 
						F.ID AS FILEID, F.BOARD_ID, F.FILENAME, F.SAVED_FILENAME, F.FILESIZE FROM (
					SELECT B.*, U.ACCOUNT FROM {$this->_table_board} B, {$this->_table_user} U
						WHERE B.TYPE = {$type} AND B.ID =  {$id} AND B.USER_ID = U.ID
				) BU LEFT OUTER JOIN {$this->_table_board_file} F ON BU.ID = F.BOARD_ID";
		
		log_message('debug', $sql);
		return $this->db->query($sql)->row();
	}

	function get_board_by_account($account) {
		$result = $this->db->get_where($this->_table_board, array('ACCOUNT'=>$account))->row();
		return $result;
	}
	
	function increase_board_read_count($args) {
		$type = $this->_get_type_from_desc($args['type']);
		$id = $args['id'];
		$sql = "update {$this->_table_board} set READ_COUNT=READ_COUNT+1 where ID={$id} and TYPE={$type}";
		log_message('debug', $sql);
		$this->db->query($sql);
	}
	
	function insert_board($args) {
		$type = $this->_get_type_from_desc($args['type']);
		$user_id = $args['user_id'];
		$title = $args['title'];
		$content = $args['content'];
		
		$sql = "INSERT INTO {$this->_table_board} VALUES ({$this->_seq_board}, 
						{$user_id}, {$type}, '{$title}', '{$content}', SYSDATE, 0)";

		log_message('debug', $sql);						
		$this->db->query($sql);

	}
	
	function insert_board_by_nextval($args) {
		$type = $this->_get_type_from_desc($args['type']);
		$id = $args['id'];
		$user_id = $args['user_id'];
		$title = $args['title'];
		$content = $args['content'];
	
		$sql = "INSERT INTO {$this->_table_board} VALUES ({$id}, 
			{$user_id}, {$type}, '{$title}', '{$content}', SYSDATE, 0)";
	
		log_message('debug', $sql);
		$this->db->query($sql);
	}

	function update_board($args) {
		$board = $args['board'];
		
		$sql = "UPDATE {$this->_table_board} SET TITLE='{$board->TITLE}',
					CONTENT='{$board->CONTENT}' WHERE ID={$board->ID}";
		
		log_message('debug', $sql);
		$this->db->query($sql);
	}
	
	function delete_board($args) {
		$id = $args['id'];
		$sql = "DELETE FROM {$this->_table_board} WHERE ID={$id}";
		log_message('debug', $sql);
		$this->db->query($sql);
		
		//$this->db->delete($this->_table_board, array('ID'=>$id));
	}
	
	function get_board_nextval() {
		$sql = "SELECT {$this->_seq_board} AS NEXTVAL FROM DUAL";
		$result = $this->db->query($sql)->row();
		return $result->NEXTVAL;
	}
	
	/*
	 * Board File
	 */
	function list_board_file($args) {
		$sql = "SELECT * FROM {$this->_table_board_file} WHERE BOARD_ID={$args['board_id']}";
		log_message('debug', $sql);
		return $this->db->query($sql)->result();
	}
	
	function insert_board_file($args) {
		$sql = "INSERT INTO {$this->_table_board_file} values ({$this->_seq_board_file},
				{$args['board_id']}, '{$args['filename']}', '{$args['saved_filename']}', {$args['filesize']})";
		
		log_message('debug', $sql);
		$this->db->query($sql);
	}
	
	function get_board_file($args) {
		return $this->db->get_where($this->_table_board_file, array('ID'=>$args['id']))->row();
	}
	
	function delete_board_file($args) {
		$sql = "DELETE FROM {$this->_table_board} WHERE ID={$args['id']}";
		log_message('debug', $sql);
		$this->db->query($sql);
	}
	
	
	/*
	 * Board Reply
	 */
	function count_board_reply($args) {
		$sql = "SELECT COUNT(*) CNT FROM {$this->_table_board_reply} WHERE BOARD_ID= {$args['board_id']}";
		
		$result = $this->db->query($sql)->row();
		return $result->CNT;
	}
	
	function list_board_reply($args) {

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
							SELECT * FROM {$this->_table_board_reply} WHERE BOARD_ID= {$args['board_id']}
								ORDER BY ID DESC
						) T
					) WHERE RN BETWEEN {$row_start} AND {$row_end} ORDER BY ID DESC
				) B, {$this->_table_user} U WHERE B.USER_ID = U.ID {$search}";

		log_message('debug', $sql);
		return $this->db->query($sql)->result();
	}
	
	function insert_board_reply($args) {
		$sql = "INSERT INTO {$this->_table_board_reply} VALUES ({$this->_seq_board_reply},
					{$args['board_id']}, {$args['user_id']}, '{$args['content']}', SYSDATE)";
		
		log_message('debug', $sql);
		$this->db->query($sql);
	}
	
	function update_board_reply($args) {
		$sql = "UPDATE {$this->_table_board_reply} SET CONTENT='{$args['content']}' WHERE ID={$args['id']}";
		log_message('debug', $sql);
		$this->db->query($sql);
	}
	
	function delete_board_reply($args) {
		$sql = "DELETE FROM {$this->_table_board_reply} WHERE ID={$args['id']}";
		log_message('debug', $sql);
		$this->db->query($sql);
	}
	
}
