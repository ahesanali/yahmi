<?php
namespace Yahmi\Routing;

use \Exception;

class RouteNotFoundException extends Exception
{
	protected $message = 'Route Not Found Exception';   // exception message
    private   $string;                          // __toString cache
    protected $code = 0;                        // user defined exception code

	public function __construct($message = null, $code = 0,  Exception $previous = null)
	{
		// make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
	}
}