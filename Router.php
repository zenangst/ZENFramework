<?php

class Router extends ZENObject {
	
	function __construct() {
		$this->delimiter = '/';
	}
	
    function run($request = '') {
    	$this->parse_request($request);
    }
    
    function parse_request($string = null) {
    	$arguments = explode($this->delimiter, $string);
    	while ($argument = current($arguments)) {
    	    
    		next($arguments);
    	}
    }
    
}
