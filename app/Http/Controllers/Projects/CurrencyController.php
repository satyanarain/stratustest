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


class CurrencyController extends Controller {

  
  /*
  --------------------------------------------------------------------------
   Add Currency
  --------------------------------------------------------------------------
  */
  public function add_currency(Request $request)
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
        $currency_name      = $request['currency_name'];
        $currency_symbol    = $request['currency_symbol'];
        $currency_status    = 'active';
        $user_id            = Auth::user()->id;
        
        $information = array(
            "currency_name"       => $currency_name,
            "currency_symbol"     => $currency_symbol
        );

        $rules = [
            'currency_name'       => 'required',
            'currency_symbol'     => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('currency')
            ->insert(['cur_name' => $currency_name, 'cur_symbol' => $currency_symbol, 'cur_status' => $currency_status, 'cur_user_id' => $user_id]);

            if(count($query) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {
              $result = array('description'=>"New currency added successfully",'code'=>200);
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
  public function update_currency(Request $request, $cur_id)
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
        $currency_name      = $request['currency_name'];
        $currency_symbol    = $request['currency_symbol'];
        $currency_status    = $request['currency_status'];
        
        $information = array(
            "currency_name"       => $currency_name,
            "currency_symbol"     => $currency_symbol,
            "currency_status"     => $currency_status
        );

        $rules = [
            'currency_name'       => 'required',
            'currency_symbol'     => 'required',
            'currency_status'     => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('currency')
            ->where('cur_id', '=', $cur_id)
            ->update(['cur_name' => $currency_name, 'cur_symbol' => $currency_symbol, 'cur_status' => $currency_status]);
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
  public function get_currency_single(Request $request, $cur_id)
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
        $query = DB::table('currency')
        ->leftJoin('users', 'currency.cur_user_id', '=', 'users.id')
        ->select('currency.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('cur_id', '=', $cur_id)
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
  public function get_currency(Request $request)
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
          if(Auth::user()->role=="admin")
                $user_id              = Auth::user()->id;
          else
                $user_id              = Auth::user()->user_parent;

          if($user_id == 1){
            $query = DB::table('currency')
            ->leftJoin('users', 'currency.cur_user_id', '=', 'users.id')
            ->select('currency.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
            ->get();
          }
          else {
            $query = DB::table('currency')
            ->leftJoin('users', 'currency.cur_user_id', '=', 'users.id')
            ->select('currency.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
            ->where('cur_user_id', '=', $user_id)
            ->groupBy('currency.cur_name') 
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