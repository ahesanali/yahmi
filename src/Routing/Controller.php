<?php
namespace Yahmi\Routing;


use \Exception;
use Philo\Blade\Blade;
use DI\Container;

abstract class Controller
{
  
  /**
   * View default base directory
   * @var string
   */
  protected $viewBaseDir = "views/";

  /**
   * View cache directory
   * @var string
   */
  protected $cacheBaseDir = "resources/cache/";

  /**
   * Blade template instance
   * @var [type]
   */
  private $blade;

  /**
   * [$DOT_BLADE_EXT description]
   * @var string
   */
  private static $DOT_BLADE_EXT = ".blade.php";

  /**
   * [$DOT_PHP_EXT description]
   * @var string
   */
  private static $DOT_PHP_EXT = ".php";
  
  /**
   * Dependancy injection container
   *
   * @var Container
   */
  private $container;


  /**
   * Controller middlewres for actions
   * @var array
   */
  private $middlewares = [];


  public function __construct()
  {
      $this->blade = new Blade($this->viewBaseDir, $this->cacheBaseDir);
  }

 /**
  * Set dependnacy injection container in controller class
  *
  * @param Container $container
  * @return void
  */   
  public function setContainer(Container $container)
  {
        $this->container = $container;
  }
  /**
   * Get dependnacy injection container
   *
   * @return DI\Container
   */
  public function getContainer()
  {
      return  $this->container;
  }
  /**
   * [view description]
   * @param  [type] $view_file [description]
   * @return [type]            [description]
   */
  protected function view($view_file,array $modal_data = null)
  {
      //README:: each controller will pass view file path as dot like products.index actuall files will have product/index.blade.php
      $view_file = str_replace(".","/",$view_file);
      if(file_exists($this->viewBaseDir.$view_file. self::$DOT_BLADE_EXT) ||
        file_exists($this->viewBaseDir.$view_file. self::$DOT_PHP_EXT))
        echo $this->blade->view()->make($view_file,$modal_data)->render();
      else
         throw new Exception("View file ".$this->viewBaseDir.$view_file." does not exist");
         
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
