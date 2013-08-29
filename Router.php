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
    	$this->arguments = new stdClass();
    	while ($argument = current($arguments)) {
    	    switch (key($arguments)) {
    	        case 0: $this->controller = $argument; break;
    	        case 1: $this->method     = $argument; break;
                default:
                    if (!isset($this->arguments->$argument)) {
                        $value = next($arguments);
                        $this->arguments->$argument = $value;
                    }
                    break;
    	    }
    		next($arguments);
    	}
    }
    
}
