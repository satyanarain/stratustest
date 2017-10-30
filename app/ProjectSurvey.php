<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class ProjectSurvey extends Authenticatable
{
    public $timestamps = false;

    protected $table = 'project_survey';

    protected $fillable = [
        'sur_id', 'sur_number', 'sur_date', 'sur_description', 'sur_request_completion_date', 'sur_request_expedited', 'sur_request_path', 'sur_user_id', 'sur_project_id', 'sur_req_status', 'sur_timestamp'
    ];


    // public function __construct() {           
    //     return $this;
    // }
}
