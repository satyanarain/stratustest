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


class ProjectSettingController extends Controller {

    /*
    --------------------------------------------------------------------------
    Update Project Setting
    --------------------------------------------------------------------------
    */
    public function project_setting_add(Request $request)
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
            $project_id      = $request['project_id'];
            $meta_key        = $request['meta_key'];
            $meta_value      = $request['meta_value'];
            $user_id         = Auth::user()->id;
        // Check User Permission Parameter 
        $user_id = Auth::user()->id;
        $permission_key = 'project_setting';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
            $information = array(
                "meta_key"        => $meta_key,
                "meta_value"      => $meta_value,
                "project_id"      => $project_id,
                "user_id"         => $user_id,
            );

            $rules = [
                'meta_key'        => 'required',
                'meta_value'      => 'required',
                'project_id'      => 'required|numeric',
                'user_id'         => 'required|numeric',
            ];
            $validator = Validator::make($information, $rules);
            if ($validator->fails()) 
            {
                return $result = response()->json(["data" => $validator->messages()],400);
            }
            else
            {
                $detail = DB::table('project_settings')
                ->select()
                ->where('pset_project_id', '=', $project_id)
                ->where('pset_meta_key', '=', $meta_key)
                ->first();
                if(count($detail) < 1)
                {
                  $query = DB::table('project_settings')
                  ->insert(['pset_project_id' => $project_id, 'pset_meta_key' => $meta_key, 'pset_meta_value' => $meta_value]);
                  if(count($query) < 1)
                  {
                    return response()->json(['error' => 'Something is wrong'], 500);
                  }
                  else
                  {
                    $result = array('description'=>"Project currency saved",'code'=>200);
                    return response()->json($result, 200);
                  }
                }
                else
                {
                  $query = DB::table('project_settings')
                  ->update(['pset_meta_value' => $meta_value]);
                  if(count($query) < 1)
                  {
                    return response()->json(['error' => 'Something is wrong'], 500);
                  }
                  else
                  {
                    $result = array('description'=>"Project currency saved",'code'=>200);
                    return response()->json($result, 200);
                  }
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
    Get Project Setting Passing project_id and Meta_key
    --------------------------------------------------------------------------
    */
    public function project_setting_get(Request $request, $project_id, $meta_key)
    {
        //echo $meta_key;die;
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
          // $user_id = Auth::user()->id;
          // $permission_key = 'project_setting';
          // $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
          // if(count($check_single_user_permission) < 1){
          //   $result = array('code'=>403, "description"=>"Access Denies");
          //   return response()->json($result, 403);
          // }
          // else {
            $query = DB::table('project_settings')
            ->select()
            ->where('pset_project_id', '=', $project_id)
            ->where('pset_meta_key', '=', $meta_key)
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

}