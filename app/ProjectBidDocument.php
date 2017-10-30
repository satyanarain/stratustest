<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class ProjectBidDocument extends Authenticatable
{
    public $timestamps = false;

    protected $fillable = [
        'bd_id', 'bd_type_of_improvement', 'bd_lead_agency', 'bd_bid_advertisement_date', 'bd_add_applicable', 'bd_bid_advertisement_date', 'bd_add_applicable', 'bd_invite_date', 'bd_invite_applicable', 'bd_invite_applicable', 'bd_date_of_opening', 'bd_addvertisement_of_bid_path', 'bd_notice_invite_bid_path', 'bd_detail_result_path', 'bd_low_bidder_name', 'bd_sucessful_bidder_proposal_path', 'bd_project_id', 'bd_user_id', 'bd_status', 'bd_timestamp'
    ];


    // public function __construct() {           
    //     return $this;
    // }
}
