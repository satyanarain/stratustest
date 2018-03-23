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


class FirmController extends Controller {

  
  /*
  --------------------------------------------------------------------------
   Get All Firm Name
  --------------------------------------------------------------------------
  */
  public function get_firm_name(Request $request)
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
        
          if(Auth::user()->role=="owner")
            $user_id              = Auth::user()->id;
          else
            $user_id              = Auth::user()->user_parent;
          
          if($user_id == 1){
            $query = DB::table('project_firm')
            ->leftJoin('company_type', 'project_firm.f_type', '=', 'company_type.ct_id')
            ->leftJoin('users', 'project_firm.f_user', '=', 'users.id')
            ->select('project_firm.*', 'company_type.ct_name as company_name', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
            ->get();
          }
          else {
           $query = DB::table('project_firm')
            ->leftJoin('company_type', 'project_firm.f_type', '=', 'company_type.ct_id')
            ->leftJoin('users', 'project_firm.f_user', '=', 'users.id')
            ->select('project_firm.*', 'company_type.ct_name as company_name', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
            ->where('f_user', '=', $user_id)
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
        // }
      }
      catch(Exception $e)
      {
        return response()->json(['error' => 'Something is wrong'], 500);
      }
  }
  
  /*
  --------------------------------------------------------------------------
   Get All Firm Name
  --------------------------------------------------------------------------
  */
  public function get_company_name(Request $request)
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
        
          if(Auth::user()->role=="owner")
            $user_id              = Auth::user()->id;
          else
            $user_id              = Auth::user()->user_parent;
          
          if($user_id == 1){
            $query = DB::table('project_firm')
            ->leftJoin('company_type', 'project_firm.f_type', '=', 'company_type.ct_id')
            ->leftJoin('users', 'project_firm.f_user', '=', 'users.id')
            ->select('project_firm.*', 'company_type.ct_name as company_name', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
            ->where('project_firm.company_type', '=', 'f')
            ->get();
          }
          else {
           $query = DB::table('project_firm')
            ->leftJoin('company_type', 'project_firm.f_type', '=', 'company_type.ct_id')
            ->leftJoin('users', 'project_firm.f_user', '=', 'users.id')
            ->select('project_firm.*', 'company_type.ct_name as company_name', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
            ->where('f_user', '=', $user_id)
            ->where('project_firm.company_type', '=', 'f')
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
        // }
      }
      catch(Exception $e)
      {
        return response()->json(['error' => 'Something is wrong'], 500);
      }
  }

  /*
  --------------------------------------------------------------------------
   Get All Firm Name
  --------------------------------------------------------------------------
  */
  public function get_project_firm_name(Request $request, $project_id)
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
          //DB::enableQueryLog();
          $project_owner = DB::table('projects')
          ->select('p_user_id')
          ->where('p_id', '=', $project_id)
          ->first();
          //echo '<pre>';
          
        
        //  print_r($project_owner->p_user_id);  
          $project_owner = $project_owner->p_user_id;  
          // die();
 
          $query = DB::table('project_firm')
          ->leftJoin('company_type', 'project_firm.f_type', '=', 'company_type.ct_id')
          ->leftJoin('users', 'project_firm.f_user', '=', 'users.id')
          ->select('project_firm.*', 'company_type.ct_name as company_type', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.status as user_status', 'users.role as user_role')
          ->where('f_user', '=', $project_owner)
          ->where('project_firm.company_type', '=', 'f')
          ->get();
//         $query = DB::getQueryLog();
//        $lastQuery = end($query);
//        print_r($lastQuery);
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
   Get All Firm Name
  --------------------------------------------------------------------------
  */
  public function get_project_firm_name_agency(Request $request, $project_id)
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

          $project_owner = DB::table('projects')
          ->select('p_user_id')
          ->where('p_id', '=', $project_id)
          ->first();

        //  print_r($project_owner->p_user_id);  
          $project_owner = $project_owner->p_user_id;  
          // die();
 
          $query = DB::table('project_firm')
          //->leftJoin('company_type', 'project_firm.f_type', '=', 'company_type.ct_id')
          ->leftJoin('users', 'project_firm.f_user', '=', 'users.id')
          ->select('project_firm.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.status as user_status', 'users.role as user_role')
          ->where('project_firm.f_user', '=', $project_owner)
          ->where('project_firm.company_type', '=', 'a')
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
   Get single firm detail passing firm ID
  --------------------------------------------------------------------------
  */
  public function get_firm_name_single(Request $request, $firm_id)
  {
    try
    {
      // $firm_id            = $firm_id;
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
        $query = DB::table('project_firm')
        ->leftJoin('company_type', 'project_firm.f_type', '=', 'company_type.ct_id')
        ->select('project_firm.*', 'company_type.ct_name as company_name')
        ->where('f_id', '=', $firm_id)
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
   Update Firm Name passing Firm ID
  --------------------------------------------------------------------------
  */
  public function update_firm_name(Request $request, $firm_id)
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

        $firm_name              = $request['firm_name'];
        $firm_detail            = $request['firm_detail'];
        $firm_address           = $request['firm_address'];
        $firm_type              = $request['firm_type'];
        $firm_status            = $request['firm_status'];
        $firm_id                = $firm_id;
        $project_long           = $request['project_long'];
        $project_lat            = $request['project_lat'];
        $company_type           = $request['company_type'];


        
        $information = array(
            "firm_name"           => $firm_name,
            "firm_status"         => $firm_status,
            "firm_detail"         => $firm_detail,
            "firm_address"        => $firm_address
        );

        $rules = [
            'firm_name'           => 'required',
            'firm_status'         => 'required',
            'firm_detail'         => 'required',
            'firm_address'        => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_firm')
            ->where('f_id', '=', $firm_id)
            ->update(['company_type' => $company_type,'f_name' => $firm_name, 'f_address' => $firm_address, 'p_long' => $project_long, 'p_lat' => $project_lat, 'f_type' => $firm_type, 'f_status' => $firm_status, 'f_detail' => $firm_detail]);
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
   Add Firm Name
  --------------------------------------------------------------------------
  */
  public function add_firm_name(Request $request)
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



        $firm_name              = $request['firm_name'];
        $firm_detail            = $request['firm_detail'];
        $firm_address           = $request['firm_address'];
        $firm_type              = $request['firm_type'];
        $firm_status            = 'active';
        $project_long           = $request['project_long'];
        $project_lat            = $request['project_lat'];
        $company_type           = $request['company_type'];
        $user_id                = Auth::user()->id;

        
        $information = array(
            "firm_name"         => $firm_name,
            "firm_detail"       => $firm_detail,
            "firm_address"      => $firm_address,
            "firm_type"         => $firm_type,
            "firm_status"       => $firm_status,
            "project_lat"       => $project_lat,
            "project_long"      => $project_long,
            "user_id"           => $user_id
        );

        $rules = [
            'firm_name'         => 'required',
            'firm_detail'       => 'required',
            'firm_address'      => 'required',
            'firm_type'         => 'required',
            'firm_status'       => 'required',
            'project_lat'       => 'required',
            'project_long'      => 'required',
            'user_id'           => 'required'

        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {




            $query = DB::table('project_firm')
            ->insert(['company_type' => $company_type,'f_name' => $firm_name, 'f_status' => $firm_status, 'f_detail' => $firm_detail, 'f_address' => $firm_address, 'p_long' => $project_long, 'p_lat' => $project_lat, 'f_type' => $firm_type, 'f_user' => $user_id]);

            if(count($query) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {
              $result = array('description'=>"New firm added successfully",'code'=>200);
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
   Get All Firm Name
  --------------------------------------------------------------------------
  */
  public function get_agency_name(Request $request)
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
        
          if(Auth::user()->role=="owner")
            $user_id              = Auth::user()->id;
          else
            $user_id              = Auth::user()->user_parent;
          
          if($user_id == 1){
            $query = DB::table('project_firm')
            ->leftJoin('company_type', 'project_firm.f_type', '=', 'company_type.ct_id')
            ->leftJoin('users', 'project_firm.f_user', '=', 'users.id')
            ->select('project_firm.*', 'company_type.ct_name as company_name', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
            ->where('project_firm.company_type', '=', 'a')
            ->get();
          }
          else {
           $query = DB::table('project_firm')
            ->leftJoin('company_type', 'project_firm.f_type', '=', 'company_type.ct_id')
            ->leftJoin('users', 'project_firm.f_user', '=', 'users.id')
            ->select('project_firm.*', 'company_type.ct_name as company_name', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
            ->where('f_user', '=', $user_id)
            ->where('project_firm.company_type', '=', 'a')
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
        // }
      }
      catch(Exception $e)
      {
        return response()->json(['error' => 'Something is wrong'], 500);
      }
  }
}