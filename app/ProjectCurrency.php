<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class ProjectCurrency extends Authenticatable
{
    public $timestamps = false;

    protected $table = 'currency';

    protected $fillable = [
        'cur_id', 'cur_name', 'cur_symbol', 'cur_user_id', 'cur_status', 'cur_timestamp'
    ];


    // public function __construct() {           
    //     return $this;
    // }
}
