<?php
namespace Yahmi\Queue;

use Yahmi\Config\Logger;
use Yahmi\Database\CoreDataService;

/**
* This class will used to create background queue
*/

class QueueWorker extends CoreDataService
{
    const SHOULD_PROCESS_QUEUE = true;

    public function __construct() {
		    parent::__construct();
	  }
    /**
    * Get list of queue from database and process one by one
    *
    */
    public function processQueue( )
    {
        $queue_list_sql = 'SELECT id, class_name, method_name, data, priority, created_at, is_taken FROM queue';
        $queue_list = $this->executeSQL($queue_list_sql);
        //Logger::log($queue_list);
        foreach ($queue_list as $index => $queue_detail) {
            //execute Queue
            $this->executeQueue($queue_detail);
            //remove from queue
            $this->removeFromQueue($queue_detail['id']);
        }
    }
    //  [[  PRIVATE FUNCTIONS  ]]
    /**
    * Remove queue from list, will remove queue entry from database table
    * @param $queue_id
    */
    private function removeFromQueue($queue_id)
    {
        $remove_from_queue_sql = 'DELETE FROM queue WHERE id=:queue_id';

        return $this->performDBUpdate($remove_from_queue_sql,['queue_id' => $queue_id]);
    }

     /**
    *  Call Queue
    *
    *  @param $queue_detail
    *
    * @return API response
    */
    private  function executeQueue( $queue_detail )
    {
         if (self::SHOULD_PROCESS_QUEUE){
           $controller = $queue_detail['class_name'];
           $method_name = $queue_detail['method_name'];
           $parameters = ['data'=>unserialize($queue_detail['data'])];
           call_user_func_array(array($controller, $method_name), $parameters);
         }else{
             return null;
         }
    }

}