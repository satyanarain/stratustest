<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class UserData extends Authenticatable
{


    public $timestamps = false;
    protected $table = 'users_details';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'u_phone_type', 'u_phone_detail'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


    // public function __construct() {
    //     return $this;
    // }


}
