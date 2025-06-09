<?php

namespace Yahmi\Session;

/**
* This class will used to session related actiity like
*  storing session value
*  retrive stored session value
*  remove value from session
*  destroy all session data.
*/
class SessionManager
{
   public function __construct()
   {
       //Check whether session is started or not
      if (session_status() == PHP_SESSION_NONE) {
          session_start();
      }
   }
    /**
     * Store value in session.
     *
     * @param string $session_value_key
     * @param mixed  $session_value
     */
    public function store($session_value_key, $session_value)
    {
        $_SESSION[$session_value_key] = $session_value;
    }
    /**
     * check weather session has key or not
     * @param  string  $session_value_key 
     * @return boolean                    
     */
    public function has($session_value_key)
    {
        if(!is_null( $this->get($session_value_key) ) )
            return true;
        return false;
    }
    /**
     * Retrieve value from session  by key.
     *
     * @param string $session_value_key
     *
     * @return mixed
     */
    public function get($session_value_key)
    {
        if (isset($_SESSION[$session_value_key])) {
            return $_SESSION[$session_value_key];
        }

        return NULL;
    }

    /**
     * Retrieve value from session  by key and forget.
     *
     * @param string $session_value_key
     *
     * @return mixed
     */
    public function getAndRemove($session_value_key)
    {
        $sessionValue = $this->get($session_value_key);
        $this->remove($session_value_key);

        return $sessionValue;
    }

    /**
     * Remove session value by key.
     *
     * @param string $session_value_key
     */
    public function remove($session_value_key)
    {
        unset($_SESSION[$session_value_key]);
    }

    /**
     * Remove all values from session context.
     */
    public function removeAll()
    {
        session_destroy();
    }
}
