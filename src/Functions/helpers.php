<?php
//use statement for framework classes and use each classes in helper functions


use Yahmi\Auth\AuthManager;
use Illuminate\Container\Container;

// app('auth_manager') = AuthManager::getInstance();

if (! function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param  string|null  $abstract
     * @param  array  $parameters
     * @return mixed|\Illuminate\Contracts\Foundation\Application
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract, $parameters);
    }
}

if (! function_exists('config')) {
	function config($config_attribute, $default_value = null)
	{
		$config = app('config');
		if($config->has($config_attribute))
			return $config->get($config_attribute, $default_value);
		return null;
	}
}



if (! function_exists('route')) {
	function route($route_name,$params = [])
	{
        $router = app()->make(Yahmi\Routing\Router::class);
        
		return $router->generateUrl($route_name,$params);;
	}
}


/**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  array  $data
     * @return \Yahmi\View\View
     */
    function view($view = null, $data = [])
    {
        $viewEngine = app()->make(Yahmi\View\View::class);


        return $viewEngine->renderView($view, $data);
    }

if (! function_exists('base_path')) {
    /**
     * Get the path to the base of the install.
     *
     * @param  string  $path
     * @return string
     */
    function base_path($path = '')
    {
        return app()->basePath($path);
    }
}
if (! function_exists('storage_path')) {
    /**
     * Get the path to the storage folder.
     *
     * @param  string  $path
     * @return string
     */
    function storage_path($path = '')
    {
        return app('path.storage').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (! function_exists('resource_path')) {
    /**
     * Get the path to the resources folder.
     *
     * @param  string  $path
     * @return string
     */
    function resource_path($path = '')
    {
        return app()->resourcePath($path);
    }
}

if (! function_exists('app_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param  string  $path
     * @return string
     */
    function app_path($path = '')
    {
        return app()->path($path);
    }
}

if (! function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param  string  $path
     * @return string
     */
    function public_path($path = '')
    {
        return app()->publicPath();
    }
}
/**
 * Generate asset file path 
 * Like css,js, or image path
 */
if (! function_exists('asset')) {
	function asset($asset_file_name)
	{
		return resource_path('assets').DIRECTORY_SEPARATOR.$asset_file_name;
	}
}

/**
 * check weather logged in user role have given permission access
 * @param  string $permission_name
 */
if (! function_exists('haveAccessWithRedirect')) {
	function haveAccessWithRedirect($permission_name)
	{
		
		global $app_name;

		if(! app('auth_manager')->getLoggedInUser()->hasAccess($permission_name) )
		{
			//app('auth_manager')->logout();
			//TODO:: set unauthorise conctroll end point
			header('Location: '.$app_name.'/views/error/unauthorize.php');
		}
	}
}
/**
 * check weather logged in user role have given permission access
 * @param  string $permission_name
 * @return boolean
 */
if (! function_exists('haveAccess')) {
	function haveAccess($permission_name)
	{
		

		return app('auth_manager')->getLoggedInUser()->hasAccess($permission_name);
	}
}

/**
 * Check wheather user is logged in or not
 * @return boolean
 */
if (! function_exists('isUserLoggedIn')) {
	function isUserLoggedIn()
	{
	
		return app('auth_manager')->isUserLoggedIn();
	}
}
/**
 * check weather logged in user is super or not
 * @return boolean
 */
if (! function_exists('isSuper')) {
	function isSuper()
	{
		

		return app('auth_manager')->getLoggedInUser()->isSuper();
	}
}
/**
 * get logged in user
 * @return User
 */
if (! function_exists('getLoggedInUser')) {
	function getLoggedInUser()
	{
		return app('auth_manager')->getLoggedInUser();
	}
}






