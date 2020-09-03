<?php
use Yahmi\Routing\Router;
use Yahmi\Routing\Route;
use Yahmi\Routing\RequestURI;
use Yahmi\Routing\RequestURIPart;
use PHPUnit\Framework\TestCase;

class ProjectController
{
	public function getDemo(){}
	public function postDemo($demo_id,$project_id){}
}

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

	public function test_controller_routes()
	{
		$router = new Router();
		$router->controller('ProjectController');
		//fwrite(STDERR, print_r($router, TRUE));
		$matching_route_url = $router->generateUrl('project.get-demo');
		
		$this->assertEquals($matching_route_url,'/research/yahmi/project/demo');	
	}

	public function test_controller_method_parameters()
	{
		$router = new Router();
		$router->controller('ProjectController');
		//fwrite(STDERR, print_r($router->getRouteNames(), TRUE));
		$matching_route_url = $router->generateUrl('project.post-demo',[':demo_id'=>2,':project_id'=>5]);
		fwrite(STDERR, "\n\nMatching route:\n");
		fwrite(STDERR, $matching_route_url);
		$this->assertEquals($matching_route_url,'/research/yahmi/project/demo/2/5');	
	}

}
