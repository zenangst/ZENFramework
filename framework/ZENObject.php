<?php 
class ZENObject {
	
	protected $vars = null;
	protected $observers = null;
	
	public function __construct($className = null) {
	    if ($className)
		$this->__className = $className;
	}
	
	public function __get($name) {
	    return (isset($this->vars[$name])) ? $this->vars[$name] : null;
	}
	
	public function __set($name, $value) {
		$this->vars[$name] = $value;
		// Set observer value
		if (isset($this->observers[$name]['closure']))
    		$this->observers[$name]['closure'](
    		    $this->observers[$name]['keypath'],
    		    $this->observers[$name]['observer'],
    		    $this
            );
	}
	
	public function property($name) {
    	if (property_exists($this, $name))
    	    return $this->$name;
	}
	
	public function setProperty($key, $value) {
		$this->$key = $value;
	}
	
	public function __toString() {
    	$description = ($this->__className) ? $this->__className : get_class($this);
    	$description = 'class ' . $description;
    	$description .= print_r((array)$this, true);
	    $description = "<pre>{$description}</pre>";
	    $description = preg_replace('/array/i', '', $description);
		return $description;
	}

    public function description() {
        return $this;
    }
    
    public function addObserver($observer, $keypath, $closure = null) {
        $key = (is_array($keypath)) ? key($keypath) : $keypath;
        
        if (!$closure) {
            $closure = function($keypath, $observer, $target) {
                if (is_array($keypath)) {
                    $observerKey = current($keypath);
                    $targetKey = key($keypath);
                } else {
                    $observerKey = $targetKey = $keypath;
                }
                $observer->$observerKey = $target->$targetKey;
            };
        }
        $this->observers[$key] = array(
            'observer'  => $observer,
            'closure'   => $closure,
            'keypath'   => $keypath
        );
    }
    
    public function removeObserver($key = null) {
        if ($key)
        	$this->observers[$key] = null;
    	else
    	    unset($this->observers);
    }
	
}