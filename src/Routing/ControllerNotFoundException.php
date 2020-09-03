<?php
namespace Yahmi\Routing;

use \Exception;

class ControllerNotFoundException extends Exception
{
	protected $message = 'Controller Not Found Exception';   // exception message
    private   $string;                          // __toString cache
    protected $code = 0;                        // user defined exception code

	public function __construct($message = null, $code = 0,  Exception $previous = null)
	{
		// make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
	}
}