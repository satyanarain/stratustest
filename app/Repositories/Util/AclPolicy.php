<?php

// namespace App\Policies;
namespace App\Repositories\Util;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User; //User Model
use App\Repositories\Custom\Resource\Post; //Post resource

class AclPolicy
{
    use HandlesAuthorization;

    const RESOURCE_POST = "App\Repositories\Custom\Resource\Post";
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     //
    // }

    // public function getRole(){
    //     return $this->role;
    // }

    public function allow_admin($user, $resource, $owner=false){    
        if (is_object($resource)) {   
            if (!strcasecmp(get_class($resource), self::RESOURCE_POST)) {
                $role = ['admin'];
                if(in_array($user->role, $role)){
                 // if($owner){
                  // if($user->isOwner($resource)){       
                  //  return true;
                  // }else{
                  //  return false;
                  // }
                 // }
                return true;
                } else {
                return false;
                }
            }
        }
    }

    public function allow_admin_user($user, $resource, $owner=false){    
        if (is_object($resource)) {   
            if (!strcasecmp(get_class($resource), self::RESOURCE_POST)) {
                if($user->id == $user->userid || $user->role == 'admin'){
                    return true; 
                }
            }
        }
    }

    public function allow_admin_contractor_user($user, $resource, $owner=false){    
        if (is_object($resource)) {   
            if (!strcasecmp(get_class($resource), self::RESOURCE_POST)) {
                if($user->role == 'admin' || $user->role == 'contractor'){
                    return true; 
                }
            }
        }
    }


    public function allow_admin_owner_user($user, $resource, $owner=false){    
        if (is_object($resource)) {   
            if (!strcasecmp(get_class($resource), self::RESOURCE_POST)) {
                if($user->role == 'admin' || $user->role == 'owner'){
                    return true; 
                }
            }
        }
    }

    

}