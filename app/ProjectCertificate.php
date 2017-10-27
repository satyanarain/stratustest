<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class ProjectCertificate extends Authenticatable
{
    public $timestamps = false;

    protected $table = 'project_certificate';

    protected $fillable = [
        'ci_id', 'ci_company_name', 'ci_liability_currency', 'ci_liability_limit', 'ci_liability_exp', 'ci_liability_required_min', 'ci_liability_cert_path', 'ci_work_comp_currency', 'ci_work_comp_limit', 'ci_work_comp_exp', 'ci_works_comp_include_above', 'ci_works_comp_required_min', 'ci_works_comp_not_include', 'ci_work_comp_cert_path', 'ci_auto_liability_currency', 'ci_auto_liability_limit', 'ci_auto_liability_exp', 'ci_auto_include_above', 'ci_auto_liability_required_min', 'ci_auto_liability_not_include', 'ci_auto_liability_cert_path', 'ci_umbrella_liability_currency', 'ci_umbrella_liability_limit', 'ci_umbrella_liability_exp', 'ci_umbrella_liability_cert_path', 'ci_doc_id_certificate', 'ci_project_id', 'ci_user_id', 'ci_status', 'ci_timestamp'
    ];


    // public function __construct() {           
    //     return $this;
    // }
}
