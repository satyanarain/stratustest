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


class MinutesMeetingController extends Controller {
  /*
  --------------------------------------------------------------------------
   ADD MINUTES MEETING
  --------------------------------------------------------------------------
  */
  public function add_minutes_meeting(Request $request)
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
        $contractor_id        = $request['contractor_id'];
        $date                 = $request['date'];
        $description          = $request['description'];
        $special              = $request['special'];
        $agenda_path          = $request['agenda_path'];
        $signin_sheet_path    = $request['signin_sheet_path'];
        $meeting_minutes_path = $request['meeting_minutes_path'];
        $project_id           = $request['project_id'];
        $user_id              = Auth::user()->id;
        $status               = 'active';
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'meeting_minutes_add';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            "contractor_id"         => $contractor_id,
            "date"                  => $date,
            "description"           => $description,
            "special"               => $special,
            "meeting_minutes_path"  => $meeting_minutes_path,
            "project_id"            => $project_id,
            "user_id"               => $user_id,
            "status"                => $status,
            "pm_agenda_path"        =>$agenda_path
        );

        $rules = [
            'contractor_id'         => 'required',
            'date'                  => 'required',
            'description'           => 'required',
            'special'               => 'required',
            'meeting_minutes_path'  => 'required',
            'project_id'            => 'required|numeric',
            'user_id'               => 'required|numeric',
            'status'                => 'required',
            'pm_agenda_path'=>'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_preconstruction_meeting_documents')
            ->insert(['pm_contractor_id' => $contractor_id, 'pm_date' => $date, 'pm_description' => $description, 'pm_special' => $special, 'pm_agenda_path' => $agenda_path, 'pm_signin_sheet_path' => $signin_sheet_path, 'pm_meeting_minutes_path' => $meeting_minutes_path, 'pm_project_id' => $project_id, 'pm_user_id' => $user_id, 'pm_status' => $status]);

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
                $permission_key       = 'meeting_minutes_view_all';
                // Notification Parameter
                $project_id           = $project_id;
                //$notification_title   = 'Add new meeting minutes in Project: ' .$check_project_user->p_name;
                $notification_title   = 'New meeting minutes added in Project.';
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/minutes_meeting";
                $date                 = date("M d, Y h:i a");
                $email_description    = 'New meeting minutes have been added in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
                if(count($check_single_user_permission) < 1){
                  continue;
                }
                else {
                    $notification_key     = 'meeting_minutes';
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

              $result = array('description'=>"Added successfully",'code'=>200);
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
   Update Minutes of Meeting by passing con_id
  --------------------------------------------------------------------------
  */
  public function update_minutes_meeting(Request $request, $pm_id)
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
        // $contractor_id        = $request['contractor_id'];
        // $date                 = $request['date'];
        // $description          = $request['description'];
        // $special              = $request['special'];
        // $agenda_path          = $request['agenda_path'];
        // $signin_sheet_path    = $request['signin_sheet_path'];
        // $meeting_minutes_path = $request['meeting_minutes_path'];
        $project_id           = $request['project_id'];
        $user_id              = Auth::user()->id;
        $status               = $request['status'];
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'meeting_minutes_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            // "contractor_id"       => $contractor_id,
            // "date"                => $date,
            // "description"         => $description,
            "project_id"          => $project_id,
            "user_id"             => $user_id,
            "status"              => $status
        );

        $rules = [
            // 'contractor_id'       => 'required',
            // 'date'                => 'required',
            // 'description'         => 'required',
            'project_id'          => 'required|numeric',
            'user_id'             => 'required|numeric',
            'status'              => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_preconstruction_meeting_documents')
            ->where('pm_id', '=', $pm_id)
            // ->update(['pm_contractor_id' => $contractor_id, 'pm_date' => $date, 'pm_description' => $description, 'pm_special' => $special, 'pm_agenda_path' => $agenda_path, 'pm_signin_sheet_path' => $signin_sheet_path, 'pm_meeting_minutes_path' => $meeting_minutes_path, 'pm_project_id' => $project_id, 'pm_user_id' => $user_id, 'pm_status' => $status]);
            ->update(['pm_project_id' => $project_id, 'pm_user_id' => $user_id, 'pm_status' => $status]);
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
//                $permission_key       = 'meeting_minutes_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Update meeting minutes # '.$pm_id.' in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/minutes_meeting";
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Update meeting minutes # '.$pm_id.' in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
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
   Get single Minutes of Meeting by passing pm_id
  --------------------------------------------------------------------------
  */
  public function get_minutes_meeting_single(Request $request, $pm_id)
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
        $query = DB::table('project_preconstruction_meeting_documents')
->leftJoin('project_firm as contractor_name', 'project_preconstruction_meeting_documents.pm_contractor_id', '=', 'contractor_name.f_id')
->leftJoin('project_firm as contractor_description', 'project_preconstruction_meeting_documents.pm_description', '=', 'contractor_description.f_id')
->leftJoin('documents as agenda_path', 'project_preconstruction_meeting_documents.pm_agenda_path', '=', 'agenda_path.doc_id')
->leftJoin('documents as signin_sheet_path', 'project_preconstruction_meeting_documents.pm_signin_sheet_path', '=', 'signin_sheet_path.doc_id')
->leftJoin('documents as meeting_minutes_path', 'project_preconstruction_meeting_documents.pm_meeting_minutes_path', '=', 'meeting_minutes_path.doc_id')
->leftJoin('projects', 'project_preconstruction_meeting_documents.pm_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_preconstruction_meeting_documents.pm_user_id', '=', 'users.id')
        ->select('contractor_name.f_name as contractor_name',
          'contractor_description.f_name as contractor_description',
          'agenda_path.doc_path as agenda_path',  
          'signin_sheet_path.doc_path as signin_sheet_path',  
          'meeting_minutes_path.doc_path as meeting_minutes_path',  
          'project_preconstruction_meeting_documents.*', 'projects.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('pm_id', '=', $pm_id)
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
   Get all Minutes of Meeting by passing Project ID
  ----------------------------------------------------------------------------------
  */
  public function get_minutes_meeting_project(Request $request, $project_id)
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
        $permission_key = 'meeting_minutes_view_all';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
          $query = DB::table('project_preconstruction_meeting_documents')
->leftJoin('project_firm as contractor_name', 'project_preconstruction_meeting_documents.pm_contractor_id', '=', 'contractor_name.f_id')
->leftJoin('project_firm as contractor_description', 'project_preconstruction_meeting_documents.pm_description', '=', 'contractor_description.f_id')
->leftJoin('documents as agenda_path', 'project_preconstruction_meeting_documents.pm_agenda_path', '=', 'agenda_path.doc_id')
->leftJoin('documents as signin_sheet_path', 'project_preconstruction_meeting_documents.pm_signin_sheet_path', '=', 'signin_sheet_path.doc_id')
->leftJoin('documents as meeting_minutes_path', 'project_preconstruction_meeting_documents.pm_meeting_minutes_path', '=', 'meeting_minutes_path.doc_id')
->leftJoin('projects', 'project_preconstruction_meeting_documents.pm_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_preconstruction_meeting_documents.pm_user_id', '=', 'users.id')
        ->select('contractor_name.f_name as contractor_name',
          'contractor_description.f_name as contractor_description',
          'agenda_path.doc_path as agenda_path',  
          'signin_sheet_path.doc_path as signin_sheet_path',  
          'meeting_minutes_path.doc_path as meeting_minutes_path',  
          'project_preconstruction_meeting_documents.*', 'projects.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('pm_project_id', '=', $project_id)
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