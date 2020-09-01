<?php
use Yahmi\Config\Config;
use PHPUnit\Framework\TestCase;

class ConfigTestCase extends TestCase
{
	//constant
	
	//Fixture method needed to for setup before test
	// public  function setUp(): void
 //    {
 //       //create config folder and 
 //       fwrite(STDOUT, __METHOD__ . "\n");
       
 //    }
	// //Fixture method needed to for setup before test
 //    public  function tearDown(): void
 //    {
 //       fwrite(STDOUT, __METHOD__ . "\n"); 
 //    }

    //Test cases started
	public function test_loading_config_file()
	{
		$config = new Config('database.php');
		$this->assertTrue(true);
	}

	public function test_config_has_key_file()
	{
		$config = new Config('app.php');
		$this->assertEquals($config->has('app_name'), true);
	}


	public function test_get_config_key()
	{
		$config = new Config('app.php');
		$this->assertEquals($config->get('app_name'), '/research/phpkit');
	}

}
