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
class ContactController extends Controller {

  /*
  ----------------------------------------------------------------------------------
   Get all Contacts Project
  ----------------------------------------------------------------------------------
  */
  public function get_contact_project(Request $request, $project_id)
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
        $permission_key = 'contact_view_permission_all';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
          $query = DB::table('users')
          ->leftJoin('project_contact', 'users.id', '=', 'project_contact.c_user_id')
          ->leftJoin('projects', 'project_contact.c_project_id', '=', 'projects.p_id')
          ->leftJoin('project_firm', 'users.company_name', '=', 'project_firm.f_id')
          ->leftJoin('company_type', 'project_firm.f_type', '=', 'company_type.ct_id')
          ->select('projects.*', 'users.id', 'users.email', 'users.username', 'users.first_name', 'users.last_name', 'users.phone_number', 'users.status', 'users.position_title', 'users.role', 'project_firm.f_name as company_name','company_type.ct_name')
          ->where('project_contact.c_project_id', '=', $project_id)
          ->where('users.status', '!=', 2)
          ->orderBy('role')
          ->get();

          foreach($query as $d){
              $user_data = DB::table('users_details')->select('u_phone_type','u_phone_detail')->where('user_id', '=',$d->id)->get();
              $d->user_detail = $user_data;

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
        }
      }
      catch(Exception $e)
      {
        return response()->json(['error' => 'Something is wrong'], 500);
      }
  }

  /*
  ----------------------------------------------------------------------------------
   Get all Contacts
  ----------------------------------------------------------------------------------
  */
  public function get_contact(Request $request, $project_id)
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
      $permission_key = 'contact_view_permission_all';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
          $query = DB::table('project_contact')
          ->select('c_user_id')
          // ->where('project_contact.c_project_id', '!=', $project_id)
          ->where('c_project_id', '=', $project_id)
          ->get();

          $fullarray = array();
          foreach ($query as $q) {
            $fullarray[] = $q->c_user_id;
          }  
            
           //  print_r($fullarray);  
           // exit;   

          $project_owner = DB::table('projects')
          ->select('p_user_id')
          ->where('p_id', '=', $project_id)
          ->first();

           // print_r($project_owner->p_user_id);  
           $project_owner = $project_owner->p_user_id;  
        
          $query2 = DB::table('users')
          // ->leftJoin('projects', 'project_contact.c_project_id', '=', 'projects.p_id')
          ->leftJoin('project_firm', 'users.company_name', '=', 'project_firm.f_id')
          ->select('users.id', 'users.email', 'users.username', 'users.first_name', 'users.last_name', 'users.phone_number', 'users.status', 'users.position_title', 'users.role', 'project_firm.f_name as company_name')
          // ->where('project_contact.c_project_id', '!=', $project_id)
          ->whereNotIn('users.id', $fullarray)
          ->where('status', '!=', 2)
          ->where('user_parent', '=', $project_owner)
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
        }
      }
      catch(Exception $e)
      {
        return response()->json(['error' => 'Something is wrong'], 500);
      }
  }

  /*
  --------------------------------------------------------------------------
  Add contact is project
  --------------------------------------------------------------------------
  */
  public function add_contact_project(Request $request, $project_id, $id)
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
      //   $result = array('code'=>403, "description"=>"Access Denies");
      //   return response()->json($result, 403);
      // } 
      // else { 
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'contact_add';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
          $getuser = new User();
          $user = DB::table('project_contact')
          ->insert(['c_project_id' => $project_id, 'c_user_id' => $id]);
          
          // Add Contact view permission
          $query_contact = DB::table('project_user_permission')
          ->insert(['pup_project_id' => $project_id, 'pup_user_id' => $id, 'pup_permission_key' => 'contact_view_permission_all', 'pup_access' => 'true', 'pup_permission_assign_user_id' => $user_id]);
         
          if(count($user) < 1)
          {
            $result = array('code'=>400, "description"=>"No Records Found");
            return response()->json($result, 400);
          }
          else
          {

            // Start Automation Process
            // $query = DB::table('users')
            // ->leftJoin('project_contact', 'users.id', '=', 'project_contact.c_user_id')
            // ->leftJoin('projects', 'project_contact.c_project_id', '=', 'projects.p_id')
            // ->select('projects.*', 'users.id', 'users.email', 'users.username', 'users.first_name', 'users.last_name', 'users.phone_number', 'users.status', 'users.position_title', 'users.role')
            // ->where('project_contact.c_project_id', '=', $project_id)
            // // ->whereIn('users.role', ['manager', 'contractor'])
            // ->where('users.status', '!=', 2)
            // ->get();

            $check_project_users = app('App\Http\Controllers\Projects\PermissionController')->check_project_user($project_id);

            $query_add_user = DB::table('users')
            ->select('id', 'email', 'username', 'first_name', 'last_name', 'phone_number', 'status', 'position_title', 'role')
            ->where('id', '=', $id)
            ->get();

            // print_r($query_add_user);
            // die();

            // Check User Project Permission  
              foreach ($check_project_users as $check_project_user) {
              // Check User Permission Parameter 
//              $user_id              = $check_project_user->id;
//              $permission_key       = 'contact_view_permission_all';
//              // Notification Parameter
//              $project_id           = $project_id;
//              $notification_title   = 'Add new user '.$query_add_user[0]->username.' has been added in Project ' .$check_project_user->p_name;
//              $url                  = App::make('url')->to('/');
//              $link                 = "/dashboard/".$project_id."/contact/".$query_add_user[0]->id;
//              $date                 = date("M d, Y h:i a");
//              $email_description    = 'Add new user <strong>'.$query_add_user[0]->username.'</strong> in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
//
//              $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
//              if(count($check_single_user_permission) < 1){
//                continue;
//              }
//              else {
//                // Send Notification to users
//                $project_notification_query = app('App\Http\Controllers\Projects\NotificationController')->add_notification($notification_title, $link, $project_id, $check_single_user_permission[0]->pup_user_id);
//             
//                $user_detail = array(
//                  'id'              => $check_project_user->id,
//                  'name'            => $check_project_user->username,
//                  'email'           => $check_project_user->email,
//                  'link'            => $link,
//                  'date'            => $date,
//                  'project_name'    => $check_project_user->p_name,
//                  'title'           => $notification_title,
//                  'description'     => $email_description
//                );
//                $user_single = (object) $user_detail;
//                Mail::send('emails.send_notification',['user' => $user_single], function ($message) use ($user_single) {
//                    $message->from('no-reply@sw.ai', 'StratusCM');
//                    $message->to($user_single->email, $user_single->name)->subject($user_single->title);
//                });
//              }

            } // End Foreach

            // foreach ($query as $user_one) {
            //     print_r($user_one);
            //     $project_id           = $project_id;
            //     $description          = $query_add_user[0]->first_name." ".$query_add_user[0]->last_name." has been added in Project: ".$user_one->p_name;
            //     $link                 = "/dashboard/".$query_add_user[0]->id."/view-contact";
            //     $notification_status  = '1';
            //     $sender_user_id       = Auth::user()->id;


            //     $query = DB::table('project_notifications')
            //     ->insert(['pn_description' => $description, 'pn_link' => $link, 'pn_status' => $notification_status, 'pn_project_id' => $project_id, 'pn_sender_user_id' => $sender_user_id, 'pn_receiver_user_id' => $user_one->id]);

            //     $user_detail = array(
            //       'id'              => $user_one->id,
            //       'name'            => $user_one->username,
            //       'email'           => $user_one->email,
            //       'link'            => $link,
            //       'project_name'    => $user_one->p_name,
            //       'contact_name'    => $query_add_user[0]->first_name." ".$query_add_user[0]->last_name
            //     );
            //     $user_single = (object) $user_detail;
            //     Mail::send('emails.new_contact_add',['user' => $user_single], function ($message) use ($user_single) {
            //         $message->from('no-reply@sw.ai', 'StratusCM');
            //         $message->to($user_single->email, $user_single->name)->subject('New Contact add in Project');
            //     });
            // }
            // End Automation Process

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
   Delete user to project
  --------------------------------------------------------------------------
  */
  public function user_delete_project(Request $request, $project_id, $id)
  {
    try
    {
      //  $user = array(
      //   'id'        => Auth::user()->id,
      //   'role'      => Auth::user()->role
      // );
      // $user = (object) $user;
      // $post = new Resource_Post(); // You create a new resource Post instance
      // if (Gate::forUser($user)->denies('allow_admin', [$post,false])) { 
      //   $result = array('code'=>403, "description"=>"Access Denies");
      //   return response()->json($result, 403);
      // } 
      // else { 
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'contact_remove';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $getuser = new User();
        $user = DB::table('project_contact')
        ->where('c_project_id', $project_id)
        ->where('c_user_id', $id)
        ->delete();

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
   Add User Permission per Project
  --------------------------------------------------------------------------
  */
  public function add_user_permission(Request $request, $project_id, $user_id)
  {
      try
      {    
        $project_id                   = $project_id;
        $user_id                      = $user_id;
        $permission_key               = $request['permission_key'];
        // $permission_access            = $request['permission_access'];
        $permission_assign_user_id    = Auth::user()->id;

        $information = array(

            "project_id"                  => $project_id,
            "user_id"                     => $user_id,
            "permission_key"              => $permission_key,
            // "permission_access"           => $permission_access,
            "permission_assign_user_id"   => $permission_assign_user_id

        );
        $rules = [
            "project_id"                  => 'required|numeric',
            "user_id"                     => 'required|numeric',
            "permission_key"              => 'required',
            // "permission_access"           => 'required',
            "permission_assign_user_id"   => 'required|numeric'
        ];

        $validator = Validator::make($information, $rules);
        if ($validator->fails())
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
          // Delete User Permission
          $user = DB::table('project_user_permission')
          ->where('pup_project_id', $project_id)
          ->where('pup_user_id', $user_id)
          ->delete();
        
          foreach ($permission_key  as $key => $permission_single) {
              // print_r('Project ID: '.$project_id.' User ID: '.$user_id.' Permission Key: '.$permission_single.' Permission Assign User: '.$permission_assign_user_id.'<br/>');
              $query = DB::table('project_user_permission')
              ->insert(['pup_project_id' => $project_id, 'pup_user_id' => $user_id, 'pup_permission_key' => $permission_single, 'pup_access' => 'true', 'pup_permission_assign_user_id' => $permission_assign_user_id]);
          }
         
          if(count($query) < 1)
          {
            $result = array('code'=>400, "description"=>$query);
            return response()->json($result, 400);
          }
          else
          {
            $result = array('description'=>"Add permission successfully",'code'=>200);
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
   Delete user all permission by passing project_id & user_id
  --------------------------------------------------------------------------
  */
  public function delete_user_permission(Request $request, $project_id, $id)
  {
    try
    {
      //  $user = array(
      //   'id'        => Auth::user()->id,
      //   'role'      => Auth::user()->role
      // );
      // $user = (object) $user;
      // $post = new Resource_Post(); // You create a new resource Post instance
      // if (Gate::forUser($user)->denies('allow_admin', [$post,false])) { 
      //   $result = array('code'=>403, "description"=>"Access Denies");
      //   return response()->json($result, 403);
      // } 
      // else { 
        // Delete User Permission
        $user = DB::table('project_user_permission')
        ->where('pup_project_id', $project_id)
        ->where('pup_user_id', $id)
        ->delete();

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
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }


  /*
  ----------------------------------------------------------------------------------
   Get all User Permission by passing project_id & user_id
  ----------------------------------------------------------------------------------
  */
  public function get_user_permission(Request $request, $project_id, $user_id)
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
          $query = DB::table('project_user_permission')
          ->leftJoin('users', 'project_user_permission.pup_permission_assign_user_id', '=', 'users.id')
          ->select('project_user_permission.*', 'users.username as pup_permission_assign_user_name')
          ->where('pup_project_id', $project_id)
          ->where('pup_user_id', $user_id)
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
  ----------------------------------------------------------------------------------
   Check User Permission by passing project_id & permission_key
  ----------------------------------------------------------------------------------
  */
  public function get_user_permission_key(Request $request, $project_id)
  {
      try
      {
        $project_id                   = $project_id;
        $permission_key               = $request['permission_key'];
        $user_id_permission           = Auth::user()->id;

        $information = array(
            "project_id"                  => $project_id,
            "user_id_permission"          => $user_id_permission,
            "permission_key"              => $permission_key,
        );
        $rules = [
            "project_id"                  => 'required|numeric',
            "user_id_permission"          => 'required|numeric',
            "permission_key"              => 'required'
        ];

        $validator = Validator::make($information, $rules);
        if ($validator->fails())
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
          $permission_query = DB::table('project_user_permission')
          ->select()
          ->where('pup_project_id', '=', $project_id)
          ->where('pup_permission_key', '=', $permission_key)
          ->where('pup_user_id', '=', $user_id_permission)
          ->get();
          if(count($permission_query) < 1)
          { 
            $result = array('code'=>403, "description"=>"Access Denies");
            return response()->json($result, 403);
          }
          else
          {
            $result = array('description'=>$permission_query,'code'=>200);
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
   Add User Notification per Project
  --------------------------------------------------------------------------
  */
  public function add_user_notification(Request $request, $project_id, $user_id)
  {
      try
      {    
        $project_id                   = $project_id;
        $user_id                      = $user_id;
        $notification_key             = $request['notification_key'];
        // $permission_access            = $request['permission_access'];
        $permission_assign_user_id    = Auth::user()->id;

        $information = array(

            "project_id"                  => $project_id,
            "user_id"                     => $user_id,
            //"notification_key"              => $notification_key,
            // "permission_access"           => $permission_access,
            "permission_assign_user_id"   => $permission_assign_user_id

        );
        $rules = [
            "project_id"                  => 'required|numeric',
            "user_id"                     => 'required|numeric',
            //"notification_key"              => 'required',
            // "permission_access"           => 'required',
            "permission_assign_user_id"   => 'required|numeric'
        ];

        $validator = Validator::make($information, $rules);
        if ($validator->fails())
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
          // Delete User Permission
          $user = DB::table('project_user_notification')
          ->where('pun_project_id', $project_id)
          ->where('pun_user_id', $user_id)
          ->delete();
        
            if(count($notification_key))
            {
                foreach ($notification_key  as $key => $notification_single) {
                    // print_r('Project ID: '.$project_id.' User ID: '.$user_id.' Permission Key: '.$notification_single.' Permission Assign User: '.$permission_assign_user_id.'<br/>');
                    $query = DB::table('project_user_notification')
                    ->insert(['pun_project_id' => $project_id, 'pun_user_id' => $user_id, 'pun_notification_key' => $notification_single, 'pun_access' => 'true', 'pun_notification_assign_user_id' => $permission_assign_user_id]);
                }
                if(count($query) < 1)
                {
                  $result = array('code'=>400, "description"=>$query);
                  return response()->json($result, 400);
                }
                else
                {
                  $result = array('description'=>"Add notification successfully",'code'=>200);
                  return response()->json($result, 200);
                }
            }else{
                
                $result = array('description'=>"Add notification successfully",'code'=>200);
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
  ----------------------------------------------------------------------------------
   Get all User Notification by passing project_id & user_id
  ----------------------------------------------------------------------------------
  */
  public function get_user_notification(Request $request, $project_id, $user_id)
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
          $query = DB::table('project_user_notification')
          ->leftJoin('users', 'project_user_notification.pun_notification_assign_user_id', '=', 'users.id')
          ->select('project_user_notification.*', 'users.username as pun_notification_assign_user_name')
          ->where('pun_project_id', $project_id)
          ->where('pun_user_id', $user_id)
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
}