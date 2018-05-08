<?php 
// namespace App\Http\Controllers\Users;
// use Illuminate\Http\Request;
// use Tymon\JWTAuth\Exceptions\JWTException;
// use Tymon\JWTAuth\Facades\JWTAuth;
// use App\Http\Controllers\Controller;
// use JWTAuth;
// use DB;


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
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Redirect;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;
use Illuminate\Support\Facades\Mail;
use App;
use Session;

use Gate;
use App\User; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post; 
use App\Repositories\Util\AclPolicy;


class ProjectController extends Controller {

  /*
  --------------------------------------------------------------------------
   Get All Projects
  --------------------------------------------------------------------------
  */
  public function get_projects(Request $request)
  {
      try
      {
        // $user = array(
        //   'userid'    => Auth::user()->id,
        //   'role'      => Auth::user()->role
        // );
        // $user = (object) $user;
        // $post = new Resource_Post(); // You create a new resource Post instance
        // if (Gate::forUser($user)->denies('allow_admin', [$post,false])) { 
        //   $result = array('code'=>403, "description"=>"Access denies");
        //   return response()->json($result, 403);
        // } 
        // else {
          $query = DB::table('projects')
          ->leftJoin('project_type_improvement', 'projects.p_type', '=', 'project_type_improvement.pt_id')
          ->select('projects.*', 'project_type_improvement.*')
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
        // }
      }
      catch(Exception $e)
      {
        return response()->json(['error' => 'Something is wrong'], 500);
      }
  }


  /*
  --------------------------------------------------------------------------
   Get All Projects
  --------------------------------------------------------------------------
  */
  public function get_user_projects(Request $request, $user_id)
  {
      try
      {
        // $user = array(
        //   'userid'    => Auth::user()->id,
        //   'role'      => Auth::user()->role
        // );
        // $user = (object) $user;
        // $post = new Resource_Post(); // You create a new resource Post instance
        // if (Gate::forUser($user)->denies('allow_admin', [$post,false])) { 
        //   $result = array('code'=>403, "description"=>"Access denies");
        //   return response()->json($result, 403);
        // } 
        // else {
          $query = DB::table('projects')
          ->leftJoin('users', 'projects.p_user_id', '=', 'users.id')
          ->leftJoin('project_contact', 'projects.p_id', '=', 'project_contact.c_project_id')
          ->select('projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('project_contact.c_user_id', '=', $user_id)
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
        // }
      }
      catch(Exception $e)
      {
        return response()->json(['error' => 'Something is wrong'], 500);
      }
  }

  /*
  --------------------------------------------------------------------------
   Add Project
  --------------------------------------------------------------------------
  */
  public function add_project(Request $request)
  {
    try
    {
      $user = array(
        'id'        => Auth::user()->id,
        'role'      => Auth::user()->role
      );
      $user = (object) $user;
      $post = new Resource_Post(); // You create a new resource Post instance
      if (Gate::forUser($user)->denies('allow_admin_owner_user', [$post,false])) { 
        $result = array('code'=>403, "description"=>"Access denies");
        return response()->json($result, 403);
      } 
      else { 
        $project_number       = $request['project_number'];
        $project_name         = $request['project_name'];
        $project_location     = $request['project_location'];
        $project_long         = $request['project_long'];
        $project_lat          = $request['project_lat'];
        $project_type         = $request['project_type'];
        // $project_description  = $request['project_description'];
        $project_lead_agency  = $request['project_lead_agency'];
        $project_terms        = $request['project_terms'];
        $project_wage_determination        = $request['project_wage_determination'];
        $rfi_due_date         = $request['rfi_due_date'];
        $rfi_days_type        = $request['rfi_days_type'];
        $submittal_days_type        = $request['submittal_days_type'];
        $submittal_due_date        = $request['submittal_due_date'];
        $change_order_due_date        = $request['change_order_due_date'];
        $change_order_days_type        = $request['change_order_days_type'];
        $project_status       = 'active';
        $user_id              = Auth::user()->id;

        $information = array(
            "p_number"        => $project_number,
            "p_name"          => $project_name,
            "p_location"      => $project_location,
            // "p_description"   => $project_description,
            "p_lead_agency"   => $project_lead_agency,
            "p_type"          => $project_type,
            "p_wage_determination" => $project_wage_determination,
            "p_status"        => $project_status,
            "p_term"          => $project_terms,
            "rfi_due_date"          => $rfi_due_date,
            "rfi_days_type"          => $rfi_days_type,
            "submittal_days_type"          => $submittal_days_type,
            "submittal_due_date"          => $submittal_due_date,
            "change_order_due_date"          => $change_order_due_date,
            "change_order_days_type"          => $change_order_days_type,
        );

        if($project_terms == 'yes'){
          $rules = [
            "p_number"              => 'required|unique:projects',
            "p_name"                => 'required',
            "p_location"            => 'required',
            "p_lead_agency"         => 'required',
            "p_type"                => 'required',
            "p_wage_determination"  => 'required',
            "p_term"                => 'required',
            "p_status"              => 'required',
            "rfi_due_date"          => 'required',
            "rfi_days_type"         => 'required',
            "submittal_days_type"   => 'required',
            "submittal_due_date"    => 'required',
            "change_order_due_date" => 'required',
            "change_order_days_type"=> 'required'
          ];  
        }
        else {
          $rules = [
            "p_number"              => 'required|unique:projects',
            "p_name"                => 'required',
            "p_location"            => 'required',
            "p_type"                => 'required',
            "p_wage_determination"  => 'required',
            "p_term"                => 'required',
            "p_status"              => 'required',
            "rfi_due_date"          => 'required',
            "rfi_days_type"         => 'required',
            "submittal_days_type"   => 'required',
            "submittal_due_date"    => 'required',
            "change_order_due_date" => 'required',
            "change_order_days_type"=> 'required'
          ];
        }
        
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('projects')
            // ->insertGetId(['p_number' => $project_number, 'p_name' => $project_name, 'p_location' => $project_location, 'p_long' => $project_long, 'p_lat' => $project_lat, 'p_type' => $project_type, 'p_description' => $project_description, 'p_term' => $project_terms, 'p_status' => $project_status, 'p_user_id' => $user_id]);
            ->insertGetId(['p_number' => $project_number, 'p_name' => $project_name, 'p_location' => $project_location, 'p_long' => $project_long, 'p_lat' => $project_lat, 'p_type' => $project_type, 'p_term' => $project_terms, 'p_wage_determination' => $project_wage_determination, 'p_lead_agency' => $project_lead_agency, 'p_status' => $project_status, 'p_user_id' => $user_id,'rfi_due_date' => $rfi_due_date,'rfi_days_type' => $rfi_days_type,'submittal_days_type' => $submittal_days_type,'submittal_due_date' => $submittal_due_date,'change_order_due_date' => $change_order_due_date,'change_order_days_type' => $change_order_days_type]);

            if(count($query) < 1)
            {
              return $result = response()->json(["data" => $validator->messages()],400);
            }
            else
            {

              $user_permission = array("contact_view_permission_all", "contact_update", "contact_add", "contact_remove", "standard_view_all", "standard_update", "standard_add", "plan_view_all", "plan_update", "plan_add", "geotechnical_view_all", "geotechnical_update", "geotechnical_add", "swppp_view_all", "swppp_update", "swppp_add", "bid_document_view_all", "bid_document_update", "bid_document_add", "contract_item_view_all", "contract_item_update", "contract_item_add", "notice_award_view_all", "notice_award_update", "notice_award_add", "certificate_view_all", "certificate_update", "certificate_add", "bond_view_all", "bond_update", "bond_add", "contract_view_all", "contract_update", "contract_add", "notice_proceed_view_all", "notice_proceed_update", "notice_proceed_add", "meeting_minutes_view_all", "meeting_minutes_update", "meeting_minutes_add", "service_alert_view_all", "service_alert_update", "service_alert_add", "test_result_view_all", "test_result_update", "test_result_add", "daily_construction_report_view_all", "daily_construction_report_update", "weekly_report_view_all", "weekly_report_update", "preliminary_view_all", "preliminary_update", "preliminary_add", "submittal_view_all", "submittal_update", "submittal_add", "submittal_log", "submittal_review_view_all", "submittal_review_update", "rfi_view_all", "rfi_update", "rfi_add", "rfi_log", "rfi_review_view_all", "rfi_review_update", "survey_view_all", "survey_update", "survey_add", "survey_log", "survey_review_view_all", "survey_review_update", "cor_log", "cor_view_all", "cor_add", "cor_order_review_update", "labor_compliance_view_all", "labor_compliance_update", "labor_compliance_add", "drawing_view_all", "drawing_update", "drawing_add", "payment_quantity_verification_view_all", "payment_application_view_all", "notice_completion_view_all", "notice_completion_update", "notice_completion_add", "agency_acceptance_view_all", "agency_acceptance_update", "agency_acceptance_add", "project_picture_video_view_all", "project_picture_video_add", "project_setting", "unconditional_finals_view_all", "unconditional_finals_update", "unconditional_finals_add", "project_picture_video_remove");

// Permission add in Project
// if($user_id == 1){
//   foreach ($user_permission as $single_permission) {
//       // echo $single_permission;
//     $query_contact = DB::table('project_user_permission')
//     ->insert(['pup_project_id' => $query, 'pup_user_id' => '1', 'pup_permission_key' => $single_permission, 'pup_access' => 'true', 'pup_permission_assign_user_id' => '1']);
//   }
// }
// else {
//   foreach ($user_permission as $single_permission) {
//       // echo $single_permission;
//     $query_contact = DB::table('project_user_permission')
//     ->insert(['pup_project_id' => $query, 'pup_user_id' => '1', 'pup_permission_key' => $single_permission, 'pup_access' => 'true', 'pup_permission_assign_user_id' => '1']);
//   }

//   foreach ($user_permission as $single_permission) {
//       // echo $single_permission;
//     $query_contact = DB::table('project_user_permission')
//     ->insert(['pup_project_id' => $query, 'pup_user_id' => $user_id, 'pup_permission_key' => $single_permission, 'pup_access' => 'true', 'pup_permission_assign_user_id' => '1']);
//   }
// }

              foreach ($user_permission as $single_permission) {
                  // echo $single_permission;
                $query_contact = DB::table('project_user_permission')
                ->insert(['pup_project_id' => $query, 'pup_user_id' => $user_id, 'pup_permission_key' => $single_permission, 'pup_access' => 'true', 'pup_permission_assign_user_id' => '1']);
              }

              $user = DB::table('project_contact')  
              ->insert(['c_project_id' => $query, 'c_user_id' => $user_id]);

              $currency_query = DB::table('currency')
              ->select()
              ->where('cur_user_id', '=', $user_id)
              ->orderBy('cur_id', 'asc')
              ->first();
              if(count($currency_query) < 1)
              {
                $result = array('code'=>404, "description"=>"No Records Found");
                return response()->json($result, 404);
              }
              else
              {
                $result = array('data'=>$currency_query,'code'=>200);
                // print_r($currency_query->cur_id);
                $currency_query = DB::table('project_settings')
                ->insert(['pset_project_id' => $query, 'pset_meta_key' => 'project_currency', 'pset_meta_value' => $currency_query->cur_id, 'pset_user_id' => $user_id]);
                // return response()->json($result, 200);
              }
                /*send notification*/  
                $notified_users = DB::table('users')
                ->select()
                ->where('user_parent', '=', $user_id)
                ->where('status', '!=', 2)
                ->get();
                foreach($notified_users as $check_project_user)
                {
                    $user_id              = $check_project_user->id;
                    $permission_key       = 'standard_view_all';
                    
                    // Notification Parameter
                    $project_id           = $query;
                    $notification_title   = 'New Project added in StratusCM.';
                    $url                  = App::make('url')->to('/');
                    $link                 = "/dashboard";
                    $date                 = date("M d, Y h:i a");
                    $email_description    = 'A new Project has been added in StratusCM: <strong>'.$project_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
                    
                    $notification_key     = 'project_setup';
                    $check_project_user_notification = app('App\Http\Controllers\Projects\PermissionController')->check_project_user_notification($project_id,$user_id,$notification_key);
                    if(count($check_project_user_notification) < 1){
                      continue;
                    }else{
                        // Send Notification to users
                        $project_notification_query = app('App\Http\Controllers\Projects\NotificationController')->add_notification($notification_title, $link, $project_id, $check_project_user->id);

                        $user_detail = array(
                          'id'              => $check_project_user->id,
                          'name'            => $check_project_user->username,
                          'email'           => $check_project_user->email,
                          'link'            => $link,
                          'date'            => $date,
                          'project_name'    => $project_name,
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
                /*send notification end*/  
              $result = array('description'=>"New project add successfully", 'last_insert_id' => $query, 'code'=>200);
              return response()->json($result, 200);
            }
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
   Get single Project detail passing project ID
  --------------------------------------------------------------------------
  */
  public function get_project_single(Request $request, $project_id)
  {
    try
    {
      $project_id            = $project_id;
      // $user = array(
      //   'userid'    => Auth::user()->id,
      //   'role'      => Auth::user()->role
      // );
      // $user = (object) $user;
      // $post = new Resource_Post(); // You create a new resource Post instance
      // if (Gate::forUser($user)->denies('allow_admin', [$post,false])) { 
      //   $result = array('code'=>403, "description"=>"Access denies");
      //   return response()->json($result, 403);
      // } 
      // else {
        $query = DB::table('projects')
        // ->join('project_type_improvement', 'projects.p_type', '=', 'project_type_improvement.pt_id')
        // ->select('projects.*', 'project_type_improvement.pt_name as project_type')
        ->leftJoin('project_firm', 'projects.p_lead_agency', '=', 'project_firm.f_id')
        ->select('projects.*','project_firm.f_name')
        ->where('p_id', '=', $project_id)
        ->first();
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
      // }
    }
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }

  /*
  --------------------------------------------------------------------------
   Update Project passing project ID
  --------------------------------------------------------------------------
  */
  public function update_project(Request $request, $project_id)
  {
    try
    {
      //print_r($request->all());die;
      $user = array(
        'userid'    => Auth::user()->id,
        'role'      => Auth::user()->role
      );
      $user = (object) $user;
     // echo "<pre> Data :".print_r($user , TRUE )."</pre>";
      $post = new Resource_Post(); // You create a new resource Post instance
   /*  if (Gate::forUser($user)->denies('allow_admin_owner_user', [$post,false])) { 
        $result = array('code'=>403, "description"=>"Access denies");
        return response()->json($result, 403);
      } 
      else {*/
        $project_id           = $project_id;
        // $project_number       = $request['project_number'];
        $project_name         = $request['project_name'];
        $project_location     = $request['project_location'];
        $project_long         = $request['project_long'];
        $project_lat          = $request['project_lat'];
        $project_type         = $request['project_type'];
        $project_description  = $request['project_description'];
        $project_wage_determination        = $request['project_wage_determination'];
        $project_status       = $request['project_status'];
        $project_terms        = $request['project_terms'];
        $project_lead_agency  = $request['project_lead_agency'];
        $rfi_due_date         = $request['rfi_due_date'];
        $rfi_days_type        = $request['rfi_days_type'];
        $submittal_days_type        = $request['submittal_days_type'];
        $submittal_due_date        = $request['submittal_due_date'];
        $change_order_due_date        = $request['change_order_due_date'];
        $change_order_days_type        = $request['change_order_days_type'];
          
        $information = array(
            // "p_number"        => $project_number,
            "p_name"          => $project_name,
            "p_location"      => $project_location,
            "p_type"          => $project_type,
            "p_status"        => $project_status,
            "p_wage_determination" => $project_wage_determination,
             "project_terms"   => $project_terms,
              "rfi_due_date"          => $rfi_due_date,
            "rfi_days_type"          => $rfi_days_type,
            "submittal_days_type"          => $submittal_days_type,
            "submittal_due_date"          => $submittal_due_date,
            "change_order_due_date"          => $change_order_due_date,
            "change_order_days_type"          => $change_order_days_type,
        );
        $rules = [
            // "p_number"        => 'required|unique:projects',
            "p_name"          => 'required',
            "p_location"      => 'required',
            "p_type"          => 'required',
            "p_status"        => 'required',
            "p_wage_determination" => 'required',
             "project_terms"   => 'required',
              "rfi_due_date"          => 'required',
            "rfi_days_type"         => 'required',
            "submittal_days_type"   => 'required',
            "submittal_due_date"    => 'required',
            "change_order_due_date" => 'required',
            "change_order_days_type"=> 'required'

        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            
            $query_select = DB::table('projects')
            ->select()
            ->where('p_id', '=', $project_id)
            ->first();

            // print_r($query_select->p_type);
            // exit;
            if(($project_type == "" || $project_type == "null") ? $project_type = $query_select->p_type : $project_type = $project_type);
            // print_r($project_type);
            // exit;
            $query = DB::table('projects')
            ->where('p_id', '=', $project_id)
            // ->update(['p_number' => $project_number, 'p_name' => $project_name, 'p_location' => $project_location, 'p_long' => $project_long, 'p_lat' => $project_lat, 'p_type' => $project_type, 'p_description' => $project_description, 'p_status' => $project_status]);
            // ->update(['p_name' => $project_name, 'p_location' => $project_location, 'p_long' => $project_long, 'p_lat' => $project_lat, 'p_type' => $project_type, 'p_description' => $project_description, 'p_status' => $project_status]);
            ->update(['p_name' => $project_name, 'p_location' => $project_location, 'p_long' => $project_long, 'p_lat' => $project_lat, 'p_term' => $project_terms, 'p_lead_agency' => $project_lead_agency, 'p_type' => $project_type, 'p_wage_determination' => $project_wage_determination, 'p_status' => $project_status,'rfi_due_date' => $rfi_due_date,'rfi_days_type' => $rfi_days_type,'submittal_days_type' => $submittal_days_type,'submittal_due_date' => $submittal_due_date,'change_order_due_date' => $change_order_due_date,'change_order_days_type' => $change_order_days_type]);
            if(count($query) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {
              $result = array('data'=>$query,'code'=>200);
              return response()->json($result, 200);
            }
        }
    //  }
    }
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }
  
  
  /*
  --------------------------------------------------------------------------
   Get Project Dashboard Top Info
  --------------------------------------------------------------------------
  */
 public function get_dashboard_info(Request $request, $project_id) {
    $info = array(); 
    $query = DB::table('project_bid_items')
        ->select(DB::raw('SUM(pbi_item_total_price) as total_amount'))
        ->where('pbi_project_id', '=', $project_id)
        ->where('pbi_status', '=', 'active')
        ->get();
    $info['contract_amount'] = $query[0]->total_amount;
    
    $query1 = DB::table('project_change_order_request_detail')
	->select('project_change_order_request_detail.*')
	->where('pcd_project_id', '=', $project_id)
	//->whereDate('pcd_approved_by_cm', '>', '1900-01-01')
        //->whereDate('pcd_approved_by_owner', '>', '1900-01-01')
	->get();
    $total = 0;
    $count = 0;
    $total1 = 0;
    $count1 = 0;
    foreach($query1 as $row){
        if(($row->pcd_approved_by_cm!=null && $row->pcd_approved_by_cm!="0000-00-00") && ($row->pcd_approved_by_owner!=null && $row->pcd_approved_by_owner!="0000-00-00"))
        {
            $count++;
            if($row->pcd_unit_number)
                $total += $row->pcd_unit_number * $row->pcd_unit_price;
            else
                $total += $row->pcd_price;
        }else{
            $count1++;
            if($row->pcd_unit_number)
                $total1 += $row->pcd_unit_number * $row->pcd_unit_price;
            else
                $total1 += $row->pcd_price;
        }
    }
    $info['total_change_order_count'] = $count;
    $info['total_change_order_amount'] = $total.'.00';
    $info['pending_change_order_count'] = $count1;
    $info['pending_change_order_amount'] = $total1.'.00';
    $info['total_contract_amount'] = $info['contract_amount']+$info['total_change_order_amount'].'.00'; 
    
    $query2 = DB::table('project_notice_proceed')
	->select('project_notice_proceed.pnp_duration')
	->where('pnp_project_id', '=', $project_id)
	->orderBy('pnp_id', 'asc')
    	->first();
    $info['original_contract_date'] = $query2->pnp_duration;
    
    $query3 = DB::table('project_weekly_reports')
	->select('project_weekly_reports.*')
	->where('pwr_project_id', '=', $project_id)
	->orderBy('pwr_id', 'desc')
    	->first();
    $info['contract_days_added'] = $query3->days_this_report_app_calender+$query3->days_previous_report_app_calender;
    
    $info['revised_contract_date'] = $query2->pnp_duration+$query3->days_this_report_app_calender+$query3->days_previous_report_app_calender;
    
    
    $query4 = DB::table('project_weekly_reports_days')
        ->select(DB::raw('SUM(pwrd_approved_calender_day) as contract_days_charged'))
        ->where('pwrd_project_id', '=', $project_id)
        ->get();
    $info['contract_days_charged'] = $query4[0]->contract_days_charged;
    
    $info['remaining_days'] = $info['revised_contract_date']-$info['contract_days_charged'];
    
    //echo '<pre>';print_r($query3);
    //print_r($info);
    $result = array('data'=>$info,'code'=>200);
    return response()->json($result, 200);
 }
}