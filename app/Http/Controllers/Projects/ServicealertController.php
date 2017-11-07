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


class ServicealertController extends Controller {

  /* 
  --------------------------------------------------------------------------
   ADD SERVICE ALERT
  --------------------------------------------------------------------------
  */
  public function add_service_alert(Request $request)
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
        $date_called_in         = $request['date_called_in'];
        $date_called_valid      = $request['date_called_valid'];
        $ticket_number          = $request['ticket_number'];
        $ticket_location        = $request['ticket_location'];
        $expire_date            = $request['expire_date'];
        $status                 = 'active'; // $request['status'];
        $work_completed         = 'no';  // $request['work_completed'];
        $project_id             = $request['project_id'];
        $user_id                = Auth::user()->id;
        $req_status             = 'active';
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'service_alert_add';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            "date_called_in"      => $date_called_in,
            "date_called_valid"   => $date_called_valid,
            "ticket_number"       => $ticket_number,
            "ticket_location"     => $ticket_location,
            "expire_date"         => $expire_date,
            "status"              => $status,
            "work_completed"      => $work_completed,
            "project_id"          => $project_id,
            "user_id"             => $user_id,
            "req_status"          => $req_status
        );

        $rules = [
            'date_called_in'      => 'required',
            'date_called_valid'   => 'required',
            'ticket_number'       => 'required',
            'ticket_location'     => 'required',
            'expire_date'         => 'required',
            'status'              => 'required',
            'work_completed'      => 'required',
            'project_id'          => 'required|numeric',
            'user_id'             => 'required|numeric',
            'req_status'          => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_service_alert')
            ->insert(['psa_date_called_in' => $date_called_in, 'psa_date_called_valid' => $date_called_valid, 'psa_ticket_number' => $ticket_number, 'psa_address' => $ticket_location, 'psa_expire_date' => $expire_date, 'psa_status' => $status, 'psa_work_completed' => $work_completed, 'psa_project_id' => $project_id, 'psa_user_id' => $user_id, 'psa_req_status' => $req_status]);

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
                $permission_key       = 'service_alert_view_all';
                // Notification Parameter
                $project_id           = $project_id;
                //$notification_title   = 'Add new Underground Service Alert in Project: ' .$check_project_user->p_name;
                $notification_title     = 'New underground service alert added in Project';
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/service_alert";
                $date                 = date("M d, Y h:i a");
                $email_description    = 'A new Underground Service Alert added in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
                if(count($check_single_user_permission) < 1){
                  continue;
                }
                else {
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

              } // End Foreach
              // End Check User Permission and send notification and email 

              $result = array('description'=>"Add service alert successfully",'code'=>200);
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
   Update Service Alert by passing psa_id
  --------------------------------------------------------------------------
  */
  public function update_service_alert(Request $request, $psa_id)
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
        // $date_called_in         = $request['date_called_in'];
        // $date_called_valid      = $request['date_called_valid'];
        // $ticket_number          = $request['ticket_number'];
        // $location_lat           = $request['location_lat'];
        // $location_long          = $request['location_long'];
        // $expire_date            = $request['expire_date'];
        $status                 = $request['status'];
        $work_completed         = $request['work_completed'];
        $project_id             = $request['project_id'];
        $user_id                = Auth::user()->id;
        $request_status         = 'active'; // $request['request_status'];
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'service_alert_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            // "date_called_in"      => $date_called_in,
            // "date_called_valid"   => $date_called_valid,
            // "ticket_number"       => $ticket_number,
            "status"              => $status,
            "work_completed"      => $work_completed,
            "project_id"          => $project_id,
            "user_id"             => $user_id,
            "request_status"      => $request_status
        );

        $rules = [
            // 'date_called_in'      => 'required',
            // 'date_called_valid'   => 'required',
            // 'ticket_number'       => 'required',
            'status'              => 'required',
            'work_completed'      => 'required',
            'project_id'          => 'required|numeric',
            'user_id'             => 'required|numeric',
            'request_status'      => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_service_alert')
            ->where('psa_id', '=', $psa_id)
            // ->update(['psa_date_called_in' => $date_called_in, 'psa_date_called_valid' => $date_called_valid, 'psa_ticket_number' => $ticket_number, 'psa_lat' => $location_lat, 'psa_long' =>$location_long, 'psa_expire_date' => $expire_date, 'psa_status' => $status, 'psa_work_completed' => $work_completed, 'psa_project_id' => $project_id, 'psa_user_id' => $user_id, 'psa_req_status' => $request_status]);
            ->update(['psa_status' => $status, 'psa_work_completed' => $work_completed, 'psa_project_id' => $project_id, 'psa_user_id' => $user_id, 'psa_req_status' => $request_status]);
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
//                $permission_key       = 'service_alert_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Update Status Underground Service Alert # '.$psa_id.' in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/service_alert";
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Update Status Underground Service Alert # '.$psa_id.' in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
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
   Get single Service Alert by passing psa_id
  --------------------------------------------------------------------------
  */
  public function get_service_alert_single(Request $request, $psa_id)
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
        $query = DB::table('project_service_alert')
->leftJoin('projects', 'project_service_alert.psa_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_service_alert.psa_user_id', '=', 'users.id')
        ->select('project_service_alert.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('psa_id', '=', $psa_id)
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
   Get all Service Ticket by passing project_id
  ----------------------------------------------------------------------------------
  */
  public function get_service_alert_project(Request $request, $project_id)
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
        $permission_key = 'service_alert_view_all';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
          $query = DB::table('project_service_alert')
->leftJoin('projects', 'project_service_alert.psa_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_service_alert.psa_user_id', '=', 'users.id')
        ->select('project_service_alert.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('psa_project_id', '=', $project_id)
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

}