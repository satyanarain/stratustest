<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class ProjectDailyQuantityCompleted extends Authenticatable
{
    public $timestamps = false;

    protected $table = 'project_daily_quantity_completed';

    protected $fillable = [
        'pdq_id', 'pdq_report_id', 'pdq_item_id', 'pdq_qty_complete_this_day', 'pdq_location_additional_information', 'pdq_project_id', 'pdq_user_id', 'pdq_timestamp'
    ];


    // public function __construct() {           
    //     return $this;
    // }
}
