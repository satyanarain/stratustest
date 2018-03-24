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


class ImprovementController extends Controller {

  
  /*
  --------------------------------------------------------------------------
   Get All Improvement Type
  --------------------------------------------------------------------------
  */
  public function get_improvement(Request $request)
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
          $user_id              = Auth::user()->id;

            if($user_id == 1){
              $query = DB::table('project_type_improvement')
              ->leftJoin('users', 'project_type_improvement.pt_user_id', '=', 'users.id')
              ->select('project_type_improvement.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
              ->where('pt_user_id', '=', $user_id)
              ->orderBy('pt_name', 'asc')
              ->get();
            }
            else {
              $query = DB::table('project_type_improvement')
              ->leftJoin('users', 'project_type_improvement.pt_user_id', '=', 'users.id')
              ->select('project_type_improvement.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
              ->where('pt_user_id', '=', $user_id)
              ->orderBy('pt_name', 'asc')
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
   Get All Improvement Type
  --------------------------------------------------------------------------
  */
  public function get_improvement_by_owner(Request $request, $project_id)
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

         // print_r($project_owner->p_user_id);  
          $project_owner = $project_owner->p_user_id;  
          // die();
         
          $query = DB::table('project_type_improvement')
          ->leftJoin('users', 'project_type_improvement.pt_user_id', '=', 'users.id')
          ->select('project_type_improvement.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('pt_user_id', '=', $project_owner)
          ->orderBy('pt_name', 'asc')
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
   Get Project Improvement Type by passing project_id
  --------------------------------------------------------------------------
  */
  public function get_project_improvement(Request $request, $project_id)
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
          $user_id              = Auth::user()->id;

          $query = DB::table('projects')
          ->select('p_type')
          ->where('p_id', '=', $project_id)
          ->get();
          
            $pro_type = array();
            // foreach ($query as $q) {
            //    $pro_type[] = $q->p_type;
            // }  
            // print_r($query[0]->p_type);
            $pro_type =  $query[0]->p_type;
            // print_r($query);
            $faizan = $pro_type;
            $faizan = str_replace('"','',$pro_type);
            $faizan = str_replace('[','',$faizan);
            $faizan = str_replace(']','',$faizan);
            $faizan = explode(",",$faizan);
            // print_r($faizan);
            // exit;  

            $query2 = DB::table('project_type_improvement')
            ->select()
            ->whereIn('pt_id', $faizan)
            ->where('pt_status', '=', 'active')
            ->orderBy('pt_name', 'asc')
            ->get();

          if(count($query2) < 1)
          {
            $result = array('code'=>404, "description"=>"No Records Found");
            return response()->json($result, 404);
          }
          else
          {
            $result = array('data'=>$query2,'code'=>200);
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
   Get single Improvement by passing ID
  --------------------------------------------------------------------------
  */
  public function get_improvement_single(Request $request, $improvementid)
  {
    try
    {
      // $type_id            = $improvementid;
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
        $query = DB::table('project_type_improvement')
        ->select()
        ->where('pt_id', '=', $improvementid)
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
   Update Project Type Improvement passing Improvement ID
  --------------------------------------------------------------------------
  */
  public function update_improvement(Request $request, $improvementid)
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
        $type_name              = $request['type_name'];
        $type_status            = $request['type_status'];
        $type_id                = $improvementid;
        
        $information = array(
            "type_name"         => $type_name,
            "type_status"       => $type_status,
        );

        $rules = [
            'type_name'         => 'required',
            'type_status'       => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_type_improvement')
            ->where('pt_id', '=', $type_id)
            ->update(['pt_name' => $type_name, 'pt_status' => $type_status]);
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
   Add Project Type Improvement
  --------------------------------------------------------------------------
  */
  public function add_improvement(Request $request)
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
        $improvement_type     = $request['improvement_type'];
        $type_status          = 'active';
        $user_id              = Auth::user()->id;

        $information = array(
            "improvement_type"   => $improvement_type
        );
        $rules = [
            "improvement_type"   => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $project_improvement = DB::table('project_type_improvement')
            ->insert(['pt_name' => $improvement_type, 'pt_status' => $type_status, 'pt_user_id' => $user_id]);

            if(count($project_improvement) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {
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
}