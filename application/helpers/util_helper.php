<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/*
 * Form Validation
 */
if (! function_exists('set_array_value')) {
	function set_array_value($arr, $name) {
		if (is_array($arr)) 
			echo $arr[$name]; 
		else 
			echo set_value($name);
	}
}

if (! function_exists('set_array_select')) {
	function set_array_select($arr, $name, $value, $default = FALSE) {
		if (is_array($arr)) {
			if ($arr[$name] == $value)
				echo ' selected="selected"';
			else
				echo '';
		}
		else
			echo set_select($name, $value, $default);
	}
}

if (! function_exists('set_array_checkbox')) {
	function set_array_checkbox($arr, $name, $value, $default = FALSE) {
		if (is_array($arr)) {
			if ($arr[$name] == $value)
				echo ' checked="checked"';
			else
				echo '';
		}
		else
			echo set_checkbox($name, $value, $default);
	}
}

/*
 * Log
 */
if (! function_exists('log_debug')) {
	function log_debug($msg = '') {
		log_message('debug', $msg);
	}
}

if (! function_exists('log_error')) {
	function log_error($msg = '') {
		log_message('error', $msg);
	}
}



 