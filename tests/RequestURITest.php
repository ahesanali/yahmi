<?php
use Yahmi\Routing\RequestURI;
use Yahmi\Routing\RequestURIPart;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class RequestURITest extends TestCase
{
	public function test_request_uri_part_count()
	{
		$requestUri = new RequestURI("projects/:id/tickets/:ticket_id");
		$this->assertEquals(4,count($requestUri->getRequestURIParts()) );
	}

	public function test_request_uri_part_type()
	{
		$requestUri = new RequestURI("projects/:id/tickets/:ticket_id");
		$this->assertEquals(RequestURIPart::PARAMETER,$requestUri->getURIPartByIndex(1)->getPartType() );
	}

	public function test_not_matching_request_uri_part_type()
	{
		$requestUri = new RequestURI("projects/:id/tickets/:ticket_id");
		$this->assertEquals(RequestURIPart::URI_STRING,$requestUri->getURIPartByIndex(2)->getPartType() );
	}

	public function test_compaer_uri()
	{
		$requestUri = new RequestURI("projects/:id/tickets/:ticket_id");
		$actualrequestUri = new RequestURI("projects/2/tickets/45");
		$this->assertEquals(true,$requestUri->match($actualrequestUri) );	
    }
    
    public function test_requet_uri_part_count()
    {
        
        $requestUri = new RequestURI("projects/:id/tickets/:ticket_id");
        $url = $requestUri->getActualUrl([':id'=>2,':ticket_id'=>34]);
        fwrite(STDERR, print_r($url, TRUE));
        $this->assertTrue(true);
    }
}