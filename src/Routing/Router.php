<?php
namespace Yahmi\Routing;

use Illuminate\Support\Collection;
use Yahmi\Config\Config;
use Yahmi\Functions\StringUtils;
use \ReflectionClass;

class Router
{
	/**
	 * Controller base name space in applicaiton to instatiate the class
	 * @var string
	 */
	private $controllerBaseNameSpace = "";
	/**
	 * Collection of routes
	 * @var Array[] Route
	 */
	private $routes;

	public function __construct()
	{
		$this->routes = new Collection();
	}
	public function setControllerBaseNameSpace($controllerNameSpace)
	{
		$this->controllerBaseNameSpace = $controllerNameSpace;
	}

	/**
	 * Return request action from URL. 
	 * For example if request url is : /inspire-php-app/public/catalogue/titleList/34
	 * This will return :catalogue/titleList/34
	 * @param  [type] $request_url [description]
	 * @return [string]              [reuquest actin]
	 */
	public function getRequestAction($request_url)
	{
			$app_public_dir = config('app.php','public_dir');
			$request_action = substr($request_url,strlen($app_public_dir)); //  /catalogue/titleList/34
            $request_action = strpos($request_action, '?') ? substr($request_action, 0, strpos($request_action, '?')) : $request_action; //Remove query string from request URI
            $parameters = [];
            
            if( empty($request_action) ){
                $request_action = "/";
            }

            return $request_action;
	}
	/**
	 * return Route which accept get request
	 * @param  [type] $routeName     /catalogue_addBook
	 * @param  [type] $uri     /catalogue/23/addBook
	 * @param  [type] $options  ['actionController'=>'ControllerClassName','action'=>'actionMethodName',
	 *                          	'middlewares' => ['MiddlewareClass1','MiddlewareClass2']];
	 */
	public function get($routeName, $uri, $options)
	{
		$this->addRoute($routeName,['GET','HEAD'],$uri, $options);
	}

	/**
	 * return Route which accept post request
	 * @param  [type] $routeName     /catalogue_addBook
	 * @param  [type] $uri     /catalogue/23/addBook
	 * @param  [type] $options  ['actionController'=>'ControllerClassName','action'=>'actionMethodName',
	 *                          	'middlewares' => ['MiddlewareClass1','MiddlewareClass2']];
	 */
	public  function post($routeName,$uri, $options){
		$this->addRoute($routeName,['POST'],$uri, $options);
	}

	/**
	 * Add routes using controller class Name
	 * @param  [String] $controllerName [just add  ProjectController]
	 */
	public function controller($controllerName)
	{
		$controller_class_name = $this->controllerBaseNameSpace.$controllerName;
		if (class_exists($controller_class_name) == FALSE)
			throw new ControllerNotFoundException("Controller class ".$controller_class_name." not found.", 0);
			
		$oReflectionClass = new ReflectionClass($controller_class_name);

		foreach ($oReflectionClass->getMethods() as $key => $reflectionMethod) {
			if($reflectionMethod->isConstructor())
				continue;
			$action_method = $reflectionMethod->name;
			
			$controller_uri_name = str_replace('controller', '', strtolower($controllerName));
			$method_name_only = str_replace(array("get", "post"), "", $action_method);
			$action_method_uri_name = StringUtils::camel2dashed($method_name_only);
			$action_method_route_name = StringUtils::camel2dashed($action_method);

			$route_name = $controller_uri_name.".".$action_method_route_name;
			$route_uri = "/".$controller_uri_name."/".$action_method_uri_name;

			foreach($reflectionMethod->getParameters() as $index =>$method_param)
			{
				$route_uri = $route_uri."/:".$method_param->name;
			}
			
			if (strpos($action_method, 'get') !== FALSE)
				$this->get($route_name, $route_uri , ['controller'=>$controllerName,'action' => $action_method]);

			if (strpos($action_method, 'post') !== FALSE)
				$this->post($route_name, $route_uri, ['controller'=>$controllerName,'action' => $action_method]);

		}

	}
	protected function addRoute($routeName,$methods,$uri, $options)
	{
		if (array_key_exists('middlewares', $options) === FALSE)
			$options['middlewares'] = [];
		$route = new Route($routeName,$methods, new RequestURI($uri), $options['controller'],
			$options['action'],$options['middlewares']);
		$this->routes->push($route);
	}
	/**
	 * Execute route action
	 */
	public function getMatchingRouteFromURI($uri)
	{
		$requestURI = new  RequestURI($uri);
		$matchingRoutes = $this->routes->filter(function($route) use($requestURI) {
			if($route->isURIMatch($requestURI))
				return $route;
		});

		if(count($matchingRoutes) > 0){
            $firstMatchingRoute = $matchingRoutes->first() ;
            
			return $firstMatchingRoute;
		}else{
			throw new RouteNotFoundException("RouteNotFound! requested route is invalid.", 1);
		}

	}

	/**
	 * Generate Url from route name
	 * @param  string $route_name
	 * @param  array  $params
	 * @return string
	 */
	public function generateUrl($route_name,array $params = [])
	{

		$matchingRoutes = $this->routes->filter(function($route) use($route_name) {
			if( $route->getRouteName() == $route_name )
				return $route;
		});

		if(count($matchingRoutes) > 0){
			$firstMatchingRoute = $matchingRoutes->first() ;
			$app_public_dir = config('app.php','public_dir');
			// $config->get('app_dir'); //returning app base path
			
			return $app_public_dir.$firstMatchingRoute->getRequestURI()->getActualUrl($params);
		}else{
			throw new RouteNotFoundException("RouteNotFound! requested route is invalid.", 1);
		}
	}

	public function getRouteNames()
	{
		$route_names = [];
		foreach ($this->routes as $key => $route) {
			$route_data =[];
			$route_data['name'] =$route->getRouteName();
			$route_data['uri'] =$route->getRequestURI();
			array_push($route_names, $route_data);
		}

		return $route_names;
	}
	
}
