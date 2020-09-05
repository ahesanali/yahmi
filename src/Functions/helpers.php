<?php
//use statement for framework classes and use each classes in helper functions

use Yahmi\Config\Config;
use Yahmi\Auth\AuthManager;

$authManager = AuthManager::getInstance();

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
	function config($config_file_name,$config_attribute)
	{
		$config = new Config($config_file_name,$config_attribute);
		if($config->has($config_attribute))
			return $config->get($config_attribute);
		return null;
	}
}



if (! function_exists('route')) {
	function route($route_name,$params = [])
	{
		return app()->make('router')->generateUrl($route_name,$params);;
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
        $view = app(View::class);


        return $view->renderView($view, $data);
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

if (! function_exists('asset')) {
	function asset($asset_file_name)
	{
		return config('app.php','app_dir').'/assets/'.$asset_file_name;
	}
}

/**
 * check weather logged in user role have given permission access
 * @param  string $permission_name
 */
if (! function_exists('haveAccessWithRedirect')) {
	function haveAccessWithRedirect($permission_name)
	{
		global $authManager;
		global $app_name;

		if(! $authManager->getLoggedInUser()->hasAccess($permission_name) )
		{
			//$authManager->logout();
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
		global $authManager;

		return $authManager->getLoggedInUser()->hasAccess($permission_name);
	}
}

/**
 * Check wheather user is logged in or not
 * @return boolean
 */
if (! function_exists('isUserLoggedIn')) {
	function isUserLoggedIn()
	{
		global $authManager;

		return $authManager->isUserLoggedIn();
	}
}
/**
 * check weather logged in user is super or not
 * @return boolean
 */
if (! function_exists('isSuper')) {
	function isSuper()
	{
		global $authManager;

		return $authManager->getLoggedInUser()->isSuper();
	}
}
/**
 * get logged in user
 * @return User
 */
if (! function_exists('getLoggedInUser')) {
	function getLoggedInUser()
	{
		global $authManager;

		return $authManager->getLoggedInUser();
	}
}






