<?php

namespace Yahmi\Contracts\Auth;



interface Role
{
	/**
     * check weather role has given permission access or not
     * @param  string  $premission_name
     * @return boolean
     */
    public function hasAccess($premission_name);

     /**
     * Get All permission list
     * @return array
     */
    public function getAllPermissions();

    /**
     * Check weather role is super or not
     * @return boolean 
     */
    public function isSuper();
}