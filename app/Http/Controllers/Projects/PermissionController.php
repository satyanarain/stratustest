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
use Response;

class PermissionController extends Controller {

    /*
    --------------------------------------------------------------------------
    Check Project All User
    --------------------------------------------------------------------------
    */
    public function check_project_user($project_id)
    {
        try
        {
          $check_project_user = DB::table('users')
          ->leftJoin('project_contact', 'users.id', '=', 'project_contact.c_user_id')
          ->leftJoin('projects', 'project_contact.c_project_id', '=', 'projects.p_id')
          ->select('projects.*', 'users.id', 'users.email', 'users.username', 'users.first_name', 'users.last_name', 'users.phone_number', 'users.status', 'users.position_title', 'users.role')
          ->where('project_contact.c_project_id', '=', $project_id)
          // ->whereIn('users.role', ['manager', 'contractor'])
          ->where('users.status', '!=', 2)
          ->get();

          return $check_project_user;
        }
        catch(Exception $e)
        {
          return response()->json(['error' => 'Something is wrong'], 500);
        }
    }


    /*
    --------------------------------------------------------------------------
    Check Single User Permission
    --------------------------------------------------------------------------
    */
    public function check_single_user_permission($project_id, $user_id, $permission_key)
    {
        try
        {
          $check_single_user_permission = DB::table('project_user_permission')
          ->select()
          ->where('pup_project_id', '=', $project_id)
          ->where('pup_user_id', '=', $user_id)
          ->where('pup_permission_key', '=', $permission_key)
          ->get();

          return $check_single_user_permission;
        }
        catch(Exception $e)
        {
          return response()->json(['error' => 'Something is wrong'], 500);
        }
    }

    /*
    --------------------------------------------------------------------------
    Check Single User Permission
    --------------------------------------------------------------------------
    */
    public function check_all_user_permission($project_id, $user_id)
    {
        try
        {
          $check_single_user_permission = DB::table('project_user_permission')
          ->select()
          ->where('pup_project_id', '=', $project_id)
          ->where('pup_user_id', '=', $user_id)
          ->get();

          return $check_single_user_permission;
        }
        catch(Exception $e)
        {
          return response()->json(['error' => 'Something is wrong'], 500);
        }
    }

}