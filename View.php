<?php
class View extends ZENObject {

    function __construct() {
        $this->vars = null;
    }

    function getPlaceholders($contents) {
    	preg_match_all('/(\$\{([^}]+)})/i', $contents, $placeholders);
    	$retval = array();
    	foreach ($placeholders[1] as $key => $value )
    		$retval[$placeholders[2][$key]] = $value;
    	return $retval;
    }
    
    function setPlaceholderValues($placeholders = array(), $retval = '', $viewKey = 'templateVariables') {
    	foreach ($placeholders as $key => $placeholder) {
            if ($value = $this->vars[$viewKey][$key]) {
                $retval = str_replace($placeholder, $value, $retval);
            }
        }
        return $retval;
    }
    
    function clean($placeholders = array(), $retval = '') {
        foreach ($placeholders as $key => $placeholder) {
            $retval = str_replace($placeholder, '', $retval);
        }
        return $retval;
    }

    function parse($path = null, $placeholderKey) {
        $retval = null;
        $templateContents = file_get_contents($path);
        $placeholders['template'] = $this->getPlaceholders($templateContents);
        $templateContents = $this->setPlaceholderValues($this->getPlaceholders($templateContents), $templateContents);
        $retval = $templateContents;
        return $retval;
    }

    function assign() {
        switch (func_num_args()) {
            case 2:
                $args = func_get_args();
                $this->vars['templateVariables'][$args[0]] = $args[1];
                break;
            case 1:
                $args = func_get_arg(0);
                if (is_array($args))
                	foreach ($args as $key => $value)
                        $this->vars['templateVariables'][$key] = $value;
                break;
        	default:
        	    break;
        }
    }
    
    function display($template, $placeholder = 'BODY') {
        $templateFile = current(Spotlight::find($template, $this->templateDirectory));
        $this->vars['themeVariables'][$placeholder] = $this->parse($templateFile['fullpath'], $placeholder);
    }
    
    function render() {
        $this->setGlobals();
    	if ($this->theme) {
            $themePath = current(Spotlight::find($this->theme, $this->templateDirectory));
            $themeFile = $themePath['fullpath'];
            $output = file_get_contents($themeFile);
            $placeholders = $this->getPlaceholders($output);
            $output = $this->setPlaceholderValues($placeholders, $output, 'themeVariables');
            $output = $this->setPlaceholderValues($placeholders, $output, 'templateVariables');
        } else {
            if (isset($this->vars['themeVariables']))
            foreach ($this->vars['themeVariables'] as $key => $value)
            	$output .= $value;
        }
        
        if ( ! $this->displayPlaceholders )
        	$output = $this->clean($this->getPlaceholders($output), $output);
        if ($output) {
            echo $output;
        }
    }
    
    function setGlobals() {
        if ($this->theme)
        $this->vars['themeVariables']['THEME'] = str_replace('/', '', strstr(dirname($this->theme), '/'));
    }
}