<?php
namespace Yahmi\Routing;

/**
 *  This class will indicate one Route entry in routes.php file
 */
class Route{

	/**
	 * URI like /products/23/showDetail
	 * @var RequestURI
	 */
	private $requestURI;

	/**
	 * Accepted methods like GET,POST,DELETE,
	 * @var Array []
	 */
	private $acceptedMethods;

	/**
	 * Action indicates Controller class Name and method name
	 * @var String. IndexController
	 */
	private $controller;

	/**
	 * Controller action method name
	 * @var String getPerson
	 */
	private $actionMethod;

	/**
	 * [$middlewares description]
	 * @var Array
	 */
	private $middlewares;

	/**
	 * Route name as alias
	 * @var string
	 */
	 private $routeName;

	private static $METHOD_NAMES = ['GET','POST','DELETE','OPTION','PATCH'];

	public function __construct($routeName,array$methods,RequestURI $uri , $controller ,$method,array $middlewares)
	{
		$this->routeName = $routeName;
		$this->middlewares = $middlewares;
		$this->acceptedMethods = (array) $methods;
		$this->requestURI = $uri;
		$this->controller = $controller;
		$this->actionMethod = $method;

	}
	public function addMethodsAccepted($method)
	{
		$this->acceptedMethods[] = $method;
	}

	public function isURIMatch(RequestURI $requestURI)
	{
		return $this->requestURI->match($requestURI);
	}
	public function getController(){
		return $this->controller;
	}

	public function getActionMethod(){
		return $this->actionMethod;
	}

	public function getMiddlewares(){
		return $this->middlewares;
	}

	public function getRouteName()
	{
			return $this->routeName;
    }
    
    public function getRequestURI()
	{
			return $this->requestURI;
	}
}
