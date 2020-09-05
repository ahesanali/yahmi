<?php
namespace Yahmi\Core;

use Invoker\Exception\NotCallableException;
use Yahmi\Routing\RouteNotFoundException;
use Yahmi\Routing\ControllerNotFoundException;
use Yahmi\Routing\RequestURIPart;
use Yahmi\Contracts\Http\Kernel as KernelContract;

class Kernel implements KernelContract
{
	
	/**
	 * Application instance
	 * @var \Yahmi\Core\Application
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
    protected $routeMiddleware = [];


    /**
     * Create a new HTTP kernel instance.
     *
     * @param  \Yahmi\Core\Application  $app
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
        try{
            // $router = $container->get('router');
            $router = $this->router;
            
            $controller_namespace = config('app.php','controller_namespace');
            $middleware_namespace = config('app.php','middleware_namespace');

            $request_action = $router->getRequestAction($request_url);    
            $matchingRoute = $router->getMatchingRouteFromURI($request_action);
            $parameters = $matchingRoute->getRequestURI()->getParameters($request_action);
            $class_name = $controller_namespace.$matchingRoute->getController();
            $controller = $container->make($class_name);

            //invoke middleware before invoking controller action
            $middlewares = $matchingRoute->getMiddlewares();
            //TODO:: this should be part of Route object call
            foreach($middlewares as $middleware){
                $middleware_class = $middleware_namespace.$middleware;
                $middleware = $container->make($middleware_class);
                call_user_func_array(array($middleware, 'run'),[]);    //as of now we are passing empty parameters letter on we will pass actual parameters
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

                $middleware_class = $middleware_namespace.$middleware['middleware'];
                $middleware = $container->make($middleware_class);
                call_user_func_array(array($middleware, 'run'),[]);    //as of now we are passing empty parameters letter on we will pass actual parameters
            }
            //invoking the controller
            call_user_func_array(array($controller, $matchingRoute->getActionMethod()),$parameters);
        //TODO:: Try to implement some fancy and nicely design stack trace for debug mode of application instead of this exception message	
        } catch (ControllerNotFoundException $cne) {
            echo "Associate controller not found for given route. Cause:".$cne->getMessage();
        } catch (RouteNotFoundException $rne) {
            echo "Requested route not found. Cause:".$rne->getMessage();
        } catch (NotCallableException $nce) {
        	echo "Requested URL not found. Cause:".$nce->getMessage();
        }
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
}