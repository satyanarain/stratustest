<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class ProjectPaymentQuantityVerification extends Authenticatable
{
    public $timestamps = false;

    protected $table = 'project_payment_quantity_verification';

    protected $fillable = [
        'ppq_id', 'ppq_month_name', 'ppq_project_id','approval_status' ,'ppq_timestamp'
    ];


    // public function __construct() {           
    //     return $this;
    // }
}
