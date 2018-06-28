<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class ProjectWeeklyReports extends Authenticatable
{
    public $timestamps = false;

    protected $table = 'project_weekly_reports';

    protected $fillable = [
        'pwr_id', 'pwr_project_id', 'pwr_contractor_name', 'pwr_week_ending', '	pwr_total_calender_day_in_contract', 'pwr_time_extension','pwr_day_this_report_calender_day', 'pwr_day_this_report_non_calender_day', 'pwr_day_previous_report_calender_day', 'pwr_day_previous_report_non_calender_day', 'pwr_remarks', 'pwr_type_name', 'pwr_status', 'pwr_timestamp','report_type'
    ];


    // public function __construct() {           
    //     return $this;
    // }
}
