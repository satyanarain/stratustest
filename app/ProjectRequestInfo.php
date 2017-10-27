<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class ProjectRequestInfo extends Authenticatable
{
    public $timestamps = false;

    protected $table = 'project_request_info';

    protected $fillable = [
        'ri_id', 'ri_number', 'ri_date', 'ri_question_request', 'ri_question_proposed', 'ri_additional_cost', 'ri_additional_cost_currency', 'ri_additional_cost_amount', 'ri_additional_day', 'ri_additional_day_add', 'ri_file_path', 'ri_user_id', 'ri_project_id', 'ri_request_status', 'ri_timestamp'
    ];


    // public function __construct() {           
    //     return $this;
    // }
}
