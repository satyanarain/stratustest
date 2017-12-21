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


class SwpppController extends Controller {

  /*
  --------------------------------------------------------------------------
   Add SWPPP
  --------------------------------------------------------------------------
  */
  public function add_swppp(Request $request)
  {
    try
    {

      $date         = $request['date'];
      $name         = $request['name'];
      $firm_name    = $request['firm_name'];
      $applicable   = $request['applicable'];
      $available    = $request['available'];
      $file_path    = $request['file_path'];
      $type         = $request['type'];
      $project_id   = $request['project_id'];
      $wdid_no         = $request['wdid_no'];
      $wdid_expiration_date   = $request['wdid_expiration_date'];
      $user_id      = Auth::user()->id;
      $status       = 'active';

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
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'swppp_add';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        
        $information = array(
            "date"        => $date,
            "name"        => $name,
            "firm_name"   => $firm_name,
            "applicable"  => $applicable,
            "available"   => $available,
            "file_path"   => $file_path,
            "type"        => $type,
            "project_id"  => $project_id,
            "user_id"     => $user_id,
            "wdid_no"       => $wdid_no,
            "wdid_expiration_date"     => $wdid_expiration_date          
        );

        if($available == "yes"){
          $rules = [
              'date'        => 'required',
              'name'        => 'required',
              'firm_name'   => 'required',
              'file_path'   => 'required',
              'applicable'  => 'required',
              'available'   => 'required',
              'type'        => 'required',
              'project_id'  => 'required|numeric',
              'user_id'     => 'required|numeric',
              'wdid_no'     => 'required',
              'wdid_expiration_date'     => 'required'
          ];
        }
        else {
          $rules = [
              'date'        => 'required',
              'name'        => 'required',
              'firm_name'   => 'required',
              'applicable'  => 'required',
              'available'   => 'required',
              'type'        => 'required',
              'project_id'  => 'required|numeric',
              'user_id'     => 'required|numeric',
              'wdid_no'     => 'required',
              'wdid_expiration_date'     => 'required'
          ];
        }
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
             $query = DB::table('project_swppp')
            ->insert(['wdid_no' => $wdid_no, 'wdid_expiration_date' => $wdid_expiration_date,'sw_date' => $date, 'sw_name' => $name, 'sw_firm_name' => $firm_name, 'sw_applicable' => $applicable, 'sw_available' => $available, 'sw_file_path' => $file_path, 'sw_type' => $type, 'sw_project_id' => $project_id, 'sw_user_id' => $user_id, 'sw_status' => $status]);

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
                $permission_key       = 'swppp_view_all';
                // Notification Parameter
                $project_id           = $project_id;
                $notification_title   = 'New SWPPP/WPCP added in Project.';
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/swppp";
                $date                 = date("M d, Y h:i a");
                $email_description    = 'A new SWPPP / WPCP has been added in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

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

              $result = array('description'=>"Add SWPPP / WPCP successfully",'code'=>200);
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
   Update SWPPP by passing sw_id
  --------------------------------------------------------------------------
  */
  public function update_swppp(Request $request, $sw_id)
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
        $project_id   = $request['project_id'];
        $user_id      = Auth::user()->id;
        $status       = $request['status'];
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'swppp_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {   
        $information = array(
            "project_id"  => $project_id,
            "user_id"     => $user_id,
            "status"      => $status
        );

        $rules = [
            'project_id'  => 'required|numeric',
            'user_id'     => 'required|numeric',
            'status'      => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
          // $detail = DB::table('project_swppp')
          //     ->select()
          //     ->where('sw_id', '=', $sw_id)
          //     ->first();

// if(($available == "") ? $available = $detail->sw_available : $available = $available);
// if(($file_path == "") ? $file_path = $detail->sw_file_path : $file_path = $file_path);

            $query = DB::table('project_swppp')
            ->where('sw_id', '=', $sw_id)
            // ->update(['sw_date' => $date, 'sw_name' => $name, 'sw_firm_name' => $firm_name, 'sw_applicable' => $applicable, 'sw_available' => $available, 'sw_file_path' => $file_path, 'sw_type' => $type, 'sw_project_id' => $project_id, 'sw_user_id' => $user_id, 'sw_status' => $status]);
            ->update(['sw_project_id' => $project_id, 'sw_user_id' => $user_id, 'sw_status' => $status]);
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
//                $permission_key       = 'swppp_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Change status SWPPP / WPCP # '.$sw_id.' in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/swppp";
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Change status SWPPP / WPCP # '.$sw_id.' in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
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
   Get single SWPPP by passing sw_id
  --------------------------------------------------------------------------
  */
  public function get_swppp_single(Request $request, $sw_id)
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
        $query = DB::table('project_swppp')
        ->leftJoin('project_firm', 'project_swppp.sw_firm_name', '=', 'project_firm.f_id')
        ->leftJoin('documents', 'project_swppp.sw_file_path', '=', 'documents.doc_id')
        ->leftJoin('projects', 'project_swppp.sw_project_id', '=', 'projects.p_id')
        ->leftJoin('users', 'project_swppp.sw_user_id', '=', 'users.id')
        ->select('project_swppp.*', 'project_firm.*', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('sw_id', '=', $sw_id)
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
   Get all SWPPP by passing Project ID
  ----------------------------------------------------------------------------------
  */
  public function get_swppp_project(Request $request, $project_id)
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
        $permission_key = 'swppp_view_all';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
          $query = DB::table('project_swppp')
          ->leftJoin('project_firm', 'project_swppp.sw_firm_name', '=', 'project_firm.f_id')
          ->leftJoin('documents', 'project_swppp.sw_file_path', '=', 'documents.doc_id')
          ->leftJoin('projects', 'project_swppp.sw_project_id', '=', 'projects.p_id')
          ->leftJoin('users', 'project_swppp.sw_user_id', '=', 'users.id')
          ->select('project_swppp.*', 'project_firm.*', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('sw_project_id', '=', $project_id)
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