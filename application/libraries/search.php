<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search {

	public $date_start = '';
	public $date_end = '';
	public $date_count = '';
	public $guest_num = '1';

	public $year_cur;
	public $month_cur;
	public $day_last;
	
	function __construct() {
		$a = func_get_args();
		$i = func_num_args();
		if ($i == 0) {
			
		}
		else if (method_exists($this, $f='__construct'.$i)) {
			call_user_func_array(array($this, $f), $a);
		}
	}

	function __construct2($year, $date_end) {
		parent::__construct();
		
	}
	
	function init($input) {
		$this->year_cur = $input->get_post('year_cur');
		if (! $this->year_cur) {
			$this->year_cur = date('Y');
		}
		
		$this->month_cur = $input->get_post('month_cur');
		if (! $this->month_cur) {
			$this->month_cur = date('n');
		}
		
		$this->date_start = $input->get_post('date_start');
		if (! $this->date_start) {
			$this->date_start = "{$this->year_cur}/{$this->month_cur}/1";
		}
		
		$this->day_last = $input->get_post('day_last');
		if (! $this->day_last) {
			$date = new DateTime("{$this->year_cur}/{$this->month_cur}/1");
			$this->day_last = $date->format('t');
		}
		
		$this->date_end = $input->get_post('date_end');
		if (! $this->date_end) {
			$this->date_end = "{$this->year_cur}/{$this->month_cur}/{$this->day_last}";
		}
		
		$this->date_count = $input->get_post('date_count');
		if (! $this->date_count) {
			// $this->date_count = $input->get_post('date_count');
		}
		
		$this->guest_num = $input->get_post('guest_num');
		if (! $this->guest_num) {
			// $this->date_count = $input->get_post('date_count');
			$this->guest_num = 1;
		}
		
	}
	
	
}
