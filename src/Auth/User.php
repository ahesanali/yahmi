<?php
namespace Yahmi\Auth;

use Yahmi\CoreDataService;
use Yahmi\Auth\Role;
use stdClass;

class User extends CoreDataService
{
    /**
     * Assign below dynamic properties to class
     * 
     * @property int $id [Database record id of user object]
     * @property string $firstName [First Name of user]
     * @property string $lastName [Last Name of user]
     * @property string $userId [User Id of user, example mobile no, or email id]
     * @property string $password [hashed password of user]
     * @property int $userRoleId [Role Id of user]
     * 
     * @param array $attributes [assign this array values to above mentioned properties]
     */
    public function __construct(array $attributes)
    {
        parent::__construct();
        foreach ($attributes as $attribute_key => $attribute_value) {
            $this->$attribute_key =   $attribute_value;     
        }
      
    }
    /**
     * check weather user has given permission access or not
     * @param  string  $premission_name
     * @return boolean
     */
    public function hasAccess($premission_name)
    {
        $role = $this->getRole();

        return $role->hasAccess($premission_name);
    }

    /**
     * Get Assigned Role
     * @return Role
     */
    public function getRole()
    {
        $find_role_sql = 'SELECT Role_Id,Role,Permissions,Is_Super FROM users_view WHERE User_Id=:User_Id';
        $role_obj = $this->executeSQL($find_role_sql,['User_Id' => $this->userId],true);
        if(!is_null($role_obj)){
            $role = new stdClass();
            $role->roleId = $role_obj['Role_Id'];
            $role->role = $role_obj['Role'];
            $role->permissions = $role_obj['Permissions'];
            $role->isSuper = ($role_obj['Is_Super']==1) ? true :  false;
        }

        return  new Role((array)$role);
    }

    /**
     * Check weather user is super or not
     * @return boolean 
     */
    public function isSuper()
    {
        $role = $this->getRole();

        return $role->isSuper();
    }

}
