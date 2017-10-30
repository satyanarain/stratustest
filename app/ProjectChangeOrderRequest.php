<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class ProjectChangeOrderRequest extends Authenticatable
{
    public $timestamps = false;

    protected $table = 'project_change_order_request';

    protected $fillable = [
        'pco_id', 'pco_number', 'pco_date', 'pco_contractor_name', 'pco_rfi', 'pco_order_status', 'pco_project_id', 'pco_user_id', 'pco_status', 'pco_timestamp'
    ];

    // public function __construct() {           
    //     return $this;
    // }
}
