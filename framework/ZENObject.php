<?php 
class ZENObject {
	
	protected $vars = null;
	
	public function __get($name) {
	    return (isset($this->vars[$name])) ? $this->vars[$name] : null;
	}
	
	public function __set($name, $value) {
		$this->vars[$name] = $value;
	}
	
	public function __toString() {
	    $description = get_class($this);
	    $description .= print_r(current((array)$this), true);
	    $description = "<pre>{$description}</pre>";
	    $description = preg_replace('/array/i', '', $description);
		return $description;
	}

    public function description() {
        return $this;
    }
	
}