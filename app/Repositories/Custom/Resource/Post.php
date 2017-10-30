<?php
namespace App\Repositories\Custom\Resource;


class Post {

    public $id;

    public function __construct($id = null) {
       	if($id){
           $this->id = $id; 
      	}
        return $this;
    }

    /*
    public function get($user, $resource, $owner=false){				
		if (is_object($resource)) {			
	        if (!strcasecmp(get_class($resource), self::RESOURCE_POST)) {
				$role = ['admin', 'manager'];
	            if(in_array($user->getRole(), $role)){
					// if($owner){
					// 	if($user->isOwner($resource)){							
					// 		return true;
					// 	}else{
					// 		return false;
					// 	}
					// }
	                return true;
				} else {
					return false;
	           }
			}
		}
	}
	*/


	

}