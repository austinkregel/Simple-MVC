<?php error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", 1);
define("APP_PATH", "/var/sentora/hostdata/zadmin/public_html/embb_pw");
// eventually will be usefull REQUEST_METHOD
// SERVER_PROTOCOL
//Controller Naming convention, Controller name = Controller class name
$route = new Route();
$method = $_SERVER['REQUEST_METHOD'];
if($method === 'GET')
$route->get('/', 'HelloWorld@second')
	  ->get('second', 'HelloWorld@second')
	  ->get('help', 'HelloWorld@index')
	  ->get('help/yes', 'HelloWorld@index');
elseif($method === 'POST')
  $route->post('/','HelloWorld@post');
elseif($method === 'DELETE')
 $route->delete('stuff', '');
class Router{
  public $routes = [];
  public $controller = [];

  function main($route, $controller){
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
  	$controllerPath = APP_PATH.'/Controllers/'.$controller.'.php';
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
	public function get($route, $control){
	    if($_SERVER['REQUEST_METHOD'] === 'GET')
	    	parent::main($route, $control);
	    else 
	    	new Exception("That method is not allowed!");
	    return $this;
	}
	public function post($route, $cotrol){
		return $this;
	}
}
class Controller{
	public function __construct(){
		
	}
}

class View{
	public static function make($viewPath){
	  	$controllerPath = APP_PATH.'/view/'.$viewPath.'.php';
	  	include $controllerPath;
	}
}
function prin(){
	$args = func_get_args();
	foreach($args as $p){
	  echo "<pre>";print_r($p);echo "</pre>";
	}
}
