<?php
namespace Yahmi\Middleware;

abstract class Middleware
{
   public function __construct()
   {

   }
   /**
    * Run Middleware
    * @param  Array  $params  
    * @return [type]           
    */
   abstract public function run($params = NULL);
}
