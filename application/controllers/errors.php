<?php
class Errors extends MY_controller {
	public function e404() {
		$this->_load_view('/error/error_404');
	}
	
	public function access() {
		$this->_load_view('/error/error_access');
	}
	
	public function auth() {
		$this->_load_view('/error/error_auth');
	}
	
	
}
?>