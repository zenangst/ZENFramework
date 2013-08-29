<?php
class Controller extends ZENObject {
	
	function __construct() {
	    $args = new stdClass();
		if (func_num_args() > 0)
		    $args = func_get_arg(0);
        $this->arguments = $args;
	}
	
	function main() {
		die('Override this method');
	}
	
}