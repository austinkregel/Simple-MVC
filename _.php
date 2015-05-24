<?php 
error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", 1);
define("APP_PATH", "/base/path/to/file/directory");
// eventually will be usefull REQUEST_METHOD
// SERVER_PROTOCOL

//Controller Naming convention, Controller name = Controller class name
$route = new Route();
$route->main('help', 'HelloWorld@index');
$route->main('butt', 'HelloWorld@index');
$route->main('help/yes', 'HelloWorld@second');
class Router{
  public $routes = [];
  public $controller = [];

  public function main($route, $controller){
	$function = (strstr($controller, '@') !== false)?$this->splitControllerFunction($controller):null;
	$this->addRoute($route, $controller);
	$url = $_SERVER['REQUEST_URI'];
	similar_text($route, $url, $o);
 	if($o > 85)
	    if($this->requireController($controller) !== false){
			$this->runFunction($controller, $function);
	    }
    return $this;
  }
  /**
   * run function
   */
  private function runFunction($controller, $function=null){
  	if(!($function instanceof null)){
	  	$con = new $controller;
	  	return $con->$function();
  	}
  	return new $controller();
  }
  /**
   * Is static
   */
  private function is_static(){
	return !(isset($this) && get_class($this) == __CLASS__);
  }
  /**
   * Splits stuff
   */
  private function splitControllerFunction(&$controller){
  	if(strstr($controller, '@')){
	  	$a = explode('@', $controller);
	  	$controller = $a[0];
	  	return $a[1];
  	} 
  }
  /**
   * Require's the controller
   */
  private function requireController($controller){
  	$controllerPath = APP_PATH.'/controllers/'.$controller.'.php';
    if($this->controllerExists($controllerPath) !== false){
  		require $controllerPath;
  		return true;
    }
    return false;
  }
  /**
   * if controller exists
   */
  private function controllerExists($controllerPath){
  	if(file_exists($controllerPath)){
  		return true;
  	}
  	return false;
  }
  /**
   * Add Route
   */
  private function addRoute($route, $controller){
  	$this->routes[$controller] = $route;
  	return $this;
  } 
  /**
   * To string
   */
  public function __toString(){
  	header('Content-type: application/json');
  	return json_encode($this);
  }
}


class Route extends Router{
	public $route = '';
	public $controller = '';
	public static function get($route, $control){
	    if($_SERVER['REQUEST_METHOD'] === 'GET')
	    	Router::main($route, $control);
	    else 
	    	new Exception("That method is not allowed!");
	}
}
class Controller{
	public function __construct(){
		
	}
}

class View{
	public static function make($viewPath){
	  	$controllerPath = APP_PATH.'/views/'.$viewPath.'.php';
	  	$view = file_get_contents($controllerPath);
	  	echo $view;
	}
}
function prin(){
	$args = func_get_args();
	foreach($args as $p){
	  echo "<pre>";print_r($p);echo "</pre>";
	}
}
