<?php
namespace Yahmi\Routing;

use Illuminate\Support\Collection;
use \Exception;
/**
 * This class will indicate request uri like from
 *
 * projects/6/request_tickets/95/show 
 */
class RequestURI
{
	/**
	 * Request URI : projects/7/tickets/4
	 * @var string
	 */
	private $requestURIString;
	/**
	 * RequestURIPart
	 * @var Array
	 */
	private $requestURIParts;

	public function __construct($requestURI)
	{
		$this->requestURIString = $requestURI;
		$this->requestURIParts = new Collection();
		$this->processURI();
	}
	/**
	 * Prepare request URI part
	 */
	private function processURI()
	{
		$requestURIStringArray = explode('/', $this->requestURIString);
		foreach ($requestURIStringArray as $key => $requestURIStringArrayItem) {
			$requestURIPart = new RequestURIPart($requestURIStringArrayItem);	
			$this->requestURIParts->push($requestURIPart);
		}
	}
	/**
	 * Get uri parts array
	 * @return Array[RequestURIPart]
	 */
	public function getRequestURIParts()
	{
		return $this->requestURIParts;
	}
	public function match(RequestURI $requestURI)
	{
		if($this->requestURIParts->count() == $requestURI->getRequestURIParts()->count() ){
			 $stringURIParts = $this->requestURIParts->filter(function($requestURIPart,$index) use($requestURI) {
									if($requestURIPart->getPartType() == RequestURIPart::URI_STRING)
										return $requestURIPart;
								})->filter(function($requestURIPart,$index) use($requestURI) {
									if($requestURIPart->equals( $requestURI->getURIPartByIndex($index))
										 == false) 
										return $requestURIPart;
                                });
            
			return ($stringURIParts->count() == 0);
		}else{
			return false;
		}
	}

	public function getURIPartByIndex($partIndex)
	{
		if($this->requestURIParts->has($partIndex))
			return $this->requestURIParts->get($partIndex);
		return null;
    }

    /**
     * This will return request uri part by part type if
     * it is uri_string or parameters
     * @return collection
     */    
    public function getRequetUriPartsByPartType($part_type)
    {
        $requestURIParts = $this->requestURIParts->filter(function($requestURIPart,$index) use($part_type) {
            if($requestURIPart->getPartType() == $part_type)
                return $requestURIPart;
        });

        return $requestURIParts;
    }
    /**
     * get actual url from requet uri
     * if request uri is /project/:id/tickets/:ticket_id will be converted to /project/2/tickets/45
     * @param array $params
     * @return void
     */
    public function getActualUrl(array $params = [])
    {
         $actual_url = collect([]);
         $params_collection = collect($params);
         $requestURIParametersParts = $this->getRequetUriPartsByPartType(RequestURIPart::PARAMETER);
         if($requestURIParametersParts->count() != $params_collection->count())
                throw new Exception("Route paramaters does not matched."); 
         $this->getRequestURIParts()->each(function ($requetUriPart, $index) use ($params_collection,$actual_url) {
            
            if ($requetUriPart->getPartType() == RequestURIPart::PARAMETER){
                if( $params_collection->has( $requetUriPart->getPartName() ) ) 
                    $actual_url->push($params_collection->get($requetUriPart->getPartName()));
                else
                    throw new Exception("Route paramater:".$requetUriPart->getPartName()." does not exist for."); 
            }else
                $actual_url->push($requetUriPart->getPartName());
        }); 
        
        return $actual_url->implode('/');  
    }
    /**
     * Get parameters array for request uri
     *
     * @return array
     */
    public function getParameters($uri)
    {   
        $requestUri = new RequestURI($uri);
        $parameters = [];
        $requestURIParametersParts = $this->getRequetUriPartsByPartType(RequestURIPart::PARAMETER);    
        foreach($requestURIParametersParts as $key => $requestUriPart){
            array_push($parameters,$requestUri->getURIPartByIndex($key)->getPartName());
        }
        
        return $parameters;
    }
}