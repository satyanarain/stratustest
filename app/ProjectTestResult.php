<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class ProjectTestResult extends Authenticatable
{
    public $timestamps = false;

    protected $table = 'project_test_result';

    protected $fillable = [
        'tr_id', 'tr_compaction_firm', 'tr_compaction_date', 'tr_compaction_date', 'tr_compaction_location', 'tr_compaction_result', 'tr_strength_firm', 'tr_strength_date', 'tr_strength_test_num', 'tr_strength_location', 'tr_strength_result', 'tr_etc_firm', 'tr_etc_date', 'tr_etc_test_num', 'tr_etc_location', 'tr_etc_result', 'tr_project_id', 'tr_user_id', 'tr_status', 'tr_timestamp'
    ];


    // public function __construct() {           
    //     return $this;
    // }
}
