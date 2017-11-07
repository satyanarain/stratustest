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


class GeoreportController extends Controller {

    /*
  --------------------------------------------------------------------------
   Add Geo Technical Report
  --------------------------------------------------------------------------
  */
  public function add_geo_reports(Request $request)
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
        $date_of_report  = $request['date_of_report'];
        $name_of_report  = $request['name_of_report'];
        $name_of_firm    = $request['name_of_firm'];
        $applicable      = $request['applicable'];
        $available       = $request['upload'];
        $file_path       = $request['file_path'];
        $project_id      = $request['project_id'];
        $user_id         = Auth::user()->id;
        $status          = 'active';
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'geotechnical_add';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {  
        $information = array(
            "date_of_report" => $date_of_report,
            "name_of_report" => $name_of_report,
            "name_of_firm"   => $name_of_firm,
            "applicable"     => $applicable,
            "available"      => $available,
            "project_id"     => $project_id,
            "user_id"        => $user_id,
            "status"         => $status,
        );

        $rules = [
            'date_of_report'  => 'required',
            'name_of_report'  => 'required',
            'name_of_firm'    => 'required',
            'applicable'      => 'required',
            'available'       => 'required',
            'project_id'      => 'required|numeric',
            'user_id'         => 'required|numeric',
            'status'          => 'required',
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_geo_reports')
            ->insert(['geo_date_of_report' => $date_of_report, 'geo_name_of_report' => $name_of_report, 'geo_name_of_firm' => $name_of_firm, 'geo_application' => $applicable, 'geo_available' => $available, 'geo_file_path' => $file_path, 'geo_project_id' => $project_id, 'geo_user_parent' => $user_id, 'geo_status' => $status]);

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
//                $permission_key       = 'geotechnical_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Add new Geotechnical Report in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/geo_reports";
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'New Geotechnical Report added in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
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

              $result = array('description'=>"Add geo technical report successfully",'code'=>200);
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
   Update Geo Technical Report by passing geo_id
  --------------------------------------------------------------------------
  */
  public function update_geo_reports(Request $request, $geo_id)
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
        $project_id      = $request['project_id'];
        $status          = $request['status'];
        $user_id         = Auth::user()->id;
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'geotechnical_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        
        $information = array(
            "project_id"     => $project_id,
            "status"         => $status,
            "user_id"        => $user_id,
        );

        $rules = [
            'project_id'      => 'required|numeric',
            'user_id'         => 'required|numeric',
            'status'          => 'required',
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {


           $detail = DB::table('project_geo_reports')
              ->select()
              ->where('geo_id', '=', $geo_id)
              ->first();

// if(($available == "") ? $available = $detail->geo_available : $available = $available);

            $query = DB::table('project_geo_reports')
            ->where('geo_id', '=', $geo_id)
            // ->update(['geo_date_of_report' => $date_of_report, 'geo_name_of_report' => $name_of_report, 'geo_name_of_firm' => $name_of_firm, 'geo_application' => $applicable, 'geo_available' => $available, 'geo_file_path' => $file_path, 'geo_project_id' => $project_id, 'geo_user_parent' => $user_id, 'geo_status' => $status]);
            ->update(['geo_project_id' => $project_id, 'geo_user_parent' => $user_id, 'geo_status' => $status]);
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
//                $permission_key       = 'geotechnical_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Change status Geotechnical Report # '.$geo_id.' in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/geo_reports";
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Change status Geotechnical Report # '.$geo_id.' in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
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
   Get single Geo Technical Report by passing geo_id
  --------------------------------------------------------------------------
  */
  public function get_geo_reports_single(Request $request, $geo_id)
  {
    try
    {
      $geo_id            = $geo_id;
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
        $query = DB::table('project_geo_reports')
        ->leftJoin('project_firm', 'project_geo_reports.geo_name_of_report', '=', 'project_firm.f_id')
        ->leftJoin('documents', 'project_geo_reports.geo_file_path', '=', 'documents.doc_id')
        ->leftJoin('projects', 'project_geo_reports.geo_project_id', '=', 'projects.p_id')
        ->leftJoin('users', 'project_geo_reports.geo_user_parent', '=', 'users.id')
        ->select('project_geo_reports.*', 'project_firm.f_name as agency_name', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('geo_id', '=', $geo_id)
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
   Get Geo Technical Report  by padding project ID
  ----------------------------------------------------------------------------------
  */
  public function get_geo_reports_project(Request $request, $project_id)
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
        $permission_key = 'geotechnical_view_all';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
          $query = DB::table('project_geo_reports')
          ->leftJoin('project_firm', 'project_geo_reports.geo_name_of_firm', '=', 'project_firm.f_id')
          ->leftJoin('documents', 'project_geo_reports.geo_file_path', '=', 'documents.doc_id')
          ->leftJoin('projects', 'project_geo_reports.geo_project_id', '=', 'projects.p_id')
          ->leftJoin('users', 'project_geo_reports.geo_user_parent', '=', 'users.id')
          ->select('project_geo_reports.*', 'project_firm.f_name as agency_name', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('geo_project_id', '=', $project_id)
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