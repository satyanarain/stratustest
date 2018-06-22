<?php
namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Http\Requests;
use App\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Redirect;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;
use App;
use Session;

use Gate;
use App\User; //your model
use App\Document; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post;
use App\Repositories\Util\AclPolicy;


class NotificationController extends Controller {


    /*
    --------------------------------------------------------------------------
     Add Notifications
    --------------------------------------------------------------------------
    */
    public function add_notification($notification_title, $link, $project_id, $receiver_user_id)
    {
        try
        {    
            $description     = $notification_title;
            $link            = $link;
            $project_id      = $project_id;
            $receiver_user_id= $receiver_user_id;
            $status          = '1';
            if(Auth::user())
                $sender_user_id  = Auth::user()->id;
            else
                $sender_user_id  = 1;

            $information = array(

                "pn_description"      => $description,
                "pn_link"             => $link,
                "pn_status"           => $status,
                "pn_project_id"       => $project_id,
                "pn_user_id"          => $sender_user_id,
                "pn_receiver_user_id" => $receiver_user_id

            );
            $rules = [
                "pn_description"      => 'required',
                "pn_link"             => 'required',
                "pn_status"           => 'required|numeric',
                "pn_project_id"       => 'required|numeric',
                "pn_user_id"          => 'required|numeric',
                "pn_receiver_user_id" => 'required|numeric'
            ];

            $validator = Validator::make($information, $rules);
            if ($validator->fails())
            {
                return $result = response()->json(["data" => $validator->messages()],400);
            }
            else
            {
                $query = DB::table('project_notifications')
                ->insert(['pn_description' => $description, 'pn_link' => $link, 'pn_status' => $status, 'pn_project_id' => $project_id, 'pn_sender_user_id' => $sender_user_id, 'pn_receiver_user_id' => $receiver_user_id]);
                if(count($query) < 1)
                {
                  $result = array('code'=>400, "description"=>"No records found");
                  return response()->json($result, 400);
                }
                else
                {
                  $result = array('description'=>"Add notification successfully",'code'=>200);
                  return response()->json($result, 200);
                }
            }
        }
        catch(Exception $e)
        {
            return response()->json(['error' => 'Something is wrong'], 500);
        }
    }

  /*
  --------------------------------------------------------------------------
   Get Notification Short by passing user_id
  --------------------------------------------------------------------------
  */
  public function get_notification_short(Request $request, $user_id)
  {
    try
    {
        $query1 = DB::table('project_notifications')
        ->leftJoin('projects', 'project_notifications.pn_project_id', '=', 'projects.p_id')
        ->leftJoin('users', 'project_notifications.pn_sender_user_id', '=', 'users.id')
        ->select('project_notifications.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        // ->where('pn_receiver_user_id', '=', $user_id)
        ->where('pn_receiver_user_id', '=', $user_id)
        ->orderBy('pn_id', 'desc')
        ->limit(5)
        ->get();
        if(count($query1) < 1)
        {
          $result = array('code'=>404, "description"=>"No Records Found");
          return response()->json($result, 404);
        }
        else
        {
          foreach($query1 as $data)
          {
              //echo $data->pn_timestamp;die;
              $data->pn_timestamp = date("Y-m-d h:i:s", strtotime($data->pn_timestamp));
              $query[] = $data;
          }
          $result = array('data'=>$query,'code'=>200);
          return response()->json($result, 200);
        }
    }
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }

  /*
  --------------------------------------------------------------------------
   Get Notification All by passing user_id
  --------------------------------------------------------------------------
  */
  public function get_notification_all(Request $request, $user_id)
  {
    try
    {
        $query1 = DB::table('project_notifications')
        ->leftJoin('projects', 'project_notifications.pn_project_id', '=', 'projects.p_id')
        ->leftJoin('users', 'project_notifications.pn_sender_user_id', '=', 'users.id')
        ->select('project_notifications.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('pn_receiver_user_id', '=', $user_id)
        ->orderBy('pn_id', 'desc')
        ->get();
        if(count($query1) < 1)
        {
          $result = array('code'=>404, "description"=>"No Records Found");
          return response()->json($result, 404);
        }
        else
        {
          foreach($query1 as $data)
          {
              //echo $data->pn_timestamp;die;
              $data->pn_timestamp = date("Y-m-d h:i:s", strtotime($data->pn_timestamp));
              $query[] = $data;
          }
          $result = array('data'=>$query,'code'=>200);
          return response()->json($result, 200);
        }
    }
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }


  /*
  --------------------------------------------------------------------------
   Get Notification Unread by passing user_id
  --------------------------------------------------------------------------
  */
  public function get_notification_unread(Request $request, $user_id)
  {
    try
    {
        $query = DB::table('project_notifications')
        ->select(DB::raw('count(pn_status) as unread_count'))
        ->where('pn_receiver_user_id', '=', $user_id)
        ->where('pn_status', '=', 1)
        ->orderBy('pn_id', 'desc')
        ->get();
        if(count($query) < 1)
        {
          $result = array('code'=>404, "description"=>"No Records Found");
          return response()->json($result, 404);
        }
        else
        {
          $result = array('data'=>$query,'code'=>200);
          return response()->json($result, 200);
        }
    }
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }

  /*
  --------------------------------------------------------------------------
   Update Notification status by passing pn_id
  --------------------------------------------------------------------------
  */
  public function change_notification_status(Request $request, $pn_id)
  {
    try
    {
        $query = DB::table('project_notifications')
        ->where('pn_id', '=', $pn_id)
        ->update(['pn_status' => 0]);
        if(count($query) < 1)
        {
          $result = array('code'=>404, "description"=>"No Records Found");
          return response()->json($result, 404);
        }
        else
        {
          $result = array('data'=>$query,'code'=>200);
          return response()->json($result, 200);
        }
    }
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }
  
  /*
  --------------------------------------------------------------------------
   Send Notification via Cron
  --------------------------------------------------------------------------
  */
    public function send_notification(Request $request)
    {
        //if (php_sapi_name() !='cli') exit;
        //if ($_SERVER['HTTP_USER_AGENT'] != 'stratuscm.con') exit;
        //->whereRaw('date(project_preconstruction_meeting_documents.pm_date) < ?',[date("Y-m-d")])
        
        /*----------------------------Pre--Certs of Insurance-------------------------------------------------*/
        
        //$WeekAgo = strtotime("-1 week"); //A week before today
        //$MonthAgo = strtotime("-1 month"); //A month before today
        //DB::enableQueryLog();
        $records = DB::table('project_certificate')
        ->leftJoin('project_firm', 'project_certificate.ci_company_name', '=', 'project_firm.f_id')
        //->leftJoin('currency as liability_currency', 'project_certificate.ci_liability_currency', '=', 'liability_currency.cur_id')
        //->leftJoin('documents as liability_cert_path', 'project_certificate.ci_liability_cert_path', '=', 'liability_cert_path.doc_id')
        //->leftJoin('currency as work_comp_currency', 'project_certificate.ci_work_comp_currency', '=', 'work_comp_currency.cur_id')
        //->leftJoin('documents as work_comp_cert_path', 'project_certificate.ci_work_comp_cert_path', '=', 'work_comp_cert_path.doc_id')
        //->leftJoin('currency as auto_liability_currency', 'project_certificate.ci_auto_liability_currency', '=', 'auto_liability_currency.cur_id')
        //->leftJoin('documents as auto_liability_cert_path', 'project_certificate.ci_auto_liability_cert_path', '=', 'auto_liability_cert_path.doc_id')
        //->leftJoin('currency as umbrella_liability_symbol', 'project_certificate.ci_umbrella_liability_currency', '=', 'umbrella_liability_symbol.cur_id')
       //->leftJoin('documents as umbrella_liability', 'project_certificate.ci_umbrella_liability_cert_path', '=', 'umbrella_liability.doc_id')
        //->leftJoin('documents as doc_cert_path', 'project_certificate.ci_doc_id_certificate', '=', 'doc_cert_path.doc_id')
        ->leftJoin('projects', 'project_certificate.ci_project_id', '=', 'projects.p_id')
        ->leftJoin('users', 'project_certificate.ci_user_id', '=', 'users.id')
        ->select('project_firm.f_name as agency_name', 
          'project_certificate.ci_id', 
          //'liability_currency.cur_symbol as liability_currency', 
          'project_certificate.ci_liability_limit as liability_limit', 
          'project_certificate.ci_liability_exp as liability_exp', 
          'project_certificate.ci_liability_required_min as liability_required_min', 
          //'liability_cert_path.doc_path as liability_cert_path', 
          //'work_comp_currency.cur_symbol as work_comp_currency',
          //'project_certificate.ci_work_comp_limit as work_comp_limit', 
          'project_certificate.ci_work_comp_exp as work_comp_exp', 
          'project_certificate.ci_works_comp_required_min as works_comp_required_min', 
          //'work_comp_cert_path.doc_path as work_comp_cert_path', 
          //'auto_liability_currency.cur_symbol as auto_liability_currency',
          //'project_certificate.ci_auto_liability_limit as auto_liability_limit', 
          //'project_certificate.ci_auto_liability_exp as auto_liability_exp', 
          //'project_certificate.ci_auto_liability_required_min as auto_liability_required_min', 
          //'auto_liability_cert_path.doc_path as auto_liability_cert_path', 
          //'umbrella_liability_symbol.cur_symbol as umbrella_liability_symbol', 
          //'project_certificate.ci_umbrella_liability_limit as umbrella_liability_limit', 
          //'project_certificate.ci_umbrella_liability_exp as umbrella_liability_exp', 
          //'umbrella_liability.doc_path as umbrella_liability_cert_path',
          //'doc_cert_path.doc_path as doc_cert_path',  
          'projects.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role', 
          'project_certificate.ci_status as status', 
          'project_certificate.ci_timestamp as timestamp')
        ->whereRaw('date(project_certificate.ci_liability_exp) = ?',[date("Y-m-d",strtotime("+1 month"))])
        ->orWhereRaw('date(project_certificate.ci_work_comp_exp) = ?',[date("Y-m-d",strtotime("+1 month"))])
        ->orWhereRaw('date(project_certificate.ci_auto_liability_exp) = ?',[date("Y-m-d",strtotime("+1 month"))])
        //->orwhereRaw('date(project_certificate.ci_liability_exp) < ?',[date("Y-m-d")])
        //->orWhereRaw('date(project_certificate.ci_work_comp_exp) < ?',[date("Y-m-d")])
        //->orWhereRaw('date(project_certificate.ci_auto_liability_exp) < ?',[date("Y-m-d")])
        ->get();
        //dd(DB::getQueryLog());
        //echo '<pre>';print_r($records);die;
        //echo 'hi';
        foreach ($records as $check_project_user) {
        $user_id              = $check_project_user->p_user_id;
        $permission_key       = 'certificate_view_all';
        $notification_key     = 'certificates_of_insurance';
        $project_id           = $check_project_user->p_id;
        $notification_title   = 'Notification for Certificate of Insurance expiration in Project: ' .$check_project_user->p_name;
        $url                  = App::make('url')->to('/');
        $link                 = "/dashboard/".$project_id."/certificate/".$check_project_user->ci_id;
        $date                 = date("M d, Y h:i a");
        $email_description    = 'This is a notification for Certificate of Insurance expiration. Please upload a non-expired Certificate of Insurance as soon as possible to avoid any liability. <br><br> <a href="'.$url.$link.'"> Click Here to see </a>';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        //echo '<pre>';print_r($check_single_user_permission);die;
        if(count($check_single_user_permission) < 1){
          continue;
        }
        else {
          //echo '<pre>';print_r($records);die;
            //echo '<pre>';print_r($check_single_user_permission);die;
            $check_project_user_notification = app('App\Http\Controllers\Projects\PermissionController')->check_project_user_notification($project_id,$user_id,$notification_key);
            //echo '<pre>';print_r($check_project_user_notification);die;
            if(count($check_project_user_notification) < 1){
              continue;
            }else{
                $project_notification_query = app('App\Http\Controllers\Projects\NotificationController')->add_notification($notification_title, $link, $project_id, $check_single_user_permission[0]->pup_user_id);
                //echo '<pre>';print_r($check_project_user);die;
                $user_detail = array(
                  'id'              => $check_project_user->p_user_id,
                  'name'            => $check_project_user->user_name,
                  'email'           => $check_project_user->user_email,
                  'link'            => $link,
                  'date'            => $date,
                  'project_name'    => $check_project_user->p_name,
                  'title'           => $notification_title,
                  'description'     => $email_description
                );
                $user_single = (object) $user_detail;
                Mail::send('emails.send_notification',['user' => $user_single], function ($message) use ($user_single) {
                    $message->from('no-reply@sw.ai', 'StratusCM');
                    $message->to($user_single->email, $user_single->name)->subject($user_single->title);
                });
            }
        }
      } // End Foreach
      
      
      
      
      
      
      /*----------------------------Pre--Expired Certs of Insurance-------------------------------------------------*/
      
      $records = DB::table('project_certificate')
        ->leftJoin('project_firm', 'project_certificate.ci_company_name', '=', 'project_firm.f_id')
        ->leftJoin('projects', 'project_certificate.ci_project_id', '=', 'projects.p_id')
        ->leftJoin('users', 'project_certificate.ci_user_id', '=', 'users.id')
        ->select('project_firm.f_name as agency_name', 
          'project_certificate.ci_id', 
          'project_certificate.ci_liability_limit as liability_limit', 
          'project_certificate.ci_liability_exp as liability_exp', 
          'project_certificate.ci_liability_required_min as liability_required_min', 
          'project_certificate.ci_work_comp_exp as work_comp_exp', 
          'project_certificate.ci_works_comp_required_min as works_comp_required_min', 
          'projects.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role', 
          'project_certificate.ci_status as status', 
          'project_certificate.ci_timestamp as timestamp')
        ->whereRaw('date(project_certificate.ci_liability_exp) < ?',[date("Y-m-d")])
        ->orWhereRaw('date(project_certificate.ci_work_comp_exp) < ?',[date("Y-m-d")])
        ->orWhereRaw('date(project_certificate.ci_auto_liability_exp) < ?',[date("Y-m-d")])
        ->get();
        //dd(DB::getQueryLog());
        //echo '<pre>';print_r($records);die;
        //echo 'hi';
        foreach ($records as $check_project_user) {
        $user_id              = $check_project_user->p_user_id;
        $permission_key       = 'certificate_view_all';
        $notification_key     = 'certificates_of_insurance';
        $project_id           = $check_project_user->p_id;
        $notification_title   = 'Notification for Certificate of Insurance expiration in Project: ' .$check_project_user->p_name;
        $url                  = App::make('url')->to('/');
        $link                 = "/dashboard/".$project_id."/certificate/".$check_project_user->ci_id."/update";
        $date                 = date("M d, Y h:i a");
        $email_description    = 'This is a notification for Certificate of Insurance expiration. Please upload a non-expired Certificate of Insurance as soon as possible to avoid any liability. <br><br> <a href="'.$url.$link.'"> Click Here to see </a>';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        //echo '<pre>';print_r($check_single_user_permission);die;
        if(count($check_single_user_permission) < 1){
          continue;
        }
        else {
          //echo '<pre>';print_r($records);die;
            //echo '<pre>';print_r($check_single_user_permission);die;
            $check_project_user_notification = app('App\Http\Controllers\Projects\PermissionController')->check_project_user_notification($project_id,$user_id,$notification_key);
            //echo '<pre>';print_r($check_project_user_notification);die;
            if(count($check_project_user_notification) < 1){
              continue;
            }else{
                $project_notification_query = app('App\Http\Controllers\Projects\NotificationController')->add_notification($notification_title, $link, $project_id, $check_single_user_permission[0]->pup_user_id);
                //echo '<pre>';print_r($check_project_user);die;
                $user_detail = array(
                  'id'              => $check_project_user->p_user_id,
                  'name'            => $check_project_user->user_name,
                  'email'           => $check_project_user->user_email,
                  'link'            => $link,
                  'date'            => $date,
                  'project_name'    => $check_project_user->p_name,
                  'title'           => $notification_title,
                  'description'     => $email_description
                );
                $user_single = (object) $user_detail;
                Mail::send('emails.send_notification',['user' => $user_single], function ($message) use ($user_single) {
                    $message->from('no-reply@sw.ai', 'StratusCM');
                    $message->to($user_single->email, $user_single->name)->subject($user_single->title);
                });
            }
        }
      } // End Foreach
        
        
        
        
        /*--------------------------------------Pre--Bidding----------------------------------------------------*/
        
        
        $bids = DB::table('project_bid_documents')
        //->leftJoin('project_firm as lead_agency', 'project_bid_documents.bd_lead_agency', '=', 'lead_agency.f_id')
        //->leftJoin('projects as type_of_improvement', 'project_bid_documents.bd_type_of_improvement', '=', 'type_of_improvement.p_id')
        // ->leftJoin('documents as upload_addendum_path', 'project_bid_documents.bd_upload_addendum_path', '=', 'upload_addendum_path.doc_id')
        //->leftJoin('documents as addvertisement_bid_path', 'project_bid_documents.bd_addvertisement_of_bid_path', '=', 'addvertisement_bid_path.doc_id')
        //->leftJoin('documents as detail_result_path', 'project_bid_documents.bd_detail_result_path', '=', 'detail_result_path.doc_id')
        //->leftJoin('documents as notice_invite_bid_path', 'project_bid_documents.bd_notice_invite_bid_path', '=', 'notice_invite_bid_path.doc_id')
        //->leftJoin('project_firm as low_bidder_name', 'project_bid_documents.bd_low_bidder_name', '=', 'low_bidder_name.f_id')
        //->leftJoin('documents as sucessful_bidder_proposal_path', 'project_bid_documents.bd_sucessful_bidder_proposal_path', '=', 'sucessful_bidder_proposal_path.doc_id')
        ->leftJoin('projects', 'project_bid_documents.bd_project_id', '=', 'projects.p_id')
        ->leftJoin('users', 'project_bid_documents.bd_user_id', '=', 'users.id')
        ->select('project_bid_documents.*', 'project_bid_documents.bd_id as bd_id', 'project_bid_documents.bd_status as bd_status',
        //'lead_agency.f_name as lead_agency', 
        //'type_of_improvement.p_description as type_of_improvement', 
        // 'upload_addendum_path.doc_path as upload_addendum_path', 
        //'addvertisement_bid_path.doc_path as addvertisement_bid_path', 
        //'detail_result_path.doc_path as detail_result_path', 
        //'notice_invite_bid_path.doc_path as notice_invite_bid_path', 
        //'low_bidder_name.f_name as low_bidder_name',
        //'sucessful_bidder_proposal_path.doc_path as sucessful_bidder_proposal_path', 
        'projects.*',
        'users.username', 'users.email', 'users.first_name', 'users.last_name as user_lastname','users.id as user_id', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->whereRaw('date(project_bid_documents.bd_date_of_opening) = ?',[date('Y-m-d')])
        ->get();
        foreach($bids as $check_project_user)
        {
            $notification_key     = 'bidding';
            $check_project_user_notification = app('App\Http\Controllers\Projects\PermissionController')->check_project_user_notification($check_project_user->p_id,$check_project_user->user_id,$notification_key);
            if(count($check_project_user_notification) < 1){
              continue;
            }else{
                $project_id           = $check_project_user->p_id;
                $notification_title   = 'Bid for ' .$check_project_user->p_name.' Project starts today.';
                $url                  = App::make('url')->to('/'); 
                $link                 = "/dashboard/".$project_id."/bid_documents/".$check_project_user->bd_id;
                $date                 = date("M d, Y h:i a");
                $email_description    = 'The Bid for the <strong>'.$check_project_user->p_name.'</strong> Project starts today. <a href="'.$url.$link.'"> Click Here to see </a>';
                $user_detail = array(
                  'id'              => $check_project_user->bd_id,
                  'name'            => $check_project_user->username,
                  'email'           => $check_project_user->email,
                  'link'            => $link,
                  'date'            => $date,
                  'project_name'    => $check_project_user->p_name,
                  'title'           => $notification_title,
                  'description'     => $email_description
                );
                $user_single = (object) $user_detail;
                Mail::send('emails.send_notification',['user' => $user_single], function ($message) use ($user_single) {
                    $message->from('no-reply@sw.ai', 'StratusCM');
                    $message->to($user_single->email, $user_single->name)->subject($user_single->title);
                });
            }
        }
//        
        /*------------------------------During Construction-------RFIs------------------------------------------*/
        
        /*------------------------------During Construction-------Submittals EOR Approval-----------------------*/
        
        /*------------------------------During Construction-------Surveys-Cut sheets----------------------------*/
        
        /*------------------------------During Construction-------Weekly Statement of Contract Days-------------*/
        
        /*------------------------------During Construction-------Pay Applications------------------------------*/
        
        /*------------------------------During Construction-------Change Orders- Execution----------------------*/
        
        
        /*------------------------------During Construction-------Labor Compliance------------------------------*/
        
        /*------------------------------During Construction-------Meeting Minutes-------------------------------*/
        
        $meetings = DB::table('project_preconstruction_meeting_documents')
        //->leftJoin('project_firm as contractor_name', 'project_preconstruction_meeting_documents.pm_contractor_id', '=', 'contractor_name.f_id')
        //->leftJoin('project_firm as contractor_description', 'project_preconstruction_meeting_documents.pm_description', '=', 'contractor_description.f_id')
        //->leftJoin('documents as agenda_path', 'project_preconstruction_meeting_documents.pm_agenda_path', '=', 'agenda_path.doc_id')
        //->leftJoin('documents as signin_sheet_path', 'project_preconstruction_meeting_documents.pm_signin_sheet_path', '=', 'signin_sheet_path.doc_id')
        //->leftJoin('documents as meeting_minutes_path', 'project_preconstruction_meeting_documents.pm_meeting_minutes_path', '=', 'meeting_minutes_path.doc_id')
        ->leftJoin('projects', 'project_preconstruction_meeting_documents.pm_project_id', '=', 'projects.p_id')
        ->leftJoin('users', 'project_preconstruction_meeting_documents.pm_user_id', '=', 'users.id')
        ->select(//'contractor_name.f_name as contractor_name',
          //'contractor_description.f_name as contractor_description',
          //'agenda_path.doc_path as agenda_path',  
          //'signin_sheet_path.doc_path as signin_sheet_path',  
          //'meeting_minutes_path.doc_path as meeting_minutes_path',  
          'project_preconstruction_meeting_documents.*', 'projects.*', 
          'users.username', 'users.email','users.id as user_id','users.first_name', 'users.last_name', 'users.company_name', 'users.phone_number', 'users.status', 'users.role')
        ->whereRaw('date(project_preconstruction_meeting_documents.pm_date) = ?',[date("Y-m-d",strtotime("+10 day"))])
        ->get();
        //echo '<pre>';print_r($meetings);die;
        //echo date("Y-m-d",strtotime("+10 day"));die;
        foreach($meetings as $check_project_user)
        {
            $notification_key     = 'meeting_minutes';
            $check_project_user_notification = app('App\Http\Controllers\Projects\PermissionController')->check_project_user_notification($check_project_user->p_id,$check_project_user->user_id,$notification_key);
            if(count($check_project_user_notification) < 1){
              continue;
            }else{
                $project_id           = $check_project_user->p_id;
                $notification_title   = 'Preconstruction Meeting Notification in Project: ' .$check_project_user->p_name;
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/minutes_meeting";
                $date                 = date("M d, Y h:i a");
                $email_description    = 'Preconstruction meeting is scheduled on '.$check_project_user->pm_date.' in <strong>'.$check_project_user->p_name.'</strong> Project.<br><a href="'.$url.$link.'"> Click Here to see </a>';
                $user_detail = array(
                  'id'              => $check_project_user->p_id,
                  'name'            => $check_project_user->username,
                  'email'           => $check_project_user->email,
                  'link'            => $link,
                  'date'            => $date,
                  'project_name'    => $check_project_user->p_name,
                  'title'           => $notification_title,
                  'description'     => $email_description
                );
                $user_single = (object) $user_detail;
                Mail::send('emails.send_notification',['user' => $user_single], function ($message) use ($user_single) {
                    $message->from('no-reply@sw.ai', 'StratusCM');
                    $message->to($user_single->email, $user_single->name)->subject($user_single->title);
                });
            }
        }
        
        /*------------------------------Post Construction---------Unconditional Finals--------------------------*/
        
        /*------------------------------Post Construction---------As-Builts-Final Walk--------------------------*/
        
        /*------------------------------Post Construction---------Notice of Completion--------------------------*/
        $notices = DB::table('project_notice_of_completion')
          ->leftJoin('documents', 'project_notice_of_completion.noc_file_path', '=', 'documents.doc_id')
          ->leftJoin('projects', 'project_notice_of_completion.noc_project_id', '=', 'projects.p_id')
          ->leftJoin('users', 'project_notice_of_completion.noc_user_id', '=', 'users.id')
          ->select('project_notice_of_completion.*', 'documents.*', 'projects.*', 'users.username as user_name','users.id as user_id', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->whereRaw('date(project_notice_of_completion.date_noc_filed) = ?',[date("Y-m-d",strtotime("+1 month"))])
          ->orWhereRaw('date(project_notice_of_completion.date_noc_filed) = ?',[date("Y-m-d",strtotime("+2 month"))])
          ->get();
        //echo '<pre>';        print_r($notices);die;
        // Start Check User Permission and send notification and email  
        // Check User Project Permission  
        foreach ($notices as $check_project_user) {
          // Check User Permission Parameter 
          $user_id              = $check_project_user->p_user_id;
          $permission_key       = 'notice_completion_view_all';
          $notification_key     = 'notice_of_completion';
          // Notification Parameter
          $project_id           = $check_project_user->p_id;
          $notification_title   = 'Notification for notice of completion in Project: ' .$check_project_user->p_name;
          $url                  = App::make('url')->to('/');
          $link                 = "/dashboard/".$project_id."/notice_completion";
          $date                 = date("M d, Y h:i a");
          $email_description    = 'Notification for notice of completion in project <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

          $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
          if(count($check_single_user_permission) < 1){
            continue;
          }
          else {
            $check_project_user_notification = app('App\Http\Controllers\Projects\PermissionController')->check_project_user_notification($project_id,$user_id,$notification_key);
            if(count($check_project_user_notification) < 1){
              continue;
            }else{
                // Send Notification to users
                $project_notification_query = app('App\Http\Controllers\Projects\NotificationController')->add_notification($notification_title, $link, $project_id, $check_single_user_permission[0]->pup_user_id);

                $user_detail = array(
                  'id'              => $check_project_user->noc_id,
                  'name'            => $check_project_user->user_name,
                  'email'           => $check_project_user->user_email,
                  'link'            => $link,
                  'date'            => $date,
                  'project_name'    => $check_project_user->p_name,
                  'title'           => $notification_title,
                  'description'     => $email_description
                );
                $user_single = (object) $user_detail;
                Mail::send('emails.send_notification',['user' => $user_single], function ($message) use ($user_single) {
                    $message->from('no-reply@sw.ai', 'StratusCM');
                    $message->to($user_single->email, $user_single->name)->subject($user_single->title);
                });
            }
          }

        } // End Foreach
        // End Check User Permission and send notification and email 
        
        
        
        
        
        
        //echo '<pre>';print_r($meetings);die;
        
        
        
        
        //print_r($request);
        die;
    }
    
    
  public function send_pay_quantity_verification_notification(Request $request)
  {
        try
        {
            //echo '<pre>';
            $projects = DB::table('projects')
            ->select()
            ->where('p_status', '=', 'active')
            ->get();
            //echo '<pre>';print_r($projects);die;
            foreach($projects as $project)
            {
                $project_id           = $project->p_id;
                //echo '<pre>';print_r($project);die;
                
                //DB::enableQueryLog();
                if($project->pqv_notification_date==date("d"))
                {
                    $cms = DB::table('project_contact')
                            ->leftJoin('users', 'project_contact.c_user_id', '=', 'users.id')
                            ->select('users.*')
                            ->where('project_contact.c_project_id', '=', $project_id)
                            ->where('users.user_role', '=', "Construction Manager")
                            ->get();
                    foreach($cms as $cm)
                    {       
                        $notification_title   = 'Your verification of pay quantities in project '.$project->p_name.' needs your review and approval before final invoicing so said pay period can be generated.';
                        $url                  = App::make('url')->to('/');
                        $link                 = "/dashboard/".$project->p_id."/payment_quantity_verification_monthly";
                        $date                 = date("M d, Y h:i a");
                        $email_description    = 'Your verification of pay quantities in project <strong>'.$project->p_name.'</strong> needs your review and approval before final invoicing so said pay period can be generated. <a href="'.$url.$link.'"> Click Here to see </a>';
                        $user_detail = array(
                            'id'              => $cm->id,
                            'name'            => $cm->username,
                            'email'           => $cm->email,
                            'link'            => $link,
                            'date'            => $date,
                            'project_name'    => $project->p_name,
                            'title'           => $notification_title,
                            'description'     => $email_description
                        );
                        $user_single = (object) $user_detail;
                        print_r($user_single);
                        Mail::send('emails.send_notification',['user' => $user_single], function ($message) use ($user_single) {
                            $message->from('no-reply@sw.ai', 'StratusCM');
                            $message->to($user_single->email, $user_single->name)->subject($user_single->title);
                        });
                        if(count($review) < 1)
                        {
                          $result = array('code'=>404, "description"=>"No Records Found");
                        }
                    }
                }
            }
            $result = array('description'=>'Notification sent successfully','code'=>200);
            return response()->json($result, 200);
        }
        catch(Exception $e)
        {
          return response()->json(['error' => 'Something is wrong'], 500);
        }
  }
  
  
    
}