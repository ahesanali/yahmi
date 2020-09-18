<?php
namespace Yahmi\Auth;

use Yahmi\Database\CoreDataService;
use Yahmi\Auth\User;
use Yahmi\Contracts\Auth\AuthManager as AuthManagerContract;
use stdClass;

class AuthManager extends CoreDataService implements AuthManagerContract
{
	/**
	 * holds logged in user object
	 * @var User
	 */
	private $user;

	private $sessionManager;

	public function __construct()
	{
		parent::__construct();

		$this->sessionManager = app('session_manager');
		$this->user = null;
	}
	
	public static function getInstance()
	{
		static $authManager;
		
		if(is_null($authManager))
			$authManager = new AuthManager();
		return  $authManager;
	}
	// public function registerUser($user_id,$password,$first_name,$last_name,$role_id)
	// {
	// 	 create new user based on given argument
	// 	 return created user object
	// }
	/**
	 * Perform authentication based on credential supplied
	 * if user found set User object in session or as of now simply set user_id in session
	 * @param  string $user_id   
	 * @param  string $password  
	 * @return boolean           
	 */
	public function performAuth($user_id,$password)
	{
		$app_name = config('app.app_dir');

		$auth_sql = 'SELECT id, First_Name, Last_Name, User_Id, Password, User_Role_id FROM user_master WHERE 
					 User_Id=:user_id';
		$user = $this->executeSQL($auth_sql,['user_id' => $user_id],true);
		if(!is_null($user) || !empty($user))
		{
			if (password_verify($password, $user['Password'])) 
			{
				//User is successfully logged in all auth params are right
				//store user object inside session
				
				// prepare user object
				$this->user = new stdClass();
				$this->user->id  =  $user['id'];
				$this->user->firstName = $user['First_Name'];
				$this->user->lastName = $user['Last_Name'];
				$this->user->userId= $user['User_Id'];
				$this->user->password = $user['Password'];
				$this->user->userRoleId = $user['User_Role_id'];

				//store user object
				$this->sessionManager->store('logged_in_user',$this->user);
				$this->sessionManager->store('app_name',$app_name);
				return true;
			}else{
				return false;
			}
		}
		return false;			 
	}
	/**
	 * fetch logged in user object from session object
	 * @return User
	 */
	public function getLoggedInUser()
	{	
		$logged_in_user_obj = (array) $this->sessionManager->get('logged_in_user');
		$logged_in_user = new User($logged_in_user_obj);
		return $logged_in_user;
	}
	/**
	*  Get logged in user role
	* @return Role
	*/	
	public function getLoggedInUserRole()
	{
		$logged_in_user = $this->getLoggedInUser();
		return $logged_in_user->getRole();
	}
	/**
	 * check session manager for logged in user bject
	 * @return boolean 
	 */
	public function isUserLoggedIn()
	{
		$app_name = config('app.app_dir');
		
		return ( $this->sessionManager->has('logged_in_user') && ($this->sessionManager->get('app_name') == $app_name) );		
	}

	/**
	 * Logout user 
	 * Remove logged in user object from session
	 * @return
	 */
	public function logout()
	{
		$this->sessionManager->removeAll();
	}
	public function changePassword($new_password)
	{
		$logged_in_user = $this->getLoggedInUser();
		
		$new_password = password_hash($new_password,PASSWORD_BCRYPT);
		$change_password_sql = 'Update user_master SET password=:new_password WHERE id=:User_Id';
		
		
		return $this->performDBUpdate($change_password_sql,['new_password'=>$new_password,'User_Id'=>$logged_in_user->id]);
		
	}
}