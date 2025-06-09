<?php
use Yahmi\Core\Application;
use Yahmi\Config\Config;
use PHPUnit\Framework\TestCase;

class ConfigTestCase extends TestCase
{
	//constant
	
//	Fixture method needed to for setup before test
	public  function setUp(): void
    {
		$app = new Yahmi\Core\Application(
		    "/Users/inspire/Sites/research/yahmi-app"
		);
       //create config folder and 
       fwrite(STDOUT, __METHOD__ . "\n");
       
    }
	//Fixture method needed to for setup before test
    public  function tearDown(): void
    {
       fwrite(STDOUT, __METHOD__ . "\n"); 
    }

    //Test cases started
	public function test_loading_config_file()
	{
		$config = new Config();
		$this->assertTrue(true);
	}

	public function test_config_has_key_file()
	{
		$config = new Config();
		$this->assertEquals($config->has('app.app_dir'), true);
	}


	public function test_get_config_key()
	{
		$config = new Config();
		$this->assertEquals($config->get('app.app_dir'), '/research/yahmi-app');
	}

}
