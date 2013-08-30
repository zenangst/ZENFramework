<?php
class Spotlight extends ZENObject {
    
	public function find($filename, $directories) {
	    $arguments = func_get_args();
	    while ($argument = current($arguments)) {
	        if (is_string($argument) && !isset($filename)) {
    	        $filename = $argument;
    	        next($arguments);
    	        continue;
	        }
	        $directories = $argument;
	    	next($arguments);
	    }
	    
	    $retval = array();
	    foreach ($directories as $directory) {
	    	$filepath = $directory.'/'.$filename;
	    	if (file_exists($filepath) && $pathinfo = pathinfo($filepath)) {
	    	    $pathinfo['fullpath'] = $filepath;
	    	    $retval = array_merge(array($pathinfo), $retval);
	    	}
	    }
	    return (count($retval)) ? $retval : null;
	}
	
}