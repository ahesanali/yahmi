<?php
namespace Yahmi\Core;

use Illuminate\Container\Container;
use AhesanAli\Blade\Blade;
use Yahmi\Contracts\Container\Application as ApplicationContract;

class Application extends Container implements ApplicationContract
{
    /**
     * The YAHMI framework version.
     *
     * @var string
     */
    const VERSION = '1.0.0';
	/**
     * The base path for the YAHMI installation.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The custom application path defined by the developer.
     *
     * @var string
     */
    protected $appPath;

    /**
     * The custom storage path defined by the developer.
     *  This directory is used for storing log files, and blade view template cache and session storage files
     * @var string
     */
    protected $storagePath;

    /**
     * The application namespace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * Create a new Illuminate application instance.
     *
     * @param  string|null  $basePath
     * @return void
     */
    public function __construct($basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->registerBaseBindings();
        $this->registerAppServices();
        $this->registerCoreContainerAliases();
        $this->registerCoreContainerInstancesAliases();
    }
    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version()
    {
        return static::VERSION;
    }

    
     /**
     * Set the base path for the application.
     *
     * @param  string  $basePath
     * @return $this
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');

        $this->bindPathsInContainer();

        return $this;
    }
    /**
     * Get the path to the application "app" directory.
     *
     * @param  string  $path
     * @return string
     */
    public function path($path = '')
    {
        $appPath = $this->appPath ?: $this->basePath.DIRECTORY_SEPARATOR.'app';

        return $appPath.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * Set the application directory.
     *
     * @param  string  $path
     * @return $this
     */
    public function useAppPath($path)
    {
        $this->appPath = $path;

        $this->instance('path', $path);

        return $this;
    }

    /**
     * Get the base path of the Laravel installation.
     *
     * @param  string  $path Optionally, a path to append to the base path
     * @return string
     */
    public function basePath($path = '')
    {
        return $this->basePath.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }


    /**
     * Get the path to the application configuration files.
     *
     * @param  string  $path Optionally, a path to append to the config path
     * @return string
     */
    public function configPath($path = '')
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'config'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }


    /**
     * Get the path to the language files.
     *
     * @return string
     */
    public function langPath()
    {
        return $this->resourcePath().DIRECTORY_SEPARATOR.'lang';
    }

    /**
     * Get the path to the public / web directory.
     *
     * @return string
     */
    public function publicPath()
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'public';
    }

    /**
     * Get the path to the storage directory.
     *
     * @return string
     */
    public function storagePath()
    {
        return $this->storagePath ?: $this->basePath.DIRECTORY_SEPARATOR.'storage';
    }

    /**
     * Set the storage directory.
     *
     * @param  string  $path
     * @return $this
     */
    public function useStoragePath($path)
    {
        $this->storagePath = $path;

        $this->instance('path.storage', $path);

        return $this;
    }

    /**
     * Get the path to the resources directory.
     *
     * @param  string  $path
     * @return string
     */
    public function resourcePath($path = '')
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'resources'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
    /**
     * Bind all of the application paths in the container.
     *
     * @return void
     */
    protected function bindPathsInContainer()
    {
        $this->instance('path', $this->path());
        $this->instance('path.base', $this->basePath());
        $this->instance('path.lang', $this->langPath());
        $this->instance('path.config', $this->configPath());
        $this->instance('path.public', $this->publicPath());
        $this->instance('path.storage', $this->storagePath());
        $this->instance('path.resources', $this->resourcePath());
    }
    
    /**
     * Get the application namespace.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getNamespace()
    {
        if (! is_null($this->namespace)) {
            return $this->namespace;
        }

        $composer = json_decode(file_get_contents($this->basePath('composer.json')), true);

        foreach ((array) data_get($composer, 'autoload.psr-4') as $namespace => $path) {
            foreach ((array) $path as $pathChoice) {
                if (realpath($this->path()) === realpath($this->basePath($pathChoice))) {
                    return $this->namespace = $namespace;
                }
            }
        }

        throw new \RuntimeException('Unable to detect application namespace.');
    }
    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    protected function registerBaseBindings()
    {
        static::setInstance($this);

        $this->instance('app', $this);

        $this->instance(Container::class, $this);
    }

    /**
     * Register services needed for applicaiton
     * @return [type] [description]
     */
    public function registerAppServices()
    {
        $this->bind(Blade::class, function (Container $container) {
            $views_directory = config('paths.views');
            $views_cache_directory = config('paths.compiled');
            $blade = new Blade($views_directory, $views_cache_directory);
            
            return $blade;
        });
        
    }

    /**
     * Register the core class aliases in the container.
     *
     * @return void
     */
    public function registerCoreContainerAliases()
    {
        
        foreach ([
            'app'    => [self::class, \Illuminate\Contracts\Container\Container::class, \Yahmi\Contracts\Container\Application::class, \Psr\Container\ContainerInterface::class],
        ] as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->alias($key, $alias);
            }
        }

        foreach ([
            'config' => [\Yahmi\Config\Config::class],    
            'logger' => [\Yahmi\Log\Logger::class],
            'queue' => [\Yahmi\Queue\Queue::class],
            'queue_wroker' => [\Yahmi\Queue\QueueWorker::class],
            'session_manager' => [\Yahmi\Session\SessionManager::class],
        ] as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->alias($alias, $key);
            }
        }
        
        
    }
    /**
     * Register the core class aliases in the container.
     *
     * @return void
     */
    public function registerCoreContainerInstancesAliases()
    {   

        $this->singleton(\Yahmi\Contracts\Auth\AuthManager::class,\Yahmi\Auth\AuthManager::class);
        $this->alias(\Yahmi\Auth\AuthManager::class, 'auth_manager');

    }

    public function flush()
    {
        parent::flush();
    }
}
