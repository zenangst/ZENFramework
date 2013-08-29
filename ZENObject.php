<?php 

class ZENObject {
	
	protected $vars = null;
	
	function __get($name) {
	    return (isset($this->vars[$name])) ? $this->vars[$name] : null;
	}
	
	function __set($name, $value) {
		$this->vars[$name] = $value;
	}
	
}

?>