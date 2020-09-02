<?php
/*
|--------------------------------------------------------------------------------------
| This class will load give config file and will return value for given key
|------------------------------------------------------------------------------------------
 */
namespace Yahmi\Config;

use Illuminate\Support\Collection;
use Exception;

class Config
{
  /**
   * [$fileName config php file]
   * @var string
   */
  private $fileName;

  /**
   * [$configuration config file content]
   * @var array
   */
  private $configuration;

  public function __construct($fileName)
  {
  	$this->fileName = $fileName;
  	$this->loadConfigFile();
  }
  /**
   * get value from config gile for given key
   * @param  [type] $keyName [description]
   * @return [type]          [description]
   */
  public function get($keyName)
  {
  		if($this->has($keyName))
  			return $this->configuration[$keyName];
  		else
  			throw new Exception($keyName." does not exist in ".$this->fileName, 1);
  }
  /**
   * Check weather config file has specified key or not
   * @param  string  $keyName 
   * @return boolean          
   */
  public function has($keyName)
  {
  	return $this->configuration->has($keyName);
  }
  /**
   * Load config file from config folder
   */
  private function loadConfigFile()
  {
      $application_root =  getcwd(); 
     $this->configuration = new Collection(include $application_root."/config/".$this->fileName );
  }
}