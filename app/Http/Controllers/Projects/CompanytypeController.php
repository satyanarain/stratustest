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
use App;
use Session;

use Gate;
use App\User; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post; 
use App\Repositories\Util\AclPolicy;


class CompanytypeController extends Controller {

  
  /*
  --------------------------------------------------------------------------
   Add Currency
  --------------------------------------------------------------------------
  */
  public function add_company_type(Request $request)
  {
    try
    {
      $user = array(
        'id'        => Auth::user()->id,
        'role'      => Auth::user()->role
      );
      $user = (object) $user;
      $post = new Resource_Post(); // You create a new resource Post instance
      if (Gate::forUser($user)->denies('allow_admin_owner_user', [$post,false])) { 
        $result = array('code'=>403, "description"=>"Access denies");
        return response()->json($result, 403);
      } 
      else { 
        $company_type_name            = $request['company_type_name'];
        $company_type_status          = 'active';
        $user_id                      = Auth::user()->id;
        
        $information = array(
            "company_type_name"       => $company_type_name
       );

        $rules = [
            'company_type_name'       => 'required'
        ];

        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('company_type')
            ->insert(['ct_name' => $company_type_name, 'ct_status' => $company_type_status, 'ct_user_id' => $user_id]);

            if(count($query) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {
              $result = array('description'=>"New company type add successfully",'code'=>200);
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
   Update Currency passing cur_id
  --------------------------------------------------------------------------
  */
  public function update_company_type(Request $request, $company_type_id)
  {
    try
    {
      $user = array(
        'userid'    => Auth::user()->id,
        'role'      => Auth::user()->role
      );
      $user = (object) $user;
      $post = new Resource_Post(); // You create a new resource Post instance
      if (Gate::forUser($user)->denies('allow_admin_owner_user', [$post,false])) { 
        $result = array('code'=>403, "description"=>"Access denies");
        return response()->json($result, 403);
      } 
      else {
        $company_type_name            = $request['company_type_name'];
        $company_type_status          = $request['status'];
        
        $information = array(
            "company_type_name"       => $company_type_name,
            "status"                  => $company_type_status
       );

        $rules = [
            'company_type_name'       => 'required',
            'status'                  => 'required'
        ];

        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('company_type')
            ->where('ct_id', '=', $company_type_id)
            ->update(['ct_name' => $company_type_name, 'ct_status' => $company_type_status]);
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
      }
    }
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }

  /*
  --------------------------------------------------------------------------
   Get single currency detail passing cur_id
  --------------------------------------------------------------------------
  */
  public function get_company_type_single(Request $request, $company_type_id)
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
        $query = DB::table('company_type')
        ->leftJoin('users', 'company_type.ct_user_id', '=', 'users.id')
        ->select('company_type.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('ct_id', '=', $company_type_id)
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
   Get All Currency
  --------------------------------------------------------------------------
  */
  public function get_company_type(Request $request)
  {
      try
      {
          if(Auth::user()->role=="owner")
            $user_id              = Auth::user()->id;
          else
            $user_id              = Auth::user()->user_parent;
        $user = array(
          'userid'    => Auth::user()->id,
          'role'      => Auth::user()->role
        );
        $user = (object) $user;
        $post = new Resource_Post(); // You create a new resource Post instance
//        if (Gate::forUser($user)->denies('allow_admin_owner_user', [$post,false])) {
//          $result = array('code'=>403, "description"=>"Access denies");
//          return response()->json($result, 403);
//        } 
        //else {

          if($user_id == 1){
            $query = DB::table('company_type')
            ->leftJoin('users', 'company_type.ct_user_id', '=', 'users.id')
            ->select('company_type.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
            ->get();
          }
          else {
            $query = DB::table('company_type')
            ->leftJoin('users', 'company_type.ct_user_id', '=', 'users.id')
            ->select('company_type.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
            ->where('ct_user_id', '=', $user_id)
            ->get(); 
          }

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
        //}
      }
      catch(Exception $e)
      {
        return response()->json(['error' => 'Something is wrong'], 500);
      }
  }
}