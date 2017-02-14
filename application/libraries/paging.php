<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paging {
	private $ROW_MAX = 10;	/* row max per page */
	private $PAGE_MAX = 10;	/* page max per pagination */

	public $row_start = 0;
	public $row_end;
	public $row_count;
	public $row_max;
	public $page_num = 1;
	public $page_start;
	public $page_end;
	public $page_count;
	public $page_max;
	
	public $search_key = "";
	public $search_val = "";
	
	function _calc_row() {
		
		$this->row_start = ($this->page_num - 1) * $this->row_max + 1;
		$this->row_end = ($this->page_num * $this->row_max);
	}
	
	function __construct() {
		$a = func_get_args();
		$i = func_num_args();
		if ($i == 0) {
			$this->row_max = $this->ROW_MAX;
			$this->page_max = $this->PAGE_MAX;
			$this->page_num = 1;
			$this->_calc_row();
		}
		else if (method_exists($this, $f='__construct'.$i)) {
			call_user_func_array(array($this, $f), $a);
		}
		
		
	}

	function __construct2($row_max, $page_max) {
		parent::__construct();
		
		$this->row_max = $row_max; 
		$this->page_max = $page_max;
		$this->page_num = 1;
		$this->_calc_row();
	}
	
	function init($input) {
		$this->page_num = $input->get_post('page_num');
		log_message('debug', $this->page_num);
		if (! $this->page_num)
			$this->page_num = 1;
		$this->search_key = $input->get_post('search_key');
		$this->search_val = $input->get_post('search_val');
		$this->_calc_row();
	}
	
	function set_rowmax($row_max) {
		$this->row_max = $row_max;
		$this->_calc_row();
	}
	
	function calculate() {
		
		if ($this->row_count % $this->row_max == 0)
			$this->page_count = $this->row_count / $this->row_max;
		else 
			$this->page_count = ($this->row_count / $this->row_max) + 1;
		
		$this->page_start = $this->page_num - (($this->page_num - 1) % $this->page_max);
		$this->page_end = $this->page_start + $this->page_max - 1;
		if ($this->page_end > $this->page_count)
			$this->page_end = $this->page_count;
	}
	
	function make_query() {
		if (func_num_args() == 0)
			return "page_num={$this->page_num}&search_key={$this->search_key}&search_val={$this->search_val}"; 		
		else {
			$args = func_get_args();
			return "page_num={$args[0]}&search_key={$this->search_key}&search_val={$this->search_val}";
		}
	}
	
}
