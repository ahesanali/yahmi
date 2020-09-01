<?php
namespace Yahmi\Routing;
/**
 * This class will indicate small part of request uri like from
 *
 * projects/6/request_tickets/95/show 
 * it will only indicate projects or :id
 */
class RequestURIPart
{
	private $partName;
	private $partType;
	const URI_STRING = "uri_string";
	const PARAMETER = "parameter";

	public function __construct($partName)
	{
		$this->partName = $partName;
		$this->detectRequestURIPatyType();
	}

	public function getPartName()
	{
		return $this->partName ;
	}

	public function getPartType()
	{
		return $this->partType ;
	}
	private function detectRequestURIPatyType()
	{
		if(strpos($this->partName,':') !== false){
			$this->partType = self::PARAMETER;
		}else{
			$this->partType = self::URI_STRING;
		}
	}
	public function equals(RequestURIPart $requestURIPart)
	{
		if($this->partName == $requestURIPart->getPartName() &&
			$this->partType == $requestURIPart->getPartType())
			return true;
		return false;
	}
}