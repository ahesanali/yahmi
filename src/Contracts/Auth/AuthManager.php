<?php

namespace Yahmi\Contracts\Auth;


interface AuthManager 
{
	/**
	 * Get Authmanager instance
	 * @return [type] [description]
	 */
	public static function getInstance();

	/**
	 * Perform authentication based on credential supplied
	 * if user found set User object in session or as of now simply set user_id in session
	 * @param  string $user_id   
	 * @param  string $password  
	 * @return boolean           
	 */
	public function performAuth($user_id,$password);

	/**
	 * fetch logged in user object from session object
	 * @return User
	 */
	public function getLoggedInUser();

	/**
	*  Get logged in user role
	* @return Role
	*/	
	public function getLoggedInUserRole();

	/**
	 * check session manager for logged in user bject
	 * @return boolean 
	 */
	public function isUserLoggedIn();

	/**
	 * Logout user 
	 * Remove logged in user object from session
	 * @return
	 */
	public function logout();

	/**
	 * Change user password
	 * @param  [type] $new_password [description]
	 * @return [type]               [description]
	 */
	public function changePassword($new_password);
}