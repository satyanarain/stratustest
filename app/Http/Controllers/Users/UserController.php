<?php 
// namespace App\Http\Controllers\Users;
// use Illuminate\Http\Request;
// use Tymon\JWTAuth\Exceptions\JWTException;
// use Tymon\JWTAuth\Facades\JWTAuth;
// use App\Http\Controllers\Controller;
// use JWTAuth;
// use DB;


namespace App\Http\Controllers\Users;

use App\Accounts;
use App\AccountUsers;
use App\Expiration;
use App\Http\Controllers\Controller;
use App\UserData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Http\Requests;
use App\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\Builder;
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


class UserController extends Controller {

  /*
  --------------------------------------------------------------------------
   Get All Users
  --------------------------------------------------------------------------
  */
  public function get_users(Request $request)
  {
      try
      {
          // $post = array(
          //   'role'      => Auth::user()->role //$request['u_role']
          // );
          // $post = (object) $post;
          // if (Gate::denies('admin-access', $post)) {
          //     $result = array('code'=>403, "description"=>"Access Denies");
          //     return response()->json($result, 403);
          // }
          // $user_role = array();
          // $users = User::select('role')->distinct()->get();
          // foreach ($users as $key => $value) {
          //     array_push($user_role, $value->role);
          // }
          // print_r($user_role);
          // exit;
        /*** Check ACL ***/
        $user = array(
          'id'        => Auth::user()->id,
          'role'      => Auth::user()->role
        );
        $user = (object) $user;
        
        // $user_role = DB::table('roles')
        // ->select('permission')
        // ->where('user_id', '=', 1)
        // ->where('resource', '=', 1)
        // ->where('role', '=', 'manager')
        // ->get();
        // print_r($user_role[0]->permission);
        // exit;
        // $user = new User();  // You create a new User instance
        $post = new Resource_Post($user->id); // You create a new resource Post instance
        if (Gate::forUser($user)->denies('allow_admin_owner_user', [$post, false])) { 
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        } 
        else { 

          $user_id              = Auth::user()->id;

          if($user_id == 1){
            $query = DB::table('users')
            ->leftJoin('project_firm', 'users.company_name', '=', 'project_firm.f_id')
            ->leftJoin('users as user_parent', 'users.user_parent', '=', 'user_parent.id')
            ->select('users.id', 'users.email', 'users.username', 'users.first_name', 'users.last_name', 'users.company_name', 'project_firm.f_name as agency_name', 'users.phone_number', 'users.position_title', 'users.status', 'users.role', 'user_parent.username as user_parent','users.user_image_path')
            ->orderBy('users.id', 'asc')
            ->get();
          }
          else {
            $query = DB::table('users')
            ->leftJoin('project_firm', 'users.company_name', '=', 'project_firm.f_id')
            ->leftJoin('users as user_parent', 'users.user_parent', '=', 'user_parent.id')
            ->select('users.id', 'users.email', 'users.username', 'users.first_name', 'users.last_name', 'users.company_name', 'project_firm.f_name as agency_name', 'users.phone_number', 'users.position_title', 'users.status', 'users.role', 'user_parent.username as user_parent','users.user_image_path')
            ->where('users.user_parent', '=', $user_id)
            ->orderBy('users.id', 'asc')
            ->get(); 
          }

          if(count($query) < 1)
          {
              // $data = array('data' =>  $result);  
              $result = array('code'=>404,"description"=>"No Records Found");
              return $result = response()->json($result, 404);
          }
          else
          {
              // $data = array('data' =>  $result);  
              $result = array('code'=>200, "data"=>$query);
              return $result = response()->json($result, 200);
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
   Get User Detail by Passing Token
  --------------------------------------------------------------------------
  */
  public function get_user_details(Request $request)
  {
      try
      {
        $u_id = $request['u_id'];
        // echo $u_id =  1; //$request->only('id');
           $query = DB::table('users')
            // ->leftJoin('project_contact', 'users.id', '=', 'project_contact.c_user_id')
            // ->leftJoin('projects', 'users.project_id', '=', 'projects.p_id')
            ->select('users.id', 'users.email', 'users.username', 'users.first_name', 'users.last_name', 'users.company_name', 'users.phone_number', 'users.status', 'users.position_title', 'users.role','users.password_changed')
            ->where('id', '=', $u_id)
            ->first();


        $user =  (array) $query;
        if(count($query) < 1)
        {
            $result = array('code'=>400,"description"=>"No Records Found");
            return response()->json($result, 400);
        }
        elseif($query->status == 2)
        {
            $result = array('code'=>403,"description"=>"Account is disabled");
            return response()->json($result, 403);
        }
        else
        {
            if($query->password_changed==1)
            {
                $session_data = Array(
                  'id'            => $query->id,
                  'email'         => $query->email,
                  'username'      => $query->username,
                  'first_name'    => $query->first_name,
                  'last_name'     => $query->last_name,
                  'company_name'  => $query->company_name,
                  'phone_number'  => $query->phone_number,
                  'position_title'=> $query->position_title,
                  'status'        => $query->status,
                  'role'          => $query->role,
                );
                // print_r($session_data);
                Session::put('user', $session_data);
                $result = array('data'=>$query,'code'=>200);
                return response()->json($result, 200);
            }else{
                $result = array('code'=>403,"description"=>"Please check your email to change password");
                return response()->json($result, 403);
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
   Get User Detail by Passing User Id
  --------------------------------------------------------------------------
  */
  public function get_single_user_details(Request $request, $user_id)
  {
      try
      {
        $u_id = $user_id;
        // echo $u_id =  1; //$request->only('id');
           $query = DB::table('users')
            // ->leftJoin('project_contact', 'users.id', '=', 'project_contact.c_user_id')
            // ->leftJoin('projects', 'users.project_id', '=', 'projects.p_id')
           
            ->leftJoin('project_firm', 'users.company_name', '=', 'project_firm.f_id')
            ->select('project_firm.f_name as company_name', 'users.id', 'users.email', 'users.username', 'users.first_name', 'users.company_name as company_id', 'users.last_name', 'users.phone_number', 'users.status', 'users.position_title', 'users.role','users.user_image_path')
            ->where('id', '=', $u_id)
            ->first();

         
            $user_data = DB::table('users_details')
            ->select('u_phone_type','u_phone_detail')
            ->where('user_id', '=' ,$u_id)
            ->get();
            
            $query->user_detail = $user_data;
         

        $user =  (array) $query;
        if(count($query) < 1)
        {
            $result = array('code'=>400,"description"=>"No Records Found");
            return response()->json($result, 400);
        }
        elseif($query->status == 2)
        {
            $result = array('code'=>403,"description"=>"Account is disabled");
            return response()->json($result, 403);
        }
        else
        {
            $session_data = Array(
              'id'            => $query->id,
              'email'         => $query->email,
              'username'      => $query->username,
              'first_name'    => $query->first_name,
              'last_name'     => $query->last_name,
              'company_name'  => $query->company_name,
              'phone_number'  => $query->phone_number,
              'position_title'=> $query->position_title,
              'status'        => $query->status,
              'role'          => $query->role
            );
            // print_r($session_data);
            Session::put('user', $session_data);
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
   Get User Status by Passing User ID
  --------------------------------------------------------------------------
  */
  public function send_email_verification(Request $request)
  {
      try
      {
        $u_id = $request['id'];

        $email_verification = sha1($u_id);

        $query = DB::table('users')
        ->where('id', $u_id)
        ->update(['email_verification' => $email_verification]);


        $query = DB::table('users')
          ->select('id', 'email', 'username', 'status', 'email_verification')
          // ->where('status', '=', 0)
          ->where('id', '=', $u_id)
          ->first();
        if(count($query) < 1)
        {
            $result = array('code'=>400, "description"=>"No Records Found");
            return $result = response()->json($result, 400);
        }
        else if($query->status == 1){
            $result = array('code'=>400, "description"=>"Account already verified");
            return $result = response()->json($result, 400);
        }
        else if($query->status == 2){
            $result = array('code'=>403, "description"=>"Account is disabled");
            return $result = response()->json($result, 403);
        }
        else
        {
          $query = array(
              'id'        => $query->id,
              'name'      => $query->username,
              'email'     => $query->email,
              'user_id'   => $query->email_verification,
              'date'      => date("M d, Y h:i a")
            );
          $user_single = (object) $query;
          Mail::send('emails.unverified_user',['user' => $user_single], function ($message) use ($user_single) {
              $message->from('no-reply@sw.ai', 'StratusCM');
              $message->to($user_single->email, $user_single->name)->subject('Confirm email verification');
          });
        }
          $result = array('code'=>200, "description"=>$query);
          return $result = response()->json($result, 200);
      }
      catch(Exception $e)
      {
        return response()->json(['error' => 'Something is wrong'], 500);
      }
  }
  
  /*
  --------------------------------------------------------------------------
   User Email Verification and send email
  --------------------------------------------------------------------------
  */
  public function email_verification(Request $request)
  {
      try
      {

        // $u_id = Crypt::decrypt($request['user_id']);
        // $u_id = Crypt::encrypt(7);
        $u_id = $request['user_id'];
           $query_user = DB::table('users')
            ->select('id', 'email', 'username', 'status')
            ->where('email_verification', '=', $u_id)
            ->first();

        $query_email = array(
          'id'        => $query_user->id,
          'name'      => $query_user->username,
          'email'     => $query_user->email,
          'status'    => $query_user->status,
          'date'      => date("M d, Y h:i a")
        );
        $query_user =  (object) $query_email;

        if(count($query_user) < 1)
        {
            $result = array('code'=>400,"description"=>"No Records Found");
            return response()->json($result, 400);
        }
        else if($query_user->status == 1){
            $result = array('code'=>400, "description"=>"Account already verified");
            return $result = response()->json($result, 400);
        }
        else if($query_user->status == 2){
            $result = array('code'=>403, "description"=>"Account is disabled");
            return $result = response()->json($result, 403);
        }
        else
        {
            $getuser = new User();
            $query_update = DB::table('users')
            ->where('id', $query_user->id)
            ->update(['status' => 1, 'email_verification' => null]);
            $user =  (array) $query_update;

            if(count($query_update) < 1)
            {
                $result = array('code'=>400,"description"=>"No Records Found");
                return response()->json($result, 400);
            }
            else
            {      
      Mail::send('emails.email_verification', ['user' => $query_user], function ($message) use ($query_user) {
          $message->from('no-reply@sw.ai', 'StratusCM');
          $message->to($query_user->email, $query_user->name)->subject('Confirm Email Verification');
      });

                $result = array('data'=>$query_update,'code'=>200);
                return response()->json($result, 200);
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
   Get All Unverified User and send email
  --------------------------------------------------------------------------
  */
  public function unverified_user(Request $request)
  {
      try
      {
          $query = DB::table('users')
          ->select('id', 'email', 'username')
          ->where('status', '=', 0)
          ->get();
        $user =  (array) $query;
        if(count($query) < 1)
        {
          $result = array('code'=>400,"description"=>"No Records Found");
          return response()->json($result, 400);
        }
        else
        {
          foreach ($user as $user_single) {
            $maildata = array(
              'name'    => $user_single->username,
              'email'   => $user_single->email,
              'user_id' => Crypt::encrypt($user_single->id),
              'date'    => date("M d, Y h:i a")
            );
            $user_single = (object) $maildata;
            // print_r($user_single);
            // exit; 
            Mail::send('emails.unverified_user',['user' => $user_single], function ($message) use ($user_single) {
                $message->from('no-reply@sw.ai', 'StratusCM');
                $message->to($user_single->email, $user_single->name)->subject('Email verification confirmed');
            });
          }
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
   Get All Unverified User and expire if X day value cross
  --------------------------------------------------------------------------
  */
  public function unverified_user_expire(Request $request)
  {
      try
      {
        $days = config('app.expiration_days');
        $query = DB::table('users')
        ->select('id', 'email', 'username', 'timestamp')
        ->where('status', '=', 0)
        ->get();
        $user =  (array) $query;
        if(count($query) < 1)
        {
          $result = array('code'=>400,"description"=>"No Records Found");
          return response()->json($result, 400);
        }
        else
        {
          foreach ($query as $key => $user) {
            $reg_time = $user->timestamp;
            $reg_date = strtotime($reg_time);
            $date = date_create($reg_time);
            date_add($date, date_interval_create_from_date_string($days.'days'));
            $plus_time = date_format($date, 'Y-m-d H:i:s');
            $plus_date = strtotime($plus_time);
            $current_date = time();
            if($current_date >= $plus_date){
              $user = DB::table('users')
              ->where('id', $user->id)
              ->update(['status' => 2]);
              if(count($user) < 1)
              {
                $result = array('code'=>400, "description"=>"No Records Found");
                return response()->json($result, 400);
              }
              else
              { 
                // $result = array('code'=>200, "description"=>"No Records Found");
                // $result = array('data'=>$user,'code'=>200);
                // return response()->json($result, 200);
              }
            }
            else {
              // echo 'time remianing';
            }
          }
          $result = array('description'=>'Account suspend successfully','code'=>200);
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
   Get single user detail passing user ID
  --------------------------------------------------------------------------
  */
  public function get_profile(Request $request, $user_id)
  {
    try
    {
      $u_id = $user_id; // Auth::user()->id;
      // $post = array(
      //   'id'        => $u_id,
      //   'userid'    => Auth::user()->id,
      //   'role'      => Auth::user()->role,
      // );
      // $post = (object) $post;
      // if (Gate::denies('user-admin-access', $post)) {
      //     $result = array('code'=>403, "description"=>"Access Denies");
      //     return response()->json($result, 403);
      // }
      /*** Check ACL ***/
      // $user = array(
      //   'id'        => $u_id,
      //   'userid'    => Auth::user()->id,
      //   'role'      => Auth::user()->role
      // );

      // $user = (object) $user;
      // $post = new Resource_Post(); // You create a new resource Post instance
      // if (Gate::forUser($user)->denies('allow_admin_user', [$post,false])) { 
      //   $result = array('code'=>403, "description"=>"Access Denies");
      //   return response()->json($result, 403);
      // } 
      // else {
        $user = DB::table('users')
        // ->leftJoin('projects', 'users.project_id', '=', 'projects.p_id')
        // ->select('id', 'email', 'username', 'first_name', 'last_name', 'company_name', 'phone_number', 'status', 'project_id', 'projects.p_name as project_name', 'role')
        // ->leftJoin('project_contact', 'users.id', '=', 'project_contact.c_user_id')
        ->leftJoin('project_firm', 'users.company_name', '=', 'project_firm.f_id')
        ->select('users.id', 'users.email', 'users.username', 'users.first_name', 'users.last_name', 'users.company_name', 'users.phone_number', 'project_firm.f_name', 'users.status', 'users.position_title', 'users.role','users.user_image_path')

        ->where('id', '=', $u_id)
        ->first();
          $user_data = DB::table('users_details')->select('u_phone_type','u_phone_detail')->where('user_id', '=', $u_id)->get();
         $user->user_detail = $user_data;
        if(count($user) < 1)
        {
          $result = array('code'=>404, "description"=>"No Records Found");
          return response()->json($result, 404);
        }
        else
        {
          $result = array('data'=>$user,'code'=>200);
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
   Update single user detail passing user ID
  --------------------------------------------------------------------------
  */
  public function update_user(Request $request, $userid)
  {
    try
    {
      $u_id = $userid;
      // $user = array(
      //   'id'        => $u_id,
      //   'userid'    => Auth::user()->id,
      //   'role'      => Auth::user()->role
      // );
      // $user = (object) $user;
      // $post = new Resource_Post(); // You create a new resource Post instance
      // if (Gate::forUser($user)->denies('allow_admin_user', [$post,false])) { 
      //   $result = array('code'=>403, "description"=>"Access Denies");
      //   return response()->json($result, 403);
      // } 
      // else {
        $first_name         = $request['first_name'];
        $last_name          = $request['last_name'];
        $company_name       = $request['company_name'];
        $phone_number       = $request['phone_number'];
        // $username           = $request['username'];
        // $email              = $request['email'];
        $role               = $request['role'];
        $status             = $request['status'];
        $position_title     = $request['position_title'];
        // $user_role          = $request['user_role'];
        $password           = $request['password'];
        // $project_id         = $request['project_id'];
        $confirm_password   = $request['confirm_password'];
        $user_image_path = $request['user_image_path'];
        
        $information = array(
            "first_name"        => $first_name,
            "last_name"         => $last_name,
            "company_name"      => $company_name,

            // "username"          => $username,
            // "email"             => $email,
            "password"          => $password,
            "confirm_password"  => $confirm_password
        );

        $rules = [
            'first_name'        => 'required',
            'last_name'         => 'required',
            'company_name'      => 'required',

            // 'username'          => 'max:255',
            // 'email'             => 'email|max:255',
            'password'          => 'min:6',
            'confirm_password'  => 'min:6|same:password'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {

            $user_detail = DB::table('users')
            ->select()
            ->where('id', '=', $u_id)
            ->first();
          if(($password == "") ? $password = $user_detail->password : $password = Hash::make($password));
          if(($role == "") ? $role = $user_detail->role : $role = $role);
          if(($status == "") ? $status = $user_detail->status : $status = $status);
          // if(($email == "") ? $email = $user_detail->email : $email = $email);
          // if(($username == "") ? $username = $user_detail->username : $username = $username);
          if(($position_title == "") ? $position_title = $user_detail->position_title : $position_title = $position_title);
            
            // echo '<pre>';
            // print_r($username);
            // echo '</pre>';
            // exit;
            $user = DB::table('users')
            ->where('id', '=', $u_id)
            // ->update(['first_name' => $first_name, 'last_name' => $last_name, 'company_name' => $company_name, 'phone_number' => $phone_number, 'username' => $username, 'position_title' => $position_title, 'email' => $email, 'password' => $password, 'status' => $status, 'role' => $role]);
            ->update(['first_name' => $first_name, 'last_name' => $last_name, 'company_name' => $company_name, 'phone_number' => $phone_number, 'position_title' => $position_title, 'password' => $password, 'status' => $status, 'role' => $role,'user_image_path' => $user_image_path]);

            // $user = DB::table('project_contact')
            // ->where('c_user_id', '=', $u_id)
            // ->update(['c_position_title' => $position, 'c_user_type' => $user_role]);            

            if(count($user) < 1)
            {
              $result = array('code'=>400, "description"=>"No Records Found");
              return response()->json($result, 400);
            }
            else
            {
              $result = array('data'=>$user,'code'=>200);
              return response()->json($result, 200);
            }
        // }
      }
    }
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }

  /*
  --------------------------------------------------------------------------
   Suspend user only admin can perform this action
  --------------------------------------------------------------------------
  */
  public function user_suspend(Request $request, $id)
  {
    try
    {
      $u_id = $id;
      $user = array(
        'id'        => Auth::user()->id,
        'role'      => Auth::user()->role
      );
      $user = (object) $user;
      $post = new Resource_Post(); // You create a new resource Post instance
      if (Gate::forUser($user)->denies('allow_admin', [$post,false])) { 
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      } 
      else { 
        $getuser = new User();
        $user = DB::table('users')
        ->where('id', $u_id)
        ->update(['status' => 2]);

        if(count($user) < 1)
        {
          $result = array('code'=>400, "description"=>"No Records Found");
          return response()->json($result, 400);
        }
        else
        {
          $result = array('data'=>$user,'code'=>200);
          return response()->json($result, 200);
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
   Add new user detail
  --------------------------------------------------------------------------
  */
  public function add_user(Request $request)
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
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      } 
      else { 
        $username         = $request['username'];
        $email            = $request['email'];
        $first_name       = $request['first_name'];
        $last_name        = $request['last_name'];
        $company_name     = $request['company_name'];
        // $phone_number     = $request['phone_number'];
        $position         = $request['position'];
        $role             = $request['role'];
        $status           = 0;
        $user_id_owner    = Auth::user()->id;
        // $user_role    = $request['user_role'];
        // $project_id   = $request['project_id'];
        $user_image_path = $request['user_image_path'];
        
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $user_email_password  = $randomString;
        $password             = Hash::make($randomString);

        $information = array(
            "username"            => $username,
            "email"               => $email,
            "password"            => $password,
            "first_name"          => $first_name,
            "last_name"           => $last_name,
            "company_name"        => $company_name,
            "position"            => $position,
            "role"                => $role,
            "user_email_password" => $user_email_password,
            "date"                => date("M d, Y h:i a"),
            "user_image_path"     => $user_image_path
        );
        $rules = [
            "username"      => 'required|unique:users|max:255',
            "email"         => 'required|email|max:255',
            "first_name"    => 'required',
            "last_name"     => 'required',
            "company_name"  => 'required',
            "position"      => 'required',
            "role"          => 'required',
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $user = User::create(['username' => $username, 'email' => $email, 'password' => $password, 'first_name' => $first_name, 'last_name' => $last_name, 'company_name' => $company_name, 'position_title' => $position, 'status' => $status, 'role' => $role, 'user_parent' => $user_id_owner,'user_image_path' => $user_image_path]);
            $lastInsert_id =  $user->id;
            
            $email_verification = sha1($lastInsert_id);
            $query = DB::table('users')
            ->where('id', $lastInsert_id)
            ->update(['email_verification' => $email_verification]);
            $information['email_verification'] = $email_verification;
            if($role == 'owner'){
              $default_improvement_type = array("Mobilization and Site Preparation", "Rough Grading", "Structural Retaining Walls", "Erosion Control", "Habitat Mitigation", "Storm Drainage", "Sanitary Sewer", "Domestic Water Systems", "Reclaimed Water Systems", "Street Improvements", "Signalization", "Street Lights", "Dry Utilities", "Landscape & Irrigation", "Entry Monuements", "Walls & Fencing", "Bridges");

              foreach ($default_improvement_type as $single_improvement_type) {
                  // echo $single_improvement_type;
                $query_contact = DB::table('project_type_improvement')
                ->insert(['pt_name' => $single_improvement_type, 'pt_user_id' => $lastInsert_id, 'pt_status' => 'active']);
              }
              $default_company_type = array("Engineering", "Surveying", "Materials Testing", "Architect", "Supplier", "General Contractor", "Inspection", "Subcontractor", "Construction Management", "Public Agency", "Geotechnical", "SWPPP");

              foreach ($default_company_type as $single_company_type) {
                  // echo $single_improvement_type;
                $query_contact = DB::table('company_type')
                ->insert(['ct_name' => $single_company_type, 'ct_user_id' => $lastInsert_id, 'ct_status' => 'active']);
              }
                // Add USD Currency by Default
                // $currency = currency::create(['cur_name' => 'US Dollor', 'cur_symbol' => '$', 'cur_user_id' => $lastInsert_id, 'cur_status' => 'active']);
                // $lastcurrency_id =  $currency->id;

                $query_contact = DB::table('currency')
                ->insert(['cur_name' => 'US Dollar', 'cur_symbol' => '$', 'cur_user_id' => $lastInsert_id, 'cur_status' => 'active']);
            }

            // $lastInsertid = $user->id;
            // $user_detail = DB::table('project_contact')
            // ->insert(['c_position_title' => $position, 'c_user_id' => $lastInsertid]);

            if(count($user) < 1)
            {
              $result = array('code'=>400, "description"=>"No Records Found");
              return response()->json($result, 400);
            }
            else
            {
                 // $user = (object) $information;
                 // Mail::send('emails.add_new_account', ['user' => $user], function ($message) use ($user) {
                 //     $message->from('no-reply@murowcm.com', 'Stratus');
                 //     $message->to($user->email, $user->username)->subject('New Account Add on Stratus');
                 // });

              $user = (object) $information;
              //print_r($user);
              Mail::send('emails.add_new_account',['user' => $user], function ($message) use ($user) {
                  $message->from('no-reply@sw.ai', 'StratusCM');
                  $message->to($user->email, $user->username)->subject('New account added on StratusCM');
              });

         $result = array('description'=>"New user Insert Successfully",'code'=>200,'user_id'=>$lastInsert_id);
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
   Logout user detail
  --------------------------------------------------------------------------
  */
  // public function logout(Request $request)
  // {
  //   try
  //   {
  //     Auth::logout();
  //     Session::flush();    
  //     // return Redirect::to('/');
  //     $result = array('description'=>"User Logout Seccessfully",'code'=>200);
  //     return response()->json($result, 200);
  //   }
  //   catch(Exception $e)
  //   {
  //     return response()->json(['error' => 'Something is wrong'], 500);
  //   }
  // }


  /*
  --------------------------------------------------------------------------
   Get username by passing Email address
  --------------------------------------------------------------------------
  */
  public function forget_username(Request $request)
  {
    try
    {
      $email = trim($request['email']);
      $information = array(
          "email"         => $email
      );

      $rules = [
          "email"         => 'required|email|max:255',
      ];

      $validator = Validator::make($information, $rules);

      if ($validator->fails()) 
      {
          return $result = response()->json($validator->messages(),400);
      }
      else
      {
          $user = DB::table('users')
          ->select('email', 'username')
          ->where('email', '=', $email)
          ->get();
          //print_r($user);die;
          if(count($user) < 1)
          {
            $result = array('code'=>400, "email"=>"No Records Found");
            return response()->json($result, 400);
          }
          else
          {
              $html = '<br>';
              foreach ($user as $users){
                  $html.=$users->username.'<br>';
              }
            $maildata = array(
              'name'                => $user[0]->username,
              'email'               => $user[0]->email,
              'username'            => $user[0]->username,
              'users'               => $html,
              'date'                => date("M d, Y h:i a")
            );
            $user_single = (object) $maildata;
        //print_r($user_single);die;
        Mail::send('emails.forget_username', ['user' => $user_single], function ($message) use ($user_single) {
            $message->from('no-reply@sw.ai', 'StratusCM');
            $message->to($user_single->email, $user_single->username)->subject('Username request');
        });            
            $result = array('data'=>$user,'code'=>200);
            return response()->json($result, 200);
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
   Get password Confirmation email by passing Email address
  --------------------------------------------------------------------------
  */
  public function forget_password(Request $request)
  {
    try
    {
      $email = $request['email'];
      $information = array(
          "email"         => $email
      );

      $rules = [
          "email"         => 'required|max:255',
      ];

      $validator = Validator::make($information, $rules);

      if ($validator->fails()) 
      {
          // $result = array("error" => $validator->messages(), "code" => 400);
          // $data = array('data' =>  $validator->messages());  
          // return response()->json($result, 400);
          return $result = response()->json($validator->messages(),400);
      }
      else
      {
          $user = DB::table('users')
          ->select('id','email', 'username')
          ->where('username', '=', $email)
          ->first();
          if(count($user) < 1)
          {
            $result = array('code'=>400, "email"=>"No Records Found");
            $data = array('data' =>  $result);  
            return response()->json($result, 400);
          }
          else
          {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < 10; $i++) {
              $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            $user_email_password  = $randomString;
            $user_email_link      = sha1($user->id);
            // $password             = Hash::make($user_email_password);

            $user_update = DB::table('users')
            ->where('id', '=', $user->id)
            ->update(['generate_password' => $user_email_password, 'user_email_password_link' => $user_email_link]);

            if(count($user_update) < 1)
            {
              $result = array('code'=>400, "email"=>"No Records Found");
              // $data = array('data' =>  $result);  
              return response()->json($result, 400);
            }
            else
            {
            $maildata = array(
              'name'                => $user->username,
              'email'               => $user->email,
              'user_name'           => $user_email_link,
              'verification_code'   => $user_email_password,
              'date'                => date("M d, Y h:i a")
            );
            $user_single = (object) $maildata;
Mail::send('emails.reset_password_request', ['user' => $user_single], function ($message) use ($user_single) {
    $message->from('no-reply@sw.ai', 'StratusCM');
    $message->to($user_single->email, $user_single->name)->subject('Password request');
});      
              $result = array('code'=>200, "description"=>"A verification code has been sent to your email. Kindly check your inbox.");
              // $data = array('data' =>  $result);  
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
   Get password to click verified email
  --------------------------------------------------------------------------
  */
  public function password_confirmation($username)
  {
    try
    {
          $u_name = Crypt::decrypt($username);
          // echo $u_name = 'admin';
          // exit;
          $user = DB::table('users')
          ->select('id','email', 'username')
          ->where('username', '=', $u_name)
          ->first();
          if(count($user) < 1)
          {
            $result = array('code'=>400, "description"=>"No Records Found");
            // $data = array('data' =>  $result);  
            return response()->json($result, 400);
          }
          else
          {
          $result = array('code'=>200, "description"=>"Link Verified", "username" => $username);
          // $data = array('data' =>  $result);  
          return $result = response()->json($result, 200);
          // return $result = response()->json($data, 200);
          // return view('reset_password', $result);
          }
    }
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }

  /*
  --------------------------------------------------------------------------
   Get password to add verified code
  --------------------------------------------------------------------------
  */
  public function password_change(Request $request)
  {
    try
    {
        // $u_name = Crypt::decrypt($request['username']);
        $u_name = $request['username'];
        $verified_code = $request['verified_code'];
        $password = Hash::make($request['password']);
        $user = DB::table('users')
        ->select('id','email', 'username')
        ->where('user_email_password_link', '=', $u_name)
        ->where('generate_password', '=', $verified_code)
        ->first();
        if(count($user) < 1)
        {
          $result = array('code'=>400, "description"=>"Invalid Validation Code");
          return response()->json($result, 400);
        }
        else
        {
          $user_update = DB::table('users')
          ->where('id', '=', $user->id)
          ->update(['password' => $password, 'generate_password' => '', 'user_email_password_link' => '']);
          if(count($user_update) < 1)
          {
            $result = array('code'=>400, "description"=>"No Records Found");
            // $data = array('data' =>  $result);  
            return $result = response()->json($result, 400);
          }
          else
          {
            $result = array('code'=>200, "description"=>"Password Updated");
            // $data = array('data' =>  $result);  
            return $result = response()->json($result, 200);
          }
        }
    }
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }



    public  function insert_user_data()
    {
        $user_info = file_get_contents('php://input');
        $data = json_decode($user_info, TRUE);
        if (!is_array($data)) {
            $result = array("code" => 403, "description" => "invalid request body");
            return $result;
        }
        $user_id = array_key_exists("user_id",$data)?$data["user_id"]:null;
        $phone_type = array_key_exists("phone_type", $data) ? $data["phone_type"] : null;
        $phone_detail = array_key_exists("phone_detail", $data) ? $data["phone_detail"] : null;
        $information = array(
            "user_id"=>$user_id,
            "phone_type"         => $phone_type,
            "phone_detail"=>$phone_detail
        );
        $rules = [
            "user_id"=>'required',
            "phone_type"         => 'required',
            "phone_detail"=>'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails())
        {
            return $result = response()->json($validator->messages(),400);
        }
        else
        {
            UserData::create(['user_id' => $user_id, 'u_phone_type' => $phone_type, 'u_phone_detail' => $phone_detail]);
            $result = array('code'=>200, "description"=>"Added");
            return $result = response()->json($result, 200);

        }

    }




    public function get_user_info($user_id){
        $information = array(
            "user_id"=>$user_id,
        );
        $rules = [
            "user_id"=>'required',
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails())
        {
            return $result = response()->json($validator->messages(),400);
        }
        else
        {
                $user = DB::table('users_details')
                ->select('u_phone_type','u_phone_detail')
                ->where('user_id', '=', $user_id)->get();
                $result = array('code'=>200, "data"=>$user);
                return $result = response()->json($result, 200);
        }
    }


    public  function delete_user_data()
    {
        $user_info = file_get_contents('php://input');
        $data = json_decode($user_info, TRUE);
        if (!is_array($data)) {
            $result = array("code" => 403, "description" => "invalid request body");
            return $result;
        }
        $user_id = array_key_exists("user_id",$data)?$data["user_id"]:null;
        $information = array(
            "user_id"=>$user_id,

        );
        $rules = [
            "user_id"=>'required',
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails())
        {
            return $result = response()->json($validator->messages(),400);
        }
        else
        {
            UserData::where('user_id', '=',$user_id)->delete();
            $result = array('code'=>200, "description"=>"Deleted");
            return $result = response()->json($result, 200);

        }

    }




  public function login(Request $request)
  {
    // $user_check = new User();

  //      $input_data = [
  //           'email' => $request['email'],
  //           'password' => $request['password']
  //       ];
  //       // echo '<pre>';
  //       // print_r($input_data);
  //       // echo '</pre>';
  //       // exit;
  //        $messages = [
  //            'email.required' => 'Enter email address',
  //            'password.required' => 'You need a password',
  //        ];
  //       $rules = [
  //         'email'    => 'required|email',
  //         'password' => 'required|min:3'
  //       ];

  //       // doing the validation, passing post data, rules and the messages
  //       $validator = Validator::make($input_data, $rules, $messages);
  //       if ($validator->fails()) {
  //       // send back to the page with the input data and errors
  //         return Redirect::to('admin')->withInput()->withErrors($validator);
  //       }
  //       else {
  //           $email = $request['email'];
  //           $password = $request['password'];
  //           // $password = Hash::make($request['password']);

  //           $user = DB::table('users')
  //               ->where('email', '=', $email)
  //               ->where('password', '=', $password)
  //               ->first();

  //           if($user){
  //               session()->regenerate();
  //               session(['u_id'     => $user->id]);
  //               session(['u_name'   => $user->name]);
  //               session(['u_email'  => $user->email]);
  //               return redirect('profile');
  //            }
  //            else {
  //               $validator = Validator::make($request->all(), [
  //                   'login' => 'Invalid Username / Password'
  //               ]);

  //               return redirect('admin')
  //               ->withErrors($validator, 'login');
  //               // return view('login', $user);
  //               exit;
  //            }

  //       }
     }
     
     
     public function update_password(Request $request) {
        try
        {
            $email_verification = $request['email_verification'];
            $new_password = Hash::make($request['new_password']);
            if(Auth::attempt(['username' => $request['username'], 'password' => $request['password']]))
            {
                //echo 'df';die;
                $user = DB::table('users')
                ->select('id','email', 'username')
                ->where('email_verification', '=', $email_verification)
                ->first();
                if(count($user) < 1)
                {
                  $result = array('code'=>400, "description"=>"Email already verified!");
                  return response()->json($result, 400);
                }
                else
                {
                  $user_update = DB::table('users')
                  ->where('id', '=', $user->id)
                  ->update(['password' => $new_password, 'password_changed' => '1', 'email_verification' => '','status'=>1]);
                  if(count($user_update) < 1)
                  {
                    $result = array('code'=>400, "description"=>"No Records Found");
                    // $data = array('data' =>  $result);  
                    return $result = response()->json($result, 400);
                  }
                  else
                  {
                    $result = array('code'=>200, "description"=>"Password Updated");
                    // $data = array('data' =>  $result);  
                    return $result = response()->json($result, 200);
                  }
                } 
            }
            else{
                $result = array('code'=>400, "description"=>"Username /Password Wrong");
                return $result = response()->json($result, 400);
            }    
        }
        catch(Exception $e)
        {
          return response()->json(['error' => 'Something is wrong'], 500);
        } 
     }
     

     public function update_site_logo(Request $request) {
         //echo $userid = Auth::user()->user_parent;die;echo '<pre>';print_r(Auth::user());die;
        if(Auth::user()->role=="user")
        {
            $user = DB::table('users')
                ->select('id','company_name')
                ->where('id', '=', Auth::user()->user_parent)
                ->first();
            $company_name = $user->company_name;
            //echo '<pre>';print_r($user);die;
        }else{
           $company_name = Auth::user()->company_name;
        }
         $ws_key = $request['ws_key'];
         // Decode base64 data
        list($type, $data) = explode(';', $request['ws_value']);
        list(, $data) = explode(',', $data);
        $file_data = base64_decode($data);

        // Get file mime type
        $finfo = finfo_open();
        $file_mime_type = finfo_buffer($finfo, $file_data, FILEINFO_MIME_TYPE);

        // File extension from mime type
        if($file_mime_type == 'image/jpeg' || $file_mime_type == 'image/jpg')
                $file_type = 'jpeg';
        else if($file_mime_type == 'image/png')
                $file_type = 'png';
        else if($file_mime_type == 'image/gif')
                $file_type = 'gif';
        else 
                $file_type = 'other';

        // Validate type of file
        if(in_array($file_type, [ 'jpeg', 'png', 'gif' ])) {
                // Set a unique name to the file and save
                $file_name = 'uploads/'.uniqid() . '.' . $file_type;
                file_put_contents($file_name, $file_data);
                
                $company = DB::table('website_settings')
                ->select('website_settings.*')
                ->where('company_id', '=',$company_name)
                ->first();
                if($company)
                {
                    $query = DB::table('website_settings')
                        ->where('ws_key', $ws_key)
                        ->where('company_id', $company_name)
                        ->update(['ws_value' => $file_name]);
                }else{
                    $query_contact = DB::table('website_settings')
                        ->insert(['ws_key' => $ws_key,'ws_value'=>$file_name,'company_id'=>$company_name]);
                }
                
                
                $result = array('description'=>"Website logo updated Successfully",'code'=>200,'site_logo'=>$file_name);
                return response()->json($result, 200);
                die;
        }
        else {
                $result = array('code'=>400, "description"=>"Error : Only JPEG, PNG & GIF allowed");
                return response()->json($result, 400);
        }die;
         
     }
     
     public function get_site_logo(Request $request) {
        if(Auth::user()->role=="user")
        {
            $user = DB::table('users')
                ->select('id','company_name')
                ->where('id', '=', Auth::user()->user_parent)
                ->first();
            $company_name = $user->company_name;
            //echo '<pre>';print_r($user);die;
        }else{
           $company_name = Auth::user()->company_name;
        }
         $user = DB::table('website_settings')
                ->select('ws_value')
                ->where('ws_key', '=','website_logo')
                ->where('company_id', '=',$company_name)
                ->get();
                $result = array('code'=>200, "data"=>$user);
                return $result = response()->json($result, 200);
     }
     
     public function get_user_new_role(Request $request) {
        $user_id        = $request['user_id'];
        $u_company_id   = $request['u_company_id'];
        $project_id     = $request['project_id'];
        $information = array(
            "user_id"=>$user_id,
        );
        $rules = [
            "user_id"=>'required',
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails())
        {
            return $result = response()->json($validator->messages(),400);
        }
        else
        {
            $company = DB::table('project_firm')
            ->leftJoin('company_type', 'project_firm.f_type', '=', 'company_type.ct_id')
            ->select('project_firm.f_id','project_firm.f_name','company_type.ct_name')
            ->where('project_firm.f_id', '=', $u_company_id)
            ->get();
            //print_r($company);
            $check_engineer =  strpos(strtolower($company[0]->ct_name), 'engineer');
            //print_r($check_engineer);
            if($check_engineer===FALSE)
            {
               
                $query = DB::table('project_notice_award')
                ->leftJoin('project_firm', 'project_notice_award.pna_contactor_name', '=', 'project_firm.f_id')
                ->select('project_firm.f_name as agency_name','project_notice_award.*')
                ->where('project_notice_award.pna_project_id', '=', $project_id)
                ->groupBy('project_notice_award.pna_id')
                ->orderBy('project_notice_award.pna_id','ASC')
                ->get();
                //print_r($query);die;
                if(count($query) < 1)
                {
                    $new_role = '';
                }else{
                    if($query[0]->pna_contactor_name==$u_company_id)
                    {
                        $new_role = 'contractor';
                    }else{
                        $new_role = '';
                    }
                }
            }else{
                $new_role = 'engineer';
            }
            //echo $new_role;die;
            if($new_role)
            {
                $result = array('code'=>200, "new_role"=>$new_role);
                return $result = response()->json($result, 200);
            }else{
                $result = array('code'=>404, "description"=>"No Role Found");
                return response()->json($result, 404);
            }
        }
     }
     
     public function change_password(Request $request) {
        try
        {
            $userid = Auth::user()->id;
            $password = Hash::make($request['password']);
            $user_update = DB::table('users')
            ->where('id', '=', $userid)
            ->update(['password' => $password]);
            if(count($user_update) < 1)
            {
              $result = array('code'=>400, "description"=>"No Records Found");
              // $data = array('data' =>  $result);  
              return $result = response()->json($result, 400);
            }
            else
            {
              $result = array('code'=>200, "description"=>"Password Updated");
              // $data = array('data' =>  $result);  
              return $result = response()->json($result, 200);
            }
        }
        catch(Exception $e)
        {
          return response()->json(['error' => 'Something is wrong'], 500);
        } 
     }
}