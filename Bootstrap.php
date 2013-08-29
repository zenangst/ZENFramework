<?php
    
$request = (isset($_SERVER['REQUEST_URI'])) 
? substr($_SERVER['REQUEST_URI'],1) : '' ;   
$router = new Router($request);

$spotlight = new Spotlight("{$router->controller}Controller.php", array(
    0 => PATH.'/controllers'
));

if (!$router->method)
    $router->method = "main";

if ($controller = current($spotlight->results)) {
	if (!class_exists($controller['filename']))
    	include $controller['fullpath'];
    $instance = new $controller['filename']($router->arguments);
    if (method_exists($instance, $router->method))
        $instance->{$router->method}();
}
    
?>