<?php
namespace Yahmi\Queue;

use Yahmi\Database\CoreDataService;

/**
* This class will used to create background queue
*/

class Queue extends CoreDataService
{
    const QUEUE_PRIORITY_HIGH = 1;
    const QUEUE_PRIORITY_MEDIUM = 2;
    const QUEUE_PRIORITY_LOW = 3;

    public function __construct() {
		    parent::__construct();
	  }

    public function addToQueue( $queue_data )
    {
        $add_to_queue_sql = 'INSERT INTO queue(id, class_name, method_name, data, priority, created_at, is_taken) VALUES (NULL, :class_name, :method_name, :data, :priority, CURRENT_TIMESTAMP, 0)';
        $add_to_queu_param = [
          'class_name' => $queue_data['class_name'],
          'method_name' => $queue_data['method_name'],
          'data' => $queue_data['data'],
          'priority' => $queue_data['priority']
        ];
        return $this->performDBUpdate($add_to_queue_sql,$add_to_queu_param);
    }
}