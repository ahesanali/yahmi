<?php

namespace Yahmi\Contracts\Auth;


interface User
{
	/**
     * check weather user has given permission access or not
     * @param  string  $premission_name
     * @return boolean
     */
    public function hasAccess($premission_name);

    /**
     * Get Assigned Role
     * @return Role
     */
    public function getRole();


     /**
     * Check weather user is super or not
     * @return boolean 
     */
    public function isSuper();
}