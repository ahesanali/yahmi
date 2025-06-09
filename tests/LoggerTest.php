<?php
use Yahmi\Log\Logger;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
	public function test_logfile_creation()
	{
		Logger::log('Hello');
		$this->assertTrue(true);
    }
}    