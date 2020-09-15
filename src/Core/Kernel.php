<?php
namespace Yahmi\Core;

use Invoker\Exception\NotCallableException;
use Yahmi\Routing\RouteNotFoundException;
use Yahmi\Routing\ControllerNotFoundException;
use Yahmi\Routing\RequestURIPart;
use Yahmi\Routing\Router;
use Yahmi\Contracts\Http\Kernel as KernelContract;
use Yahmi\Contracts\Container\Application;

class Kernel implements KernelContract
{
	
	/**
	 * Application instance
	 * @var \Yahmi\Contracts\Container\Application
	 */
	protected $app;

	/**
	 * Router instance
	 * @var \Yahmi\Routing\Router
	 */
	protected $router;

	/**
     * The application's middleware stack.
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddlewares = [];


    /**
     * Create a new HTTP kernel instance.
     *
     * @param  \Yahmi\Contracts\Container\Application  $app
     * @param  \Yahmi\Routing\Router $router
     * @return void
     */
    public function __construct(Application $app, Router $router)
    {
        $this->app = $app;
        $this->router = $router;
    }
    
    //$request_url= /inspire-php-app/catalogue/titleList/34
    public function hanldeRequest($request_url)
    {
            
        $controller_namespace = $this->router->getControllerBaseNameSpace();
        $request_action = $this->router->getRequestAction($request_url);  
        
        $matchingRoute = $this->router->getMatchingRouteFromURI($request_action);
        $parameters = $matchingRoute->getRequestURI()->getParameters($request_action);
        $class_name = $controller_namespace.$matchingRoute->getController();
        $controller = $this->app->make($class_name);

        //invoke middleware before invoking controller action
        $middlewares = $matchingRoute->getMiddlewares();
        //TODO:: this should be part of Route object call
        foreach($middlewares as $middleware){
            $middleware_class_name = $this->getRouteMiddlewareClass($middleware);
            $middleware_class = $this->app->make($middleware_class_name);
            call_user_func_array(array($middleware_class, 'run'),[]);    //as of now we are passing empty parameters letter on we will pass actual parameters
        }
        //OR invoking middlewares from controller
        $middlewares = $controller->getMiddlewares();
        foreach($middlewares as $middleware){
            $middleware_option = $middleware['options'];
            //check if action method falls in only group
            if( $middleware_option->has('only') ){
                $only_methods = $middleware_option->get('only');
                if(!in_array($matchingRoute->getActionMethod(), $only_methods))
                    continue;     
            }

            //do not run middleware if action method fale in except group
            if( $middleware_option->has('except') ){
               $except_methods = $middleware_option->get('except');     
               if(in_array($matchingRoute->getActionMethod(), $except_methods))
                    continue;     
            }

            $middleware_class_name = $this->getRouteMiddlewareClass($middleware['middleware']);
            $middleware_class = $this->app->make($middleware_class_name);
            call_user_func_array(array($middleware_class, 'run'),[]);    //as of now we are passing empty parameters letter on we will pass actual parameters
        }
        //invoking the controller
        call_user_func_array(array($controller, $matchingRoute->getActionMethod()),$parameters);
    //TODO:: Try to implement some fancy and nicely design stack trace for debug mode of application instead of this exception message	

    }

    /**
     * Return middleware class from $routerMiddlewares array
     * @param  [type] $middleware_name [description]
     * @return [type]                  [description]
     */
    public function getRouteMiddlewareClass($middleware_name)
    {
        return $this->routeMiddlewares[$middleware_name];
    }
     /**
     * Get the YAHMI application instance.
     *
     * @return \Yahmi\Core\Application
     */
    public function getApplication()
    {
        return $this->app;
    }

    /**
     * Clear all data structures used with request
     * @return [type] [description]
     */
    public function flush()
    {
        
        $this->app->flush();
    }
}
