<?php
use Yahmi\Routing\Router;
use Yahmi\Routing\Route;
use Yahmi\Routing\RequestURI;
use Yahmi\Routing\RequestURIPart;
use PHPUnit\Framework\TestCase;


class RouterTest extends TestCase
{
	public function test_router_collection()
	{
		$router = new Router();
		$router->get('projects','/projects',['controller'=>'ProjectController','action' => 'getProjects','middlewares'=>['FirstMiddleware']]);
		$router->get('show_project','/projects/:id',['controller'=>'ProjectController','action' => 'showProject']);
		$router->get('request_ticket','/projects/:id/request_tickets',['controller'=>'RTController','action' => 'getTickets']);
		$matching_route = $router->dispatch('/projects/45/request_tickets');
		//echo 'matching route'.$matching_route->getController();
		$this->assertEquals($matching_route->getController(),'RTController');
	}

	public function test_route_exist()
	{
		$router = new Router();
		$router->get('projects','/projects',['controller'=>'ProjectController','action' => 'getProjects','middlewares'=>['FirstMiddleware']]);
		$router->get('show_project','/projects/:id',['controller'=>'ProjectController','action' => 'showProject']);
		$router->get('request_ticket','/projects/:id/request_tickets',['controller'=>'RTController','action' => 'getTickets']);
		$matching_route = $router->dispatch('/projects/45/request_tickets');
		//echo 'matching route'.$matching_route->getController();
		$this->assertEquals($matching_route->getController(),'RTController');
    }
    

    public function test_get_route_url_by_name()
	{
		$router = new Router();
		$router->get('projects','/projects',['controller'=>'ProjectController','action' => 'getProjects','middlewares'=>['FirstMiddleware']]);
		$router->get('show_project','/projects/:id',['controller'=>'ProjectController','action' => 'showProject']);
		$router->get('request_ticket','/projects/:id/request_tickets',['controller'=>'RTController','action' => 'getTickets']);
		$matching_route_url = $router->generateUrl('request_ticket',[':id'=>2]);
		
		$this->assertEquals($matching_route_url,'/research/yahmi/projects/2/request_tickets');
	}

}
