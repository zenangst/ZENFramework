<?php
class Router extends ZENObject {
	
	function __construct($request = null) {
		$this->delimiter = '/';
		if (!is_null($request))
		    $this->main($request);
	}
	
    function parse($string = null) {
    	$arguments = explode($this->delimiter, $string);
    	$this->arguments = new stdClass();
    	while ($argument = current($arguments)) {
    	    switch (key($arguments)) {
    	        case 0: $this->controller = $argument; break;
    	        case 1: $this->method     = $argument; break;
                default:
                    if (!isset($this->arguments->$argument)) {
                        $value = next($arguments);
                        if (!$value)
                            $value = $argument;
                        
                        $this->arguments->$argument = $value;
                    }
                    break;
    	    }
    		next($arguments);
    	}
    }
    
    function main($request = '') {
    	$this->parse($request);
    }
}
