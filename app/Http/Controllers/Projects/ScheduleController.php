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

 
class ScheduleController extends Controller {

  /*
  --------------------------------------------------------------------------
   Add Schedule
  --------------------------------------------------------------------------
  */
  public function add_schedule(Request $request)
  {
    try
    {
        $file_path    = $request['file_path'];
        $project_id   = $request['project_id'];
        $built_filed_on = date("Y-m-d h:i:s");
        $user_id      = Auth::user()->id;
        $status       = 'active';
    // Check User Permission Parameter 
    $user_id = Auth::user()->id;
    $permission_key = 'schedule_add';
    $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
    if(count($check_single_user_permission) < 1){
      $result = array('code'=>403, "description"=>"Access Denies");
      return response()->json($result, 403);
    }
    else {

        $information = array(
            "file_path"   => $file_path,
            "project_id"  => $project_id,
            "user_id"     => $user_id,
            "built_filed_on"=>$built_filed_on
        );

        $rules = [
            'file_path'   => 'required|numeric',
            'project_id'  => 'required|numeric',
            'user_id'     => 'required|numeric'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
             $query = DB::table('project_schedule')
            ->insertGetId(['schedule_filed_on'=>$built_filed_on,'schedule_file_path' => $file_path, 'schedule_project_id' => $project_id, 'schedule_user_id' => $user_id, 'schedule_status' => $status]);

            if(count($query) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {
              $result = array('description'=>"Add built drawing successfully",'code'=>200);
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
   Update Schedule by passing schedule_id
  --------------------------------------------------------------------------
  */
  public function update_schedule(Request $request, $schedule_id)
  {
    try
    {
        $schedule_id   = $schedule_id;
        $user_id      = Auth::user()->id;
        $status = $request['status'];
        // Check User Permission Parameter 
        $user_id = Auth::user()->id;
        $query = DB::table('project_schedule')
        ->where('schedule_id', '=', $schedule_id)
        ->update(['schedule_status' => $status]);
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
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }

  /*
  --------------------------------------------------------------------------
   Get single Schedule by passing schedule_id
  --------------------------------------------------------------------------
  */
  public function get_schedule(Request $request, $schedule_id)
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
        $query = DB::table('project_schedule')
        ->leftJoin('documents', 'project_schedule.schedule_file_path', '=', 'documents.doc_id')
        ->leftJoin('projects', 'project_schedule.schedule_project_id', '=', 'projects.p_id')
        ->leftJoin('users', 'project_schedule.schedule_user_id', '=', 'users.id')
        ->select('project_schedule.*', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('schedule_id', '=', $schedule_id)
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
   Get all Schedule by passing Project ID
  ----------------------------------------------------------------------------------
  */
  public function get_schedule_project(Request $request, $project_id)
  {
      try
      {
      $user_id = Auth::user()->id;
      $permission_key = 'schedule_view_all';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
          $query = DB::table('project_schedule')
        ->leftJoin('documents', 'project_schedule.schedule_file_path', '=', 'documents.doc_id')
        ->leftJoin('projects', 'project_schedule.schedule_project_id', '=', 'projects.p_id')
        ->leftJoin('users', 'project_schedule.schedule_user_id', '=', 'users.id')
        ->select('project_schedule.*', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('schedule_project_id', '=', $project_id)
        ->orderBy('project_schedule.schedule_id','DESC')
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