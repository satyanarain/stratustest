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
use YahooWeather;

use Gate;
use App\User; //your model
use App\Document; //your model
use App\ProjectDailyQuantityCompleted; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post; 
use App\Repositories\Util\AclPolicy;


class DailyReportController extends Controller {
  
  /*
  --------------------------------------------------------------------------
   DAILY CRON REPORT GENERATE
  --------------------------------------------------------------------------
  */
  public function daily_cron_report_generate(Request $request)
  {
    try
    {
      // $ch = curl_init();
      // $headers = array(
      // 'Accept: application/json',
      // 'Content-Type: application/json',
      // );
      // curl_setopt($ch, CURLOPT_URL, 'http://dataservice.accuweather.com/locations/v1/cities/geoposition/search?apikey=GDRcpCmvI7AVdyfiee4wSEAY5vjp0FTs&q=24.862902%2C%2067.023954');
      // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      // curl_setopt($ch, CURLOPT_HEADER, 0);
      // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
      // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      // // Timeout in seconds
      // curl_setopt($ch, CURLOPT_TIMEOUT, 30);
      // $authToken = curl_exec($ch);
      // $response = json_decode($authToken, true);
      // curl_close($ch);
      
      // echo $area_key = $response["Key"];
      // print_r($response);

      // $curl = curl_init();
      // $headers = array(
      // 'Accept: application/json',
      // 'Content-Type: application/json',
      // );
      // curl_setopt($curl, CURLOPT_URL, 'http://dataservice.accuweather.com/currentconditions/v1/260799?apikey=GDRcpCmvI7AVdyfiee4wSEAY5vjp0FTs');
      // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
      // curl_setopt($curl, CURLOPT_HEADER, 0);
      // curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET"); 
      // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

      // // Timeout in seconds
      // curl_setopt($curl, CURLOPT_TIMEOUT, 30);
      // $authToken = curl_exec($curl);
      // $response_temp = json_decode($authToken, true);
      // curl_close($curl);

      // print_r($response_temp);
      // $area_temp = $response_temp[0]["Temperature"]["Metric"]["Value"];
      // echo round($area_temp);
      // exit;
      
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
          foreach ($query as $project) {
          $report_date      = date('Y-m-d');
          $project_id       = $project->p_id;

          // print_r($project);
          $project_long = $project->p_long;
          // echo ' , ';
          $project_lat = $project->p_lat;
          // echo '<br/>';

          // $ch = curl_init();
          // $headers = array(
          // 'Accept: application/json',
          // 'Content-Type: application/json',
          // );
          // curl_setopt($ch, CURLOPT_URL, 'http://dataservice.accuweather.com/locations/v1/cities/geoposition/search?apikey=GDRcpCmvI7AVdyfiee4wSEAY5vjp0FTs&q='.$project_long.','.$project_lat);
          // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          // curl_setopt($ch, CURLOPT_HEADER, 0);
          // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
          // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

          // // Timeout in seconds
          // curl_setopt($ch, CURLOPT_TIMEOUT, 30);
          // $authToken = curl_exec($ch);
          // $response = json_decode($authToken, true);
          // curl_close($ch);
          
          // $area_key = $response["Key"];
          
          // $curl = curl_init();
          // $headers = array(
          // 'Accept: application/json',
          // 'Content-Type: application/json',
          // );
          // curl_setopt($curl, CURLOPT_URL, 'http://dataservice.accuweather.com/currentconditions/v1/'.$area_key.'?apikey=GDRcpCmvI7AVdyfiee4wSEAY5vjp0FTs');
          // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
          // curl_setopt($curl, CURLOPT_HEADER, 0);
          // curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET"); 
          // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

          // // Timeout in seconds
          // curl_setopt($curl, CURLOPT_TIMEOUT, 30);
          // $authToken = curl_exec($curl);
          // $response_temp = json_decode($authToken, true);
          // curl_close($curl);

          // $area_temp = round($response_temp[0]["Temperature"]["Metric"]["Value"]);
          $area_temp = ''; //round($response_temp[0]["Temperature"]["Metric"]["Value"]);
        
          $add_project = DB::table('project_daily_report')
          ->insert(['pdr_date' => $report_date, 'pdr_weather' => $area_temp, 'pdr_perform_work_day' => null, 'pdr_material_delivery' => null, 'pdr_milestone_completed' => null, 'pdr_picture_video' => null, 'pdr_project_id' => $project_id]);
          if(count($add_project) < 1)
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
              $permission_key       = 'daily_construction_report_view_all';
              // Notification Parameter
              $project_id           = $project_id;
              $notification_title   = "Daily Report Added for date: ".$report_date." in Project: " .$check_project_user->p_name;
              $url                  = App::make('url')->to('/');
              $link                 = "/dashboard/".$project_id."/daily_construction_report";
              $date                 = date("M d, Y h:i a");
              $email_description    = 'A new Daily report has been added for date: '.$report_date.' in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

              $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
              if(count($check_single_user_permission) < 1){
                continue;
              }
              else { 
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

            } // End Foreach
            // End Payment Quantity Verification Automation Process
            echo 'Add Daily report for project: '.$project->p_name.'<br/>';
          }
        }
      }
    }
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }


  // /*
  // --------------------------------------------------------------------------
  //  ADD DAILY REPORT
  // --------------------------------------------------------------------------
  // */
  // public function add_daily_report(Request $request)
  // {
  //   try
  //   {
  //     $user = array(
  //       'id'        => Auth::user()->id,
  //       'role'      => Auth::user()->role
  //     );
  //     $user = (object) $user;
  //     $post = new Resource_Post(); // You create a new resource Post instance
  //     if (Gate::forUser($user)->denies('allow_admin', [$post,false])) { 
  //       $result = array('code'=>403, "description"=>"Access denies");
  //       return response()->json($result, 403);
  //     } 
  //     else { 
  //       $report_date                    = $request['report_date'];
  //       $report_weather                 = $request['report_weather'];
  //       $report_custum_field            = $request['report_custum_field'];
  //       $report_perform_work_day        = $request['report_perform_work_day'];
  //       $report_question_one            = $request['report_question_one'];
  //       $report_question_two            = $request['report_question_two'];
  //       $report_question_three          = $request['report_question_three'];
  //       $report_question_four           = $request['report_question_four'];
  //       $report_question_five           = $request['report_question_five'];
  //       $report_question_six            = $request['report_question_six'];
  //       $report_question_seven          = $request['report_question_seven'];
  //       $report_subcontractor_work_day  = $request['report_subcontractor_work_day'];
  //       $project_id                     = $request['project_id'];
  //       $user_id                        = Auth::user()->id;

  //       $information = array(
  //           "report_date"                     => $report_date,
  //           "project_id"                      => $project_id,
  //           "user_id"                         => $user_id
  //       );

  //       $rules = [
  //           'report_date'                     => 'required',
  //           'project_id'                      => 'required|numeric',
  //           'user_id'                         => 'required|numeric'
  //       ];
  //       $validator = Validator::make($information, $rules);
  //       if ($validator->fails()) 
  //       {
  //           return $result = response()->json(["data" => $validator->messages()],400);
  //       }
  //       else
  //       {
  //           $query = DB::table('project_daily_report')
  //           ->insert(['pdr_date' => $report_date, 'pdr_weather' => $report_weather, 'pdr_custum_field' => $report_custum_field, 'pdr_perform_work_day' => $report_perform_work_day, 'pdr_question_one' =>$report_question_one, 'pdr_question_two' =>$report_question_two, 'pdr_question_three' =>$report_question_three, 'pdr_question_four' =>$report_question_four, 'pdr_question_five' =>$report_question_five, 'pdr_question_six' =>$report_question_six, 'pdr_question_seven' =>$report_question_seven, 'pdr_subcontractor_work_day' =>$report_subcontractor_work_day, 'pdr_project_id' => $project_id, 'pdr_user_id' => $user_id]);

  //           if(count($query) < 1)
  //           {
  //             $result = array('code'=>400, "description"=>"No records found");
  //             return response()->json($result, 400);
  //           }
  //           else
  //           {
  //             $result = array('description'=>"Add daily report successfully",'code'=>200);
  //             return response()->json($result, 200);
  //           }
  //       }
  //     }
  //   }
  //   catch(Exception $e)
  //   {
  //     return response()->json(['error' => 'Something is wrong'], 500);
  //   }
  // }

  public function get_new_report_id(Request $request, $project_id,$report_id)
  {
    try
     {
      //echo $k;die;
       $query1 = DB::table('project_daily_report')
        ->where('pdr_id', '=', $report_id)
        ->update([ 'pdr_status' => 'complete']);
            
           
        $query = DB::table('project_daily_report')
         ->leftJoin('project_firm', 'project_daily_report.pdr_sub_contractor_work_detail', '=', 'project_firm.f_id')
         ->leftJoin('projects', 'project_daily_report.pdr_project_id', '=', 'projects.p_id')
         ->leftJoin('users', 'project_daily_report.pdr_user_id', '=', 'users.id')
         ->select('project_daily_report.*', 'project_firm.f_name as sub_contractor_work_detail', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
         ->where('pdr_id', '=', $report_id)
         ->first();
        $request =  (array) $query;
        //echo '<pre>';print_r($array);die;
        $pdr_report_id                  = $report_id;
        $pdr_date                       = $request['pdr_date'];
        $pdr_weather                    = $request['pdr_weather'];
        $pdr_custom_field               = $request['pdr_custom_field']; 
        $pdr_perform_work_day           = $request['pdr_perform_work_day'];
        $pdr_material_delivery          = $request['pdr_material_delivery']; 
        $pdr_milestone_completed        = $request['pdr_milestone_completed'];
        $pdr_milestone_detail           = $request['pdr_milestone_detail']; 
        $pdr_occur_detail               = $request['pdr_occur_detail']; 
        $pdr_general_notes              = $request['pdr_general_notes'];
        $pdr_picture_video              = $request['pdr_picture_video'];
        $pdr_sub_contractor_work        = $request['pdr_sub_contractor_work'];
        $pdr_sub_contractor_work_detail = $request['pdr_sub_contractor_work_detail'];
        $pdr_status                     = $request['pdr_status']; 
        $pdr_project_id                 = $request['pdr_project_id'];
        $pdr_user_id                    = $request['pdr_user_id'];
        $user_id                        = Auth::user()->id;
        $query = DB::table('project_daily_report_logs')
            ->insert(['pdr_report_id' => $pdr_report_id, 'pdr_date' => $pdr_date,
                'pdr_weather' => $pdr_weather, 
                'pdr_custom_field' => $pdr_custom_field,
                'pdr_perform_work_day' =>$pdr_perform_work_day, 'pdr_material_delivery' =>$pdr_material_delivery,
                'pdr_milestone_completed' =>$pdr_milestone_completed,
                'pdr_milestone_detail' =>$pdr_milestone_detail, 
                'pdr_occur_detail' =>$pdr_occur_detail, 'pdr_general_notes' =>$pdr_general_notes,
                'pdr_picture_video' =>$pdr_picture_video, 
                'pdr_sub_contractor_work' =>$pdr_sub_contractor_work,
                'pdr_sub_contractor_work_detail' => $pdr_sub_contractor_work_detail, 'pdr_status' => $pdr_status,
                'pdr_project_id' => $pdr_project_id, 'pdr_user_id' => $user_id]);
        if(count($query) < 1)
        {
          $result = array('code'=>400, "description"=>"No records found");
          return response()->json($result, 400);
        }
        else
        {
          $result = array('description'=>"Add daily report successfully",'code'=>200,'new_report_id'=>DB::getPdo()->lastInsertId());
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
   Update DAILY REPORT by passing report_id
  --------------------------------------------------------------------------
  */
  public function update_daily_report(Request $request, $report_id)
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
        $report_weather                     = $request['report_weather'];
        $report_custum_field                = $request['report_custum_field'];
        $report_perform_work_day            = $request['report_perform_work_day'];
        $report_material_delivery           = $request['report_material_delivery'];
        $report_milestone_completed         = $request['report_milestone_completed'];
        $report_milestone_detail            = $request['report_milestone_detail'];
        $report_occur_detail                = $request['report_occur_detail'];
        $report_general_notes               = $request['report_general_notes'];
        $report_picture_video               = $request['report_picture_video'];
        $report_subcontractor_work_day      = $request['report_subcontractor_work_day'];
        $report_subcontractor_work_detail   = $request['report_subcontractor_work_detail'];
        $project_id                         = $request['project_id'];
        $user_id                            = Auth::user()->id;
        $status                             = 'complete';
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'daily_construction_report_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            "project_id"                      => $project_id,
            "user_id"                         => $user_id
        );

        $rules = [
            'project_id'                      => 'required|numeric',
            'user_id'                         => 'required|numeric'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_daily_report_logs')
            ->where('pdrl_id', '=', $report_id)
            ->update(['pdr_weather' => $report_weather, 'pdr_custom_field' => $report_custum_field, 'pdr_perform_work_day' => $report_perform_work_day, 'pdr_material_delivery' =>$report_material_delivery, 'pdr_milestone_completed' =>$report_milestone_completed, 'pdr_milestone_detail' =>$report_milestone_detail, 'pdr_occur_detail' =>$report_occur_detail, 'pdr_general_notes' =>$report_general_notes, 'pdr_picture_video' =>$report_picture_video, 'pdr_sub_contractor_work' =>$report_subcontractor_work_day, 'pdr_sub_contractor_work_detail' => $report_subcontractor_work_detail, 'pdr_status' => $status, 'pdr_project_id' => $project_id, 'pdr_user_id' => $user_id]);
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
//                $user_id              = $check_project_user->id;
//                $permission_key       = 'daily_construction_report_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Daily Report has been submitted in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/daily_construction_report/".$report_id;
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Daily Report has been submitted in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
//
//                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
//                if(count($check_single_user_permission) < 1){
//                  continue;
//                }
//                else {
//                  // Send Notification to users
//                  $project_notification_query = app('App\Http\Controllers\Projects\NotificationController')->add_notification($notification_title, $link, $project_id, $check_single_user_permission[0]->pup_user_id);
//               
//                  $user_detail = array(
//                    'id'              => $check_project_user->id,
//                    'name'            => $check_project_user->username,
//                    'email'           => $check_project_user->email,
//                    'link'            => $link,
//                    'date'            => $date,
//                    'project_name'    => $check_project_user->p_name,
//                    'title'           => $notification_title,
//                    'description'     => $email_description
//                  );
//                  $user_single = (object) $user_detail;
//                  Mail::send('emails.send_notification',['user' => $user_single], function ($message) use ($user_single) {
//                      $message->from('no-reply@sw.ai', 'StratusCM');
//                      $message->to($user_single->email, $user_single->name)->subject($user_single->title);
//                  });
//                }

              } // End Foreach
              // End Check User Permission and send notification and email 

              $result = array('data'=>$query,'code'=>200);
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
   Get single Daily Report by passing pdr_id
  --------------------------------------------------------------------------
  */
  public function get_daily_report_single(Request $request, $report_id)
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
        $query = DB::table('project_daily_report_logs')
        ->leftJoin('project_firm', 'project_daily_report_logs.pdr_sub_contractor_work_detail', '=', 'project_firm.f_id')
        ->leftJoin('projects', 'project_daily_report_logs.pdr_project_id', '=', 'projects.p_id')
        ->leftJoin('users', 'project_daily_report_logs.pdr_user_id', '=', 'users.id')
        ->select('project_daily_report_logs.*', 'project_firm.f_name as sub_contractor_work_detail', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('pdr_report_id', '=', $report_id)
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
  ----------------------------------------------------------------------------------
   Get all Daily Report by passing project_id
  ----------------------------------------------------------------------------------
  */
  public function get_daily_report_project(Request $request, $project_id)
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
        $permission_key = 'daily_construction_report_view_all';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
            $query = DB::table('project_daily_report')
            ->leftJoin('projects', 'project_daily_report.pdr_project_id', '=', 'projects.p_id')
            ->leftJoin('users', 'project_daily_report.pdr_user_id', '=', 'users.id')
            //->leftJoin('project_daily_report_logs', 'project_daily_report.pdr_id', '=', 'project_daily_report_logs.pdr_report_id')
            ->select('project_daily_report.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('project_daily_report.pdr_project_id', '=', $project_id)
          ->orderBy('project_daily_report.pdr_id','ASC')
          ->get();
          if(count($query) < 1)
          {
            $result = array('code'=>404, "description"=>"No Records Found");
            return response()->json($result, 404);
          }
          else
          {
              //print_r($query);die;
            foreach($query as $key=>$data)
            {
               $query1 = DB::table('project_daily_report_logs')
                ->select('project_daily_report_logs.pdrl_id','project_daily_report_logs.pdr_report_id')
                ->where('project_daily_report_logs.pdr_report_id', '=', $data->pdr_id)
                ->orderBy('project_daily_report_logs.pdrl_id','DESC')
                ->get();
               //print_r($query1);
               if(count($query1)<1)
               {
                   $query[$key]->pdrl_id = null;
               }else{
                   $query[$key]->pdrl_id = $query1[0]->pdrl_id;
               }
            }
            
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
  ----------------------------------------------------------------------------------
   Get all Daily Report log by passing report id
  ----------------------------------------------------------------------------------
  */
  public function get_daily_report_logs_project(Request $request, $project_id,$pdr_id)
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
        $permission_key = 'daily_construction_report_view_all';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
            $query = DB::table('project_daily_report_logs')
            ->leftJoin('projects', 'project_daily_report_logs.pdr_project_id', '=', 'projects.p_id')
            ->leftJoin('users', 'project_daily_report_logs.pdr_user_id', '=', 'users.id')
            ->select('project_daily_report_logs.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('pdr_report_id', '=', $pdr_id)
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
   ADD DAILY QUANTITY COMPLETE
  --------------------------------------------------------------------------
  */
  public function add_daily_quantity_complete(Request $request, $project_id)
  {
    try
    {
      // $user = array(
      //   'id'        => Auth::user()->id,
      //   'role'      => Auth::user()->role
      // );
      // $user = (object) $user;
      // $post = new Resource_Post(); // You create a new resource Post instance
      // if (Gate::forUser($user)->denies('allow_admin', [$post,false])) { 
      //   $result = array('code'=>403, "description"=>"Access denies");
      //   return response()->json($result, 403);
      // } 
      // else { 
        $report_id                      = $request['report_id'];
        $report_item_id                 = $request['item_id'];
        $project_id                     = $project_id;
        $user_id                        = Auth::user()->id;

        $information = array(
            "report_id"                       => $report_id,
            "report_item_id"                  => $report_item_id,
            "project_id"                      => $project_id,
            "user_id"                         => $user_id
        );

        $rules = [
            'report_id'                       => 'required',
            'report_item_id'                  => 'required',
            'project_id'                      => 'required|numeric',
            'user_id'                         => 'required|numeric'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = ProjectDailyQuantityCompleted::create(['pdq_report_id' => $report_id, 'pdq_item_id' => $report_item_id, 'pdq_project_id' => $project_id, 'pdq_user_id' => $user_id]);

            $item_inserted_id = $query->id;

            if(count($query) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {
              $result = array('description'=>$report_item_id,'code'=>200);
              return response()->json($result, 200);
            }
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
   Update DAILY QUANTITY COMPLETE by passing item_id
  --------------------------------------------------------------------------
  */
  public function update_daily_quantity_complete(Request $request, $item_id, $report_id)
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
          $qty_complete_day               = $request['qty_complete_day'];
          $additional_information         = $request['additional_information'];

          $query = DB::table('project_daily_quantity_completed')
          ->where('pdq_item_id', '=', $item_id)
          ->where('pdq_report_id', '=', $report_id)
          ->update(['pdq_qty_complete_this_day' => $qty_complete_day, 'pdq_location_additional_information' => $additional_information]);
          if(count($query) < 1)
          {
            $result = array('code'=>400, "description"=>"No records found");
            return response()->json($result, 400);
          }
          else
          {
            $result = array('data'=>'update item quantity use per day','code'=>200);
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
  ----------------------------------------------------------------------------------
   Get Daily quantity complete by passing order_id
  ----------------------------------------------------------------------------------
  */
  public function get_daily_quantity_complete(Request $request, $report_id)
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
          $query = DB::table('project_daily_quantity_completed')
          ->leftJoin('project_bid_items', 'project_daily_quantity_completed.pdq_item_id', '=', 'project_bid_items.pbi_id')
          ->select('project_daily_quantity_completed.*', 'project_bid_items.*')
          ->where('pdq_report_id', '=', $report_id)
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
   ADD DAILY RESOURCE USED
  --------------------------------------------------------------------------
  */
  public function add_daily_resource_used(Request $request, $project_id)
  {
    try
    {
      // $user = array(
      //   'id'        => Auth::user()->id,
      //   'role'      => Auth::user()->role
      // );
      // $user = (object) $user;
      // $post = new Resource_Post(); // You create a new resource Post instance
      // if (Gate::forUser($user)->denies('allow_admin', [$post,false])) { 
      //   $result = array('code'=>403, "description"=>"Access denies");
      //   return response()->json($result, 403);
      // } 
      // else { 
        $report_id                      = $request['report_id'];
        $item_id                        = $request['item_id'];
        $resourse_detail                = $request['resourse_detail'];
        $time                           = $request['time'];
        $time_type                      = $request['time_type'];
        $project_id                     = $project_id;
        $user_id                        = Auth::user()->id;

        
            $query = DB::table('project_daily_resource_used')
            ->insert(['pdu_report_id' => $report_id, 'pdu_item_id' => $item_id, 'pdu_resourse_detail' => $resourse_detail, 'pdu_time' => $time, 'pdu_time_type' =>$time_type, 'pdu_project_id' =>$project_id, 'pdu_user_id' =>$user_id]);

            if(count($query) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {
              $result = array('description'=>'add resources successfully','code'=>200);
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
  ----------------------------------------------------------------------------------
   Get Daily resource user by passing order_id
  ----------------------------------------------------------------------------------
  */
  public function get_daily_resource_used(Request $request, $report_id)
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
          $query = DB::table('project_daily_resource_used')
          ->leftJoin('project_bid_items', 'project_daily_resource_used.pdu_item_id', '=', 'project_bid_items.pbi_id')
          ->select('project_daily_resource_used.*', 'project_bid_items.*')
          ->where('pdu_report_id', '=', $report_id)
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
   ADD DAILY MATERIAL DELIVERED
  --------------------------------------------------------------------------
  */
  public function add_material_delivered(Request $request, $project_id)
  {
    try
    {
      // $user = array(
      //   'id'        => Auth::user()->id,
      //   'role'      => Auth::user()->role
      // );
      // $user = (object) $user;
      // $post = new Resource_Post(); // You create a new resource Post instance
      // if (Gate::forUser($user)->denies('allow_admin', [$post,false])) { 
      //   $result = array('code'=>403, "description"=>"Access denies");
      //   return response()->json($result, 403);
      // } 
      // else { 
        $report_id                      = $request['report_id'];
        $material_name                  = $request['material_name'];
        $material_unit                  = $request['material_unit'];
        $material_unit_type             = $request['material_unit_type'];
        $upload_id                      = $request['upload_id'];
        $project_id                     = $project_id;
        $user_id                        = Auth::user()->id;

        
            $query = DB::table('project_daily_material_deliver')
            ->insert(['pdm_report_id' => $report_id, 'pdm_item_id' => $material_name, 'pdm_unit' => $material_unit, 'pdm_unit_type' => $material_unit_type, 'pdm_upload_id' =>$upload_id, 'pdm_project_id' =>$project_id, 'pdm_user_id' =>$user_id]);

            if(count($query) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {
              $result = array('description'=>'add resources successfully','code'=>200);
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
  ----------------------------------------------------------------------------------
   Get Daily material delivered by passing order_id
  ----------------------------------------------------------------------------------
  */
  public function get_daily_material_delivered(Request $request, $report_id)
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
          $query = DB::table('project_daily_material_deliver')
          ->leftJoin('project_bid_items', 'project_daily_material_deliver.pdm_item_id', '=', 'project_bid_items.pbi_id')
          ->leftJoin('documents', 'project_daily_material_deliver.pdm_upload_id', '=', 'documents.doc_id')
          ->select('project_daily_material_deliver.*', 'project_bid_items.*', 'documents.*')
          ->where('pdm_report_id', '=', $report_id)
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
   ADD DAILY PHOTO / VIDEO
  --------------------------------------------------------------------------
  */
  public function add_photo_video(Request $request, $project_id)
  {
    try
    {
      // $user = array(
      //   'id'        => Auth::user()->id,
      //   'role'      => Auth::user()->role
      // );
      // $user = (object) $user;
      // $post = new Resource_Post(); // You create a new resource Post instance
      // if (Gate::forUser($user)->denies('allow_admin', [$post,false])) { 
      //   $result = array('code'=>403, "description"=>"Access denies");
      //   return response()->json($result, 403);
      // } 
      // else { 
        $report_id                      = $request['report_id'];
        $description                    = $request['description'];
        $upload_id                      = $request['upload_id'];
        $project_id                     = $project_id;
        $user_id                        = Auth::user()->id;

        
            $query = DB::table('project_daily_picture')
            ->insert(['pdp_report_id' => $report_id, 'pdp_description' => $description, 'pdp_upload_id' =>$upload_id, 'pdp_project_id' =>$project_id, 'pdp_user_id' =>$user_id]);

            if(count($query) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {
              $result = array('description'=>'add photo / video successfully','code'=>200);
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
  ----------------------------------------------------------------------------------
   Get Daily photo video by passing order_id
  ----------------------------------------------------------------------------------
  */
  public function get_daily_video_photo(Request $request, $report_id)
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
          $query = DB::table('project_daily_picture')
          ->leftJoin('documents', 'project_daily_picture.pdp_upload_id', '=', 'documents.doc_id')
          ->select('project_daily_picture.*', 'documents.*')
          ->where('pdp_report_id', '=', $report_id)
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
  ----------------------------------------------------------------------------------
   Get Weather
  ----------------------------------------------------------------------------------
  */
  public function get_weather(Request $request)
  {
      try
      {
          // $weather =  YahooWeather::Country('egypt','ar');
          $weather =  YahooWeather::YQL('select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="karachi, as")');
          print_r($weather);
          // echo $weather['high'];
          // echo $weather['low'];
          // echo $weather['image'];
          // echo $weather['name'];
          // echo $weather['description'];  
          // $BASE_URL = "http://query.yahooapis.com/v1/public/yql";
          // $yql_query = 'select wind from weather.forecast where woeid in (select woeid from geo.places(1) where text="chicago, il")';
          // $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
          // // Make call with cURL
          // $session = curl_init($yql_query_url);
          // curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
          // $json = curl_exec($session);
          // // Convert JSON to PHP object
          //  $phpObj =  json_decode($json);
          // var_dump($phpObj);

          // include(app_path().'/yahoo/yosdk/lib/Yahoo.inc');
          // define("API_KEY","dj0yJmk9YWRLVElDT000Tmd3JmQ9WVdrOWJuTldZVVpwTjJjbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD0yYg--");
          // define("SHARED_SECRET","ead46dee52f8a869be2d663ba2893f4f370b34d4");
          // YahooLogger::setDebug(true);

          // $twoleg = new YahooApplication (API_KEY, SHARED_SECRET);
          // $query = 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="84054") and u="f"';
          // print_r ($results);
      }
      catch(Exception $e)
      {
        return response()->json(['error' => 'Something is wrong'], 500);
      }
  }


}