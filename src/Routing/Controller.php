<?php
namespace Yahmi\Routing;


use \Exception;

abstract class Controller
{
  
  /**
   * Controller middlewres for actions
   * @var array
   */
  private $middlewares = [];


  public function __construct()
  {
     
  }

 
  
  /**
   * Redirect request to specific Route
   * @param  String $$route_name  
   * @param  array $parameters 
   * @return [type]             
   */
  protected function redirectToRoute($route_name,$parameters = null)
  {
    header('Location: '.$this->generateUrl($route_name,$parameters));
    exit;
  }
  
  
  protected function generateUrl($route_name, $parameters = array())
  {
        return $this->getContainer()->get('router')->generateUrl($route_name,$parameters);
  }

  /**
   * Redirect response to previous URL
   * @return [type] [description]
   */
  protected function redirectBack()
  {
    //TODO:: implement this method
  }
  
  /**
   * Register middleware
   */
  
  public function middleware($middleware_name, array $middleware_options = [])
  {
       $this->middlewares[] = [
              'middleware' => $middleware_name,
              'options' => collect($middleware_options),
      ];
  }

  public function getMiddlewares()
  {
    return $this->middlewares;
  }
}
