<?php
namespace Yahmi\Routing;

use Illuminate\Support\Collection;
use Yahmi\Config\Config;

class Router{

	/**
	 * Collection of routes
	 * @var Array[] Route
	 */
	private $routes;

	public function __construct()
	{
		$this->routes = new Collection();
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
	public function dispatch($uri)
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
			$config = new Config('app.php');
			$config->get('app_name'); //returning app base path
			
			return $config->get('app_name').$firstMatchingRoute->getRequestURI()->getActualUrl($params);
		}else{
			throw new RouteNotFoundException("RouteNotFound! requested route is invalid.", 1);
		}
	}
	
}
