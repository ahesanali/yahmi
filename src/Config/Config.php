<?php
/*
|--------------------------------------------------------------------------------------
| This class will load give config file and will return value for given key
|------------------------------------------------------------------------------------------
 */
namespace Yahmi\Config;

use Illuminate\Support\Arr;
use Exception;
use SplFileInfo;
use Symfony\Component\Finder\Finder;
use Yahmi\Core\Application;

class Config
{
 

  /**
   * All of the configuration items.
   *
   * @var array
   */
  private $configuration = [];

  public function __construct()
  {
  	// $this->fileName = $fileName;
    $this->configuration = []; 
  	$this->loadConfigFiles();
  }
  /**
   * get value from config gile for given key
   * @param  [type] $keyName [description]
   * @return [type]          [description]
   */
  public function get($key,$default = null)
  {
  		if($this->has($key))
  			return  Arr::get($this->configuration, $key, $default);
  		else
  			throw new Exception($key." does not exist ", 1);
  }

  /**
     * Set a given configuration value.
     *
     * @param  array|string  $key
     * @param  mixed  $value
     * @return void
     */
    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            Arr::set($this->configuration, $key, $value);
        }
    }
  /**
   * Check weather config file has specified key or not
   * @param  string  $keyName 
   * @return boolean          
   */
  public function has($key)
  {
  	return Arr::has($this->configuration, $key);
  }
  /**
   * Load config file from config folder
   */
  private function loadConfigFiles()
  {
      $application =  app(); 
      $files = $this->getConfigurationFiles($application);
      foreach ($files as $key => $path) {
            $this->set($key, require $path);
      }
      
     //$this->configuration = new Collection(include $application_root."/config/".$this->fileName );
  }

  /**
     * Get all of the configuration files for the application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return array
     */
    protected function getConfigurationFiles(Application $app)
    {
        $files = [];

        $configPath = realpath($app->configPath());

        foreach (Finder::create()->files()->name('*.php')->in($configPath) as $file) {
            $directory = $this->getNestedDirectory($file, $configPath);

            $files[$directory.basename($file->getRealPath(), '.php')] = $file->getRealPath();
        }

        ksort($files, SORT_NATURAL);

        return $files;
    }

    /**
     * Get the configuration file nesting path.
     *
     * @param  \SplFileInfo  $file
     * @param  string  $configPath
     * @return string
     */
    protected function getNestedDirectory(SplFileInfo $file, $configPath)
    {
        $directory = $file->getPath();

        if ($nested = trim(str_replace($configPath, '', $directory), DIRECTORY_SEPARATOR)) {
            $nested = str_replace(DIRECTORY_SEPARATOR, '.', $nested).'.';
        }

        return $nested;
    }
}
