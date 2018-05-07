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
use App\ProjectWeeklyReports; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post; 
use App\Repositories\Util\AclPolicy;


class WeeklyReportController extends Controller {
  
  /* 
  --------------------------------------------------------------------------
   WEEKLY CRON REPORT GENERATE
  --------------------------------------------------------------------------
  */
  public function weekly_cron_report_generate(Request $request)
  {
    try
    {
      // echo date('Y-m-d');
      $query = DB::table('projects')
      ->select()
      ->where('p_status', '=', 'active')
      ->where('p_status', '!=', 'completed')
      ->get();
      if(count($query) < 1)
      {
        $result = array('code'=>404, "description"=>"No Records Found");
        return response()->json($result, 404);
      }
      else
      {
          echo '<pre>';
          foreach ($query as $project) {

          $current_date     = date('Y-m-d');
          $project_id       = $project->p_id;

            $project_notice_proceed = DB::table('project_notice_proceed')
            ->select()
            ->where('pnp_project_id', '=', $project_id )
            ->orderBy('pnp_start_date','DESC')
            ->first();

         echo "project : ".$project_id,"</br>"; 

            $countDays = 0;
         // echo "<pre> pro Data :".print_r($project_notice_proceed , TRUE)."</pre>";
         //  echo "<pre> pnp Data :".print_r($project_notice_proceed->pnp_cal_day , TRUE)."</pre>";
if (isset( $project_notice_proceed->pnp_cal_day  )) {

            if (  $project_notice_proceed->pnp_cal_day == 'calendar_day' ) {
               $countDays = 0;
            } else {
                 $countDays = 2;
            }
}
             echo $countDays;
           //  die();

            $add_weekly_report = ProjectWeeklyReports::create(['pwr_project_id' => $project_id, 'pwr_week_ending' => $current_date, 'pwr_status' => 'active', 'pwr_status' => 'incomplete']);
            
            $add_weekly_report_id = $add_weekly_report->id;
            // print_r($add_weekly_report_id);

            for ($i=$countDays; $i < 7; $i++) {
                $date = date('l, jS \of F Y', strtotime($current_date . ' -'.$i.' day'));
                $query = DB::table('project_weekly_reports_days')
                ->insert(['pwrd_date' => $date, 'pwrd_project_id' => $project_id, 'pwrd_report_id' => $add_weekly_report_id]);
            }

            if(count($add_weekly_report) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else 
            {

            // Start Payment Quantity Verification Automation Process
             $check_project_users = app('App\Http\Controllers\Projects\PermissionController')->check_project_user($project_id);

             // Check User Project Permission  
            foreach ($check_project_users as $check_project_user) {
              // Check User Permission Parameter 
              $user_id              = $check_project_user->id;
              $permission_key       = 'weekly_report_view_all';
              // Notification Parameter
              $project_id           = $project_id;
              $notification_title   = "Weekly report added in Project: " .$check_project_user->p_name;
              $url                  = App::make('url')->to('/');
              $link                 = "/dashboard/".$project_id."/weekly_statement/".$add_weekly_report->id.'/update';
              $date                 = date("M d, Y h:i a");
              $email_description    = 'A weekly report has been added for week ending: '.$current_date.' in Project: <strong>'.$check_project_user->p_name.'</strong>. <a href="'.$url.$link.'"> Click Here to see </a>';

              $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
              if(count($check_single_user_permission) < 1){
                continue;
              }
              else {
                $notification_key     = 'weekly_statement_of_contract_days';
                $check_project_user_notification = app('App\Http\Controllers\Projects\PermissionController')->check_project_user_notification($project_id,$user_id,$notification_key);
                if(count($check_project_user_notification) < 1){
                  continue;
                }else{
                    // Send Notification to users
                    $notification_status  = '1';
                    $sender_user_id       = '1';// Auth::user()->id;

                    $query = DB::table('project_notifications')
                    ->insert(['pn_description' => $notification_title, 'pn_link' => $link, 'pn_status' => $notification_status, 'pn_project_id' => $project_id, 'pn_sender_user_id' => $sender_user_id, 'pn_receiver_user_id' => $check_single_user_permission[0]->pup_user_id]);

                    $user_detail = array(
                      'id'              => $check_project_user->id,
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

            } // End Foreach
            // End Payment Quantity Verification Automation Process
              echo 'Add Weekly report for project: '.$project->p_name.'<br/>';
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
   Update Weekly report by passing report_id
  --------------------------------------------------------------------------
  */
  public function update_weekly_report(Request $request, $report_id)
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
          $project_id             = $request['project_id'];
          $time_extension         = $request['time_extension'];
          $remark_report          = $request['remark_report'];
          $type_name              = $request['type_name'];

      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'weekly_report_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {

          $information = array(
            "time_extension"        => $time_extension,
            "remark_report"         => $remark_report,
            "type_name"             => $type_name
        );

        $rules = [
            'time_extension'        => 'required',
            'remark_report'         => 'required',
            'type_name'             => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_weekly_reports')
            ->where('pwr_id', '=', $report_id)
            ->update(['pwr_time_extension' => $time_extension, 'pwr_remarks' => $remark_report, 'pwr_type_name' => $type_name, 'pwr_status' => 'active', 'pwr_report_status' => 'complete']);
            if(count($query) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {

              // Start Check User Permission and send notification and email  
              // Get Project Users
              $check_project_users = app('App\Http\Controllers\Projects\PermissionController')->check_project_user($project_id);

              // Check User Project Permission  
              foreach ($check_project_users as $check_project_user) {
                // Check User Permission Parameter 
                $user_id              = $check_project_user->id;
                $permission_key       = 'weekly_report_view_all';
                // Notification Parameter
                $project_id           = $project_id;
                //$notification_title   = "Update Weekly report # ".$report_id." in Project: " .$check_project_user->p_name;
                $notification_title   = "Weekly report # ".$report_id." updated in Project: ".$check_project_user->p_name;
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/weekly_statement/".$report_id;
                $date                 = date("M d, Y h:i a");
                $email_description    = 'A weekly report # '.$report_id.' has been updated in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
                if(count($check_single_user_permission) < 1){
                  continue;
                }
                else {
                    $notification_key     = 'weekly_statement_of_contract_days';
                    $check_project_user_notification = app('App\Http\Controllers\Projects\PermissionController')->check_project_user_notification($project_id,$user_id,$notification_key);
                    if(count($check_project_user_notification) < 1){
                      continue;
                    }else{
                        $notification_key     = 'weekly_statement_of_contract_days';
                        $check_project_user_notification = app('App\Http\Controllers\Projects\PermissionController')->check_project_user_notification($project_id,$user_id,$notification_key);
                        if(count($check_project_user_notification) < 1){
                          continue;
                        }else{
                            // Send Notification to users
                            $project_notification_query = app('App\Http\Controllers\Projects\NotificationController')->add_notification($notification_title, $link, $project_id, $check_single_user_permission[0]->pup_user_id);

                            $user_detail = array(
                              'id'              => $check_project_user->id,
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
                }

              } // End Foreach
              // End Check User Permission and send notification and email 

              $result = array('data'=>'update day quantity','code'=>200);
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
   Get all Weekly Report by passing project_id
  --------------------------------------------------------------------------
  */
  public function get_all_weekly_report(Request $request, $project_id)
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
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'weekly_report_view_all';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $query = DB::table('project_weekly_reports')
->leftJoin('projects', 'project_weekly_reports.pwr_project_id', '=', 'projects.p_id')
        ->select('project_weekly_reports.*', 'projects.*')
        ->where('pwr_project_id', '=', $project_id)
        ->orderBy('project_weekly_reports.pwr_id','ASC')
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
    }
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }

  /*
  --------------------------------------------------------------------------
   Get single Weekly Report by passing pwr_id
  --------------------------------------------------------------------------
  */
  public function get_weekly_report_single(Request $request, $report_id)
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
        $query = DB::table('project_weekly_reports')
->leftJoin('projects', 'project_weekly_reports.pwr_project_id', '=', 'projects.p_id')
        ->select('project_weekly_reports.*', 'projects.*')
        ->where('pwr_id', '=', $report_id)
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
   Get single Weekly Report by passing pwr_id
  --------------------------------------------------------------------------
  */
  public function get_weekly_report_single_days(Request $request, $report_id)
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
        $query = DB::table('project_weekly_reports_days')
        ->select()
        ->where('pwrd_report_id', '=', $report_id)
        ->orderBy('pwrd_id', 'desc')
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
   Get single Weekly Report by passing pwrd_project_id
  --------------------------------------------------------------------------
  */
  public function get_weekly_report_single_days_count(Request $request, $project_id)
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


        $weeklyData = DB::table('project_weekly_reports_days')
            ->select()
            ->where('pwrd_report_id', '=', $request['report_id'])
            ->count();
        echo "<pre> Data :".print_r(  $weeklyData  , TRUE )."</pre>";

            $all_report = DB::table('project_weekly_reports_days')
                     ->select(DB::raw('count(*) as user_count, pwrd_report_id'))
                     ->groupBy('pwrd_report_id')
                     ->get();

             echo "<pre> Data :".print_r(  $all_report  , TRUE )."</pre>";

        die();
            $projectID =0;
$projectIDs = array( );
for ($i=0; $i <count($project_notice_proceed) ; $i++) { 
 
            if (  $project_notice_proceed[$i]->pnp_cal_day == 'calendar_day' ) {

              $project_ids = DB::table('project_notice_proceed')
              ->select('pnp_project_id')
              ->where('pnp_cal_day', '=', 'calendar_day')
               ->where('pnp_project_id', '=', $project_id)
              ->orderBy('pnp_project_id','DESC')
               ->first();
              $projectID =  $project_ids->pnp_project_id;
            } else {
                  $project_ids = DB::table('project_notice_proceed')
              ->select('pnp_project_id')
              ->where('pnp_cal_day', '=', 'working_day')
              ->where('pnp_project_id', '=', $project_id)
              ->orderBy('pnp_project_id','DESC')
               ->first();
              $projectID =  $project_ids->pnp_project_id;
            }
}

          // echo "project id".$projectID;
        $query = DB::table('project_weekly_reports_days')
        ->select()
        ->select(DB::raw('sum(pwrd_approved_calender_day) as pwrd_approved_calender_day, sum(pwrd_approved_non_calender_day) as pwrd_approved_non_calender_day, sum(pwrd_rain_day) as pwrd_rain_day'))
        ->where('pwrd_project_id', '=', $projectID)
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
   Update days quantity by passing days_id
  --------------------------------------------------------------------------
  */
  public function update_day_quantity_complete(Request $request, $days_id)
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
          $days_id               = $request['days_id'];
          $days_weather          = $request['days_weather'];
          $days_app_calender     = $request['days_app_calender'];
          $days_app_non_calender = $request['days_app_non_calender'];
          $days_rainy_day        = $request['days_rainy_day'];
          $current_time = date("Y-m-d H:i:s"); 

          $query = DB::table('project_weekly_reports_days')
          ->where('pwrd_id', '=', $days_id)
          ->update(['pwrd_weather' => $days_weather, 'pwrd_approved_calender_day' => $days_app_calender, 'pwrd_approved_non_calender_day' => $days_app_non_calender, 'pwrd_rain_day' => $days_rainy_day,'update_time' => $current_time ]);
          if(count($query) < 1)
          {
            $result = array('code'=>400, "description"=>"No records found");
            return response()->json($result, 400);
          }
          else
          {
            $result = array('data'=>'update day quantity','code'=>200);
            return response()->json($result, 200);
          }
      // }
    }
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }

}