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
    
    function setPlaceholderValues($placeholders = array(), $retval = '') {
    	foreach ($placeholders as $key => $placeholder)
            if ($value = $this->vars['templateVariables'][$key])
                $retval = str_replace($placeholder, $value, $retval);
        return $retval;
    }

    function parse($path = null) {
        $retval = null;
        $templateContents = file_get_contents($path);
        $placeholders['template'] = $this->getPlaceholders($templateContents);
        $templateContents = $this->setPlaceholderValues($this->getPlaceholders($templateContents), $templateContents);
        $retval = $templateContents;
        if ($this->theme) {
            $themePath = current(Spotlight::find($this->theme, $this->templateDirectory));
            $themeFile = $themePath['fullpath'];
            $themeContents = file_get_contents($themeFile);
            $this->assign('BODY', $templateContents);
            $retval = $this->setPlaceholderValues($this->getPlaceholders($themeContents), $themeContents);
        }
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
    
    function display($template) {
        $templateFile = current(Spotlight::find($template, $this->templateDirectory));
        $contents = $this->parse($templateFile['fullpath']);
        if ($this->debug) {
            echo '<pre>';
            echo htmlspecialchars($contents);
            echo '</pre>';
        } else {
            echo $contents;
        }
    }
}