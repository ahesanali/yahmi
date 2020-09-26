<?php

namespace Yahmi\Http;

/**
* This class is used to generate various reposne for API
* 1. JSON Response
*/
class YahmiResponse
{
	/**
	*  Return json reponse with given data
	*  Possible Response Codes
	* 1. 400 for validaion fails
	* 2. 500 for internal server errors
	* 3. 200 Ok for successful execution of request
	*/
	public static function generateJSONResponse($responseCode,$responseMessage,$responseContent)
	{
		$response = array(
			'responseCode' => $responseCode,
			'responseMessage' => $responseMessage,
			'responseContent' => $responseContent
		);
		http_response_code($responseCode);
		return json_encode($response);
	}

} // end of class
