<?php
class Spotlight extends ZENObject {
    
    public function __construct() {
        if (func_num_args() > 0) {
            $this->arguments = func_get_args();
            $this->find();
        }
    }
    
	public function find() {
	    if (func_num_args() > 0)
	        $this->arguments = func_get_args();
	    
	    $arguments = $this->arguments;
	    while ($argument = current($arguments)) {
	        if (is_string($argument) && !isset($this->filename)) {
    	        $this->filename = $argument;
    	        next($arguments);
    	        continue;
	        }
	        $this->directories = $argument;
	    	next($arguments);
	    }
	    
	    $this->results = array();
	    foreach ($this->directories as $directory) {
	    	$filepath = $directory.'/'.$this->filename;
	    	if (file_exists($filepath)) {
	    	    $pathinfo = pathinfo($filepath);
	    	    $pathinfo['fullpath'] = $filepath;
	    	    $this->results = array_merge(array($pathinfo), $this->results);
	    	}
	    }
	}
	
}