<?php
if (!defined('PATH'))
    die('You need to define a PATH for your root directory');

define('DEFAULT_CONTROLLER', 'Main');
define('DEFAULT_METHOD', 'main');

// Use raw REQUEST_URI input for parsing request
$request = (isset($_SERVER['REQUEST_URI'])) 
? substr($_SERVER['REQUEST_URI'],1) : '' ;   
$router = new Router($request);

// Set default controller
if (!$router->controller)
    $router->controller = DEFAULT_CONTROLLER;

// Set default method
if (!$router->method)
    $router->method = DEFAULT_METHOD;

$router->controller = ucfirst($router->controller);
$controllersPath = array( 0 => PATH.'/controllers' );
$spotlight = Spotlight::find("{$router->controller}Controller.php", $controllersPath);

// If class not found, try using default controller
if (!$spotlight) {
    // Use first value of arguments as method instead of controller
    $router->method = $router->controller;
    $router->controller = DEFAULT_CONTROLLER;
    $spotlight = Spotlight::find("{$router->controller}Controller.php", $controllersPath);
}

if ($spotlight) {
    // Get paths for controller via Spotlight
    $controller = current($spotlight);
    
	if (!class_exists($controller['filename']))
    	include $controller['fullpath'];
    // Create new controller instance
    $instance = new $controller['filename']($router->arguments);
    if (!method_exists($instance, $router->method))
        $router->method = DEFAULT_METHOD;
    
    // Configure view and add to controller
    $instance->view = new View();
    $instance->view->templateDirectory = array(
        PATH.'/views/'. str_replace('Controller','',$controller['filename']),
        PATH.'/views'
    );
    
    // Run method for selected class
    $instance->{$router->method}();
    
    // Render template for method in class
    
    if ($instance->view->templates)
        $instance->view->render();
}
    
?>