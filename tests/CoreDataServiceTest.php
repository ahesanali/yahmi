<?php
use Yahmi\Database\CoreDataService;
use PHPUnit\Framework\TestCase;

Class MyDBService extends CoreDataService{}

class CoreDataServiceTest extends TestCase
{
	public function test_db_coonection()
	{
		$myDBService = new MyDBService();
		$this->assertTrue(true);
    }

    public function test_build_insert_query()
	{
		$myDBService = new MyDBService();
		$project_params = ['Project_Name'=>'Sample project','Description'=>'Sample desc','Status'=>true];
		$insert_query = $myDBService->buildInsertQuery('projects',$project_params);
		$this->assertEquals($insert_query,"INSERT INTO projects (Project_Name,Description,Status) VALUES (:Project_Name,:Description,:Status)");
    }

    public function test_build_update_query()
	{
		$myDBService = new MyDBService();
		$project_params = ['Project_Name'=>'Sample project','Description'=>'Sample desc','Status'=>true];
		$update_query = $myDBService->buildUpdateQuery('projects',$project_params,'id=:project_id');
		$this->assertEquals($update_query,"UPDATE projects SET Project_Name=:Project_Name,Description=:Description,Status=:Status WHERE id=:project_id");
    }
}    