<?php

namespace  Yahmi\Log;

use Yahmi\Config\Config;
/**
* This class will used to create  log during application lifecycle.
*/

class Logger
{
   private  $logFile = __DIR__."/../../../app.log";

   private  $fileHandle = NULL;


   public function __construct()
   {
     	//open file
        $config = new Config('app.php');
        $application_root =  getcwd(); 
        $this->logFile = $application_root.'/'.$config->get('log_file');
     	$this->fileHandle = fopen($this->logFile, "a");
     	if($this->fileHandle === FALSE || $this->fileHandle === NULL)
     			throw new \Exception("Unable to open log file: ".$this->logFile.".");


   }

   public function __destruct()
   {
   		fclose($this->fileHandle);
   }

   /**
    * Log text in log file
    *
    *
    * @param unknown $log_text
    */
   public static function log( $log_text )
   {

     	static  $loggerInstance;
     	//checking if instance is already created than pass to next operation
     	if (is_null($loggerInstance)) {
     		$loggerInstance = new Logger();
     	}
      //write text in to file
     	$result_text ="[".date("d-m-Y H:i:s")."]";
     	$result_text .= print_r($log_text, true);
        fwrite($loggerInstance->fileHandle, $result_text."\n");

   }
}
