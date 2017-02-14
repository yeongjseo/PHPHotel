<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Model extends CI_Model {
	
	/*
	 * Table
	 */
	protected $_table_board = 'PHOTEL_BOARD';
	protected $_table_user = 'PHOTEL_USER';
	protected $_table_board_file = 'PHOTEL_BOARD_FILE';
	protected $_table_board_reply = 'PHOTEL_BOARD_REPLY';
	
	protected $_table_room_type = 'PHOTEL_ROOM_TYPE';
	protected $_table_room_file = 'PHOTEL_ROOM_FILE';
	protected $_table_room = 'PHOTEL_ROOM';
	
	protected $_table_reserve = 'PHOTEL_RESERVE';
	
	/*
	 * Sequence
	 */
	protected $_seq_board = 'PHOTEL_BOARD_SEQ.NEXTVAL';
	protected $_seq_user = 'PHOTEL_USER_SEQ.NEXTVAL';
	protected $_seq_board_file = 'PHOTEL_BOARD_FILE_SEQ.NEXTVAL';
	protected $_seq_board_reply = 'PHOTEL_BOARD_REPLY_SEQ.NEXTVAL';
	
	protected $_seq_room_type = 'PHOTEL_ROOM_TYPE_SEQ.NEXTVAL';
	protected $_seq_room_file = 'PHOTEL_ROOM_FILE_SEQ.NEXTVAL';
	protected $_seq_room = 'PHOTEL_ROOM_SEQ.NEXTVAL';
	
	protected $_seq_reserve = 'PHOTEL_RESERVE_SEQ.NEXTVAL';
	
	
	function __construct() {
		parent::__construct();
	}
	
	
	function _to_date($char) {
		return "TO_DATE('{$char}','YYYY/MM/DD')";
	}
	
	function _to_char_date_time($field, $alias) {
		return "TO_CHAR({$field},  'YYYY/MM/DD HH24:MI:SS') AS {$alias}";
	}
	
	function _to_char_date($field, $alias) {
		return "TO_CHAR({$field},  'YYYY/MM/DD') AS {$alias}";
	}
}