<?php
class Member_model extends MY_Model {

	
	function __construct() {
		parent::__construct();
	}
	
	function count_member($arr) {
		$search_key = $arr['paging']->search_key;
		$search_val = $arr['paging']->search_val;
		if ($search_key != '')
			$search = " WHERE {$search_key} LIKE '%{$search_val}%' ";
		else
			$search = '';
		
		$sql = "SELECT COUNT(*) CNT FROM {$this->_table_user} $search";
		log_message('debug', $sql);
		$result = $this->db->query($sql)->row();
		return $result->CNT;
	}
	
	function get_members($arr) {
		
		if ($arr['paging']->search_key != '')
			$search = "WHERE {$arr['paging']->search_key} LIKE '%{$arr['paging']->search_val}%' ";
		else
			$search = '';
		
		$sql = "SELECT U.*, TO_CHAR (BIRTHDAY,  'yyyy/mm/dd') AS BIRTHDAY FROM (
					SELECT ROWNUM RN, T.* FROM (
						SELECT * FROM {$this->_table_user} {$search}
							ORDER BY ID DESC
					) T
				) U WHERE RN BETWEEN {$arr['paging']->row_start} AND {$arr['paging']->row_end}"; 
		
		log_message('debug', $sql);
		return $this->db->query($sql)->result();

	}

	function get_member_by_id($arr) {
		$sql = "SELECT U.ID, U.ACCOUNT, U.PASSWORD, U.NICKNAME, TO_CHAR(BIRTHDAY,  'yyyy/mm/dd') AS BIRTHDAY,
					U.ZIPCODE, U.ADDRESS1, U.ADDRESS2, U.EMAIL, U.EMAIL_CONFIRM, U.TEL
					FROM {$this->_table_user} U WHERE ID={$arr['ID']}";
		
		log_message('debug', $sql);
		return $this->db->query($sql)->row();	
	}

	function get_member_by_account($arr) {
		/*
		$result = $this->db->get_where($this->_table_user, array('ACCOUNT'=>$args['ACCOUNT']))->row();
		return $result;
		*/
		$sql = "SELECT U.ID, U.ACCOUNT, U.PASSWORD, U.NICKNAME, TO_CHAR(BIRTHDAY,  'yyyy/mm/dd') AS BIRTHDAY,
						U.ZIPCODE, U.ADDRESS1, U.ADDRESS2, U.EMAIL, U.EMAIL_CONFIRM, U.TEL
				FROM {$this->_table_user} U WHERE ACCOUNT='{$arr['ACCOUNT']}'";
		
		log_message('debug', $sql);
		return $this->db->query($sql)->row();
	}
	
	
	function insert_member($arr) {
		$sql = "INSERT INTO {$this->_table_user} VALUES ({$this->_seq_user},
					'{$arr['ACCOUNT']}', '{$arr['PASSWORD']}', '{$arr['NICKNAME']}', TO_DATE('{$arr['BIRTHDAY']}', 'YYYY/MM/DD'), 
					'{$arr['ZIPCODE']}', '{$arr['ADDRESS1']}', '{$arr['ADDRESS2']}', '{$arr['EMAIL']}',
					{$arr['EMAIL_CONFIRM']}, '{$arr['TEL']}')";
		
		log_message('debug', $sql);
		$this->db->query($sql);
		
	}
	
	function update_member($arr) {
		$sql = "UPDATE {$this->_table_user} SET ACCOUNT='{$arr['ACCOUNT']}', PASSWORD='{$arr['PASSWORD']}', 
					NICKNAME='{$arr['NICKNAME']}', BIRTHDAY=TO_DATE('{$arr['BIRTHDAY']}', 'YYYY/MM/DD'), ZIPCODE='{$arr['ZIPCODE']}', 
					ADDRESS1='{$arr['ADDRESS1']}', ADDRESS2='{$arr['ADDRESS2']}',	EMAIL='{$arr['EMAIL']}', 
					EMAIL_CONFIRM={$arr['EMAIL_CONFIRM']}, TEL='{$arr['TEL']}' WHERE ID={$arr['ID']}";
		log_message('debug', $sql);
		$this->db->query($sql);
		
	
	}
	
	function delete_member($arr){
		$sql = "DELETE FROM {$this->_table_user} WHERE ID={$arr['ID']}";
		log_message('debug', $sql);
		
		$this->db->query($sql);
	}
}
?>