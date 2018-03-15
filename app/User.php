<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class User extends Authenticatable
{

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_image_path','username', 'email', 'password', 'first_name', 'last_name', 'company_name', 'position_title', 'phone_number', 'status', 'role', 'user_parent'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    // public function __construct() {           
    //     return $this;
    // }

    public function isOwner($resource){
        return $this->id == $resource->id;
    }
    
    public function getRole(){
        return $this->role;
    }
}
