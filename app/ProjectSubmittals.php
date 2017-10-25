<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class ProjectSubmittals extends Authenticatable
{
    public $timestamps = false;

    protected $fillable = [
        'sub_id', 'sub_type', 'sub_number', 'sub_exist_parent', 'sub_rev_number', 'sub_date', 'sub_description', 'sub_specification', 'sub_additional_comments', 'sub_additional_path', 'sub_request_expedited_review', 'sub_project_id', 'sub_user_id', 'sub_status', 'sub_timestamp', 'sub_review_type'
    ];


    // public function __construct() {           
    //     return $this;
    // }
}
