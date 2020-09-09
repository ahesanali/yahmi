<?php
namespace Yahmi\View;


use \Exception;
use AhesanAli\Blade;


 class View
{
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
   * Blade template instance
   * @var [type]
   */
  private $blade;

  public function __construct(Blade $blade)
  {
  		$this->blade = $blade;
  }
  
  public function renderView($view_file, array $modal_data = null)
  {
   		//README:: each controller will pass view file path as dot like products.index actuall files will have product/index.blade.php
      $views_directory = $this->blade->viewPaths[0];
      $view_file = str_replace(".","/",$view_file);
      if(file_exists($views_directory.DIRECTORY_SEPARATOR.$view_file. self::$DOT_BLADE_EXT) ||
        file_exists($views_directory.DIRECTORY_SEPARATOR.$view_file. self::$DOT_PHP_EXT))
        echo $this->blade->view()->make($view_file,$modal_data)->render();
      else
         throw new Exception("View file ".$views_directory.DIRECTORY_SEPARATOR.$view_file." does not exist");
  }
}	
