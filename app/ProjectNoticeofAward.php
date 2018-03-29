<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class ProjectNoticeofAward extends Authenticatable
{
    public $timestamps = false;

    protected $table = 'project_notice_award';

    protected $fillable = [
        'pna_envelope_id','pna_docusign_status','pna_id', 'pna_notice_status', 'pna_improvement_type', 'pna_contactor_name', 'pna_currency', 'pna_contact_amount', 'pna_award_date', 'pna_notice_path', 'pna_notice_sign', 'pna_project_id', 'pna_user_id', 'pna_status', 'pna_timestamp'
    ];


    // public function __construct() {           
    //     return $this;
    // }
}
