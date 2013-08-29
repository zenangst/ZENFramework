<?php
if (!defined('PATH'))
    die('You need to define a PATH for your root directory');

$request = (isset($_SERVER['REQUEST_URI'])) 
? substr($_SERVER['REQUEST_URI'],1) : '' ;   
$router = new Router($request);

$spotlight = Spotlight::find("{$router->controller}Controller.php", array(
    0 => PATH.'/controllers'
));

if (!$router->method)
    $router->method = "main";

if ($controller = current($spotlight)) {
	if (!class_exists($controller['filename']))
    	include $controller['fullpath'];
    $instance = new $controller['filename']($router->arguments);
    $instance->view = new View();
    if (method_exists($instance, $router->method)) {
        $instance->view->templateDirectory = array(
            PATH.'/views/'. str_replace('Controller','',$controller['filename']),
            PATH.'/views'
        );
        $instance->{$router->method}();
    }
        
}
    
?>