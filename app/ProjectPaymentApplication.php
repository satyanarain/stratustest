<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class ProjectPaymentApplication extends Authenticatable
{
    public $timestamps = false;

    protected $table = 'project_payment_application';

    protected $fillable = [
        'ppa_id', 'ppa_month_name', 'ppa_project_id', 'ppa_timestamp'
    ];


    // public function __construct() {           
    //     return $this;
    // }
}
