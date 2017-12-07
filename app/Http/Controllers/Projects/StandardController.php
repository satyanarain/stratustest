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
// use App\Http\Controllers\Projects\PermissionController;


class StandardController extends Controller {

    /*
    --------------------------------------------------------------------------
    Add Standards
    --------------------------------------------------------------------------
    */
    public function add_standards(Request $request)
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

            $agency_name     = $request['agency_name'];
            $name            = $request['name'];
            $date            = $request['date'];
            $url             = $request['url'];
            $applicable      = $request['applicable'];
            $type            = $request['type'];
            $upload_file     = $request['upload_file'];
            $project_id      = $request['project_id'];
            $user_id         = Auth::user()->id;
            $status          = 'active';
            
            // Start Permission Process
            $user_id_permission = Auth::user()->id;
            $permission_query = DB::table('project_user_permission')
            ->select()
            ->where('pup_project_id', '=', $project_id)
            ->where('pup_user_id', '=', $user_id_permission)
            ->where('pup_permission_key', '=', 'standard_add')
            ->get();
            if(count($permission_query) < 1)
            {
              $result = array('code'=>403, "description"=>"Access Denies");
              return response()->json($result, 404);
            }
            else
            {
              // $result = array('data'=>$permission_query,'code'=>200);
              $information = array(
                  "agency_name"     => $agency_name,
                  "name"            => $name,
                  "date"            => $date,
                  "type"            => $type,
                  "upload_file"     => $upload_file,
                  "project_id"      => $project_id,
                  "user_id"         => $user_id,
                  "status"          => $status,
              );

              // if($type == 'standard'){
              //   $rules = [
              //     'agency_name'     => 'required',
              //     'name'            => 'required',
              //     'date'            => 'required',
              //     'type'            => 'required',
              //     'upload_file'     => 'required',
              //     'project_id'      => 'required|numeric',
              //     'user_id'         => 'required|numeric',
              //     'status'          => 'required',
              //   ];  
              // }
              // else {
                $rules = [
                  'agency_name'     => 'required',
                  'name'            => 'required',
                  'date'            => 'required',
                  'type'            => 'required',
                  'project_id'      => 'required|numeric',
                  'user_id'         => 'required|numeric',
                  'status'          => 'required',
                ];
              // }
              
              $validator = Validator::make($information, $rules);
              if ($validator->fails()) 
              {
                  return $result = response()->json(["data" => $validator->messages()],400);
              }
              else
              {
                  $query = DB::table('project_standards')
                  ->insertGetId(['ps_agency_name' => $agency_name, 'ps_name' => $name, 'ps_date' => $date, 'ps_url' => $url, 'ps_applicable' => $applicable, 'ps_type' => $type, 'ps_file_path' => $upload_file, 'ps_project_id' => $project_id, 'ps_user_parent' => $user_id, 'ps_status' => $status]);

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
                      $permission_key       = 'standard_view_all';
                      $notification_key     = 'standards';
                      // Notification Parameter
                      $project_id           = $project_id;
                      $notification_title   = 'New standard added in Project.';
                      $url                  = App::make('url')->to('/');
                      $link                 = "/dashboard/".$project_id."/standards";
                      $date                 = date("M d, Y h:i a");

                      if($applicable == 'no'){
                        $email_description    = 'A new standard added in Project: <strong>'.$check_project_user->p_name.'</strong> that standard is not applicable yet <a href="'.$url.$link.'"> Click Here to see </a>';
                      }
                      else {
                        $email_description    = 'A new standard added in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
                      }

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

                    // End Check User Permission and send notification and email  

                    $result = array('description'=>"Add standard or specification successfully",'last_insert_id' => $query, 'code'=>200);
                    return response()->json($result, 200);
                  // }
              }
            }
            // End Permission Process

          }
        }
        catch(Exception $e)
        {
          return response()->json(['error' => 'Something is wrong'], 500);
        }
    }

    /*
    --------------------------------------------------------------------------
    Update Standard by passing Spec_id
    --------------------------------------------------------------------------
    */
    public function update_standards(Request $request, $spec_id)
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

            $project_id       = $request['project_id'];
            $user_id          = Auth::user()->id;
            $status           = $request['status'];
            
            // Start Permission Process
            $user_id = Auth::user()->id;
            $permission_key = 'standard_update';
            $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
            if(count($check_single_user_permission) < 1){
              $result = array('code'=>403, "description"=>"Access Denies");
              return response()->json($result, 403);
            }
            else {
              // $result = array('data'=>$permission_query,'code'=>200);
              $information = array(
                  "project_id"      => $project_id,
                  "user_id"         => $user_id,
                  "status"          => $status,
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

                $detail = DB::table('project_standards')
                ->select()
                ->where('ps_id', '=', $spec_id)
                ->first();

                $query = DB::table('project_standards')
                ->where('ps_id', '=', $spec_id)
                ->update(['ps_project_id' => $project_id, 'ps_user_parent' => $user_id, 'ps_status' => $status]);
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
//                    $user_id              = $check_project_user->id;
//                    $permission_key       = 'standard_view_all';
//                    // Notification Parameter
//                    $project_id           = $project_id;
//                    $notification_title   = 'Change status standard # '.$spec_id.' in Project: ' .$check_project_user->p_name;
//                    $url                  = App::make('url')->to('/');
//                    $link                 = "/dashboard/".$project_id."/standards";
//                    $date                 = date("M d, Y h:i a");
//                    $email_description    = 'Change status standard # '.$spec_id.' in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
//
//                    $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
//                    if(count($check_single_user_permission) < 1){
//                      continue;
//                    }
//                    else {
//                      // Send Notification to users
//                      $project_notification_query = app('App\Http\Controllers\Projects\NotificationController')->add_notification($notification_title, $link, $project_id, $check_single_user_permission[0]->pup_user_id);
//                   
//                      $user_detail = array(
//                        'id'              => $check_project_user->id,
//                        'name'            => $check_project_user->username,
//                        'email'           => $check_project_user->email,
//                        'link'            => $link,
//                        'date'            => $date,
//                        'project_name'    => $check_project_user->p_name,
//                        'title'           => $notification_title,
//                        'description'     => $email_description
//                      );
//                      $user_single = (object) $user_detail;
//                      Mail::send('emails.send_notification',['user' => $user_single], function ($message) use ($user_single) {
//                          $message->from('no-reply@sw.ai', 'StratusCM');
//                          $message->to($user_single->email, $user_single->name)->subject($user_single->title);
//                      });
//                    }

                  } // End Foreach

                  // End Check User Permission and send notification and email  

                  $result = array('data'=>$query,'code'=>200);
                  return response()->json($result, 200);
                }
              }
            }
            // End Permission Process
          // }
        }
        catch(Exception $e)
        {
          return response()->json(['error' => 'Something is wrong'], 500);
        }
    }

    /*
    --------------------------------------------------------------------------
    Get single Standard or Specification by passing spec_id
    --------------------------------------------------------------------------
    */
    public function get_standards_single(Request $request, $spec_id)
    {
        try
        {
          // $spec_id            = $spec_id;
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
            $query = DB::table('project_standards')
            ->join('project_firm', 'project_standards.ps_agency_name', '=', 'project_firm.f_id')
            ->leftJoin('documents', 'project_standards.ps_file_path', '=', 'documents.doc_id')
            ->leftJoin('projects', 'project_standards.ps_project_id', '=', 'projects.p_id')
            ->leftJoin('users', 'project_standards.ps_user_parent', '=', 'users.id')
            ->select('project_standards.*', 'project_firm.f_name as agency_name', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
            ->where('ps_id', '=', $spec_id)
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
    Get Specific Project Specification or Standard by padding project ID & Spec Type  
    ----------------------------------------------------------------------------------
    */
    public function get_standards_project(Request $request, $project_id, $spec_type)
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

          // Check User Permission Parameter 
          $user_id = Auth::user()->id;
          $permission_key = 'standard_view_all';
          $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
          if(count($check_single_user_permission) < 1){
            $result = array('code'=>403, "description"=>"Access Denies");
            return response()->json($result, 403);
          }
          else {
            $query = DB::table('project_standards')
            ->join('project_firm', 'project_standards.ps_agency_name', '=', 'project_firm.f_id')
            ->leftJoin('documents', 'project_standards.ps_file_path', '=', 'documents.doc_id')
            ->leftJoin('projects', 'project_standards.ps_project_id', '=', 'projects.p_id')
            ->leftJoin('users', 'project_standards.ps_user_parent', '=', 'users.id')
            ->select('project_standards.*', 'project_firm.f_name as agency_name', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
            ->where('ps_project_id', '=', $project_id)
            ->where('ps_type', '=', $spec_type)
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
          // End Permission Process
        // }
      }
      catch(Exception $e)
      {
        return response()->json(['error' => 'Something is wrong'], 500);
      }
    } 
}