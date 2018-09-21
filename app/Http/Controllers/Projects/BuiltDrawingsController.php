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

 
class BuiltDrawingsController extends Controller {

  /*
  --------------------------------------------------------------------------
   Add Build Drawing
  --------------------------------------------------------------------------
  */
  public function add_built_drawing(Request $request)
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
        $description  = $request['description'];
        $file_path    = $request['file_path'];
        $project_id   = $request['project_id'];
        $built_filed_on = $request['built_filed_on'];
        $user_id      = Auth::user()->id;
        $status       = 'active';
    // Check User Permission Parameter 
    $user_id = Auth::user()->id;
    $permission_key = 'drawing_add';
    $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
    if(count($check_single_user_permission) < 1){
      $result = array('code'=>403, "description"=>"Access Denies");
      return response()->json($result, 403);
    }
    else {

        $information = array(
            "description" => $description,
            "file_path"   => $file_path,
            "project_id"  => $project_id,
            "user_id"     => $user_id,
            "built_filed_on"=>$built_filed_on
        );

        $rules = [
            'description' => 'required',
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
             $query = DB::table('project_built_drawing')
            ->insertGetId(['pbd_filed_on'=>$built_filed_on,'pbd_description' => $description, 'pbd_file_path' => $file_path, 'pbd_project_id' => $project_id, 'pbd_user_id' => $user_id, 'pbd_status' => $status]);

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
              //echo '<pre>';print_r($check_project_users);die;
              // Check User Project Permission  
              foreach ($check_project_users as $check_project_user) {
               $check_engineer =  strpos(strtolower($check_project_user->ct_name), 'engineer');
                //echo '<pre>';print_r($check_project_user);die;
                if($check_engineer>=0)
                {
                   
                    //echo '<pre>';print_r($check_project_user);die;
                    //echo $check_project_user->username;echo $check_project_user->id;die;
                    // Check User Permission Parameter 
                    $user_id              = $check_project_user->id;
                    $permission_key       = 'drawing_view_all';
                    // Notification Parameter
                    $project_id           = $project_id;
                    $notification_title   = 'New As built drawing added in Project: ' .$check_project_user->p_name;
                    $url                  = App::make('url')->to('/');
                    $link                 = "/dashboard/".$project_id."/built_drawing/".$query;
                    $date                 = date("M d, Y h:i a");
                    $email_description    = 'New As built drawing added in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

                    $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
                    if(count($check_single_user_permission) < 1){
                      continue;
                    }
                    else {
                          $notification_key     = 'as_built_drawings';
                          $check_project_user_notification = app('App\Http\Controllers\Projects\PermissionController')->check_project_user_notification($project_id,$user_id,$notification_key);
                          if(count($check_project_user_notification) < 1){
                            continue;
                          }else{
                                echo '<pre>';print_r($check_project_user);die;
                                echo $check_project_user->email;
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
                }
                //die('ooo');
              } // End Foreach
              // End Check User Permission and send notification and email 

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
   Update Build Drawing Status by passing pbd_id
  --------------------------------------------------------------------------
  */
  public function update_built_drawing_status_contractor(Request $request, $pbd_id)
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
        $contractor_redline   = $request['contractor_redline'];
        $built_plan           = $request['built_plan'];
        //$status               = $request['status'];
        $user_id              = Auth::user()->id;
      
        $information = array(
            "user_id"     => $user_id,
            'built_plan'  => $built_plan,
            //'status'      => $status
        );

        $rules = [
            'user_id'     => 'required|numeric',
            //'built_plan'  => 'required',
            //'status'      => 'required'
        ];

        $validator = Validator::make($information, $rules);
        if ($validator->fails()) {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else {
          $query = DB::table('project_built_drawing')
          ->where('pbd_id', '=', $pbd_id)
          ->update(['pbd_user_id' => $user_id, 'pbd_contractor_redline' => $contractor_redline, 'pbd_change_plan' => $built_plan]);
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
      // }
    }
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }

  /*
  --------------------------------------------------------------------------
   Update Build Drawing Status by passing pbd_id
  --------------------------------------------------------------------------
  */
  public function update_built_drawing_status_engineer(Request $request, $pbd_id)
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
        $engineer_redline     = $request['engineer_redline'];
        //$contractor_redline   = $request['contractor_redline'];
        $built_plan           = $request['built_plan'];
        //$status               = $request['status'];
        $project_id           = $request['project_id'];
        $user_id              = Auth::user()->id;
        
        $information = array(
            "user_id"     => $user_id,
            'built_plan'  => $built_plan,
            //'status'      => $status,
            'project_id'  => $project_id
        );

        $rules = [
            'user_id'     => 'required|numeric',
            'project_id'  => 'required|numeric',
            //'built_plan'  => 'required',
            //'status'      => 'required'
        ];

        $validator = Validator::make($information, $rules);
        if ($validator->fails()) {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else {
          $query = DB::table('project_built_drawing')
          ->where('pbd_id', '=', $pbd_id)
          ->update(['pbd_engineer_redline' => $engineer_redline, 'pbd_user_id' => $user_id, 'pbd_change_plan' => $built_plan]);
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
            $permission_key       = 'drawing_view_all';
            // Notification Parameter
            $project_id           = $project_id;
            $notification_title   = 'As Built drawing has been updated in Project: ' .$check_project_user->p_name;
            $url                  = App::make('url')->to('/');
            $link                 = "/dashboard/".$project_id."/built_drawing/".$pbd_id;
            $date                 = date("M d, Y h:i a");
            $email_description    = 'As Built drawing has been updated in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

            $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
            if(count($check_single_user_permission) < 1){
              continue;
            }
            else {
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

            } // End Foreach
            // End Check User Permission and send notification and email 
            $result = array('data'=>$query,'code'=>200);
            return response()->json($result, 200);
          }
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
   Update Build Drawing by passing pbd_id
  --------------------------------------------------------------------------
  */
  public function update_built_drawing(Request $request, $pbd_id)
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
        $project_id   = $request['project_id'];
        $user_id      = Auth::user()->id;
        //$status       = $request['status'];
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'drawing_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            "project_id"  => $project_id,
            "user_id"     => $user_id,
            //"status"      => $status
        );

        $rules = [
            'project_id'  => 'required|numeric',
            'user_id'     => 'required|numeric',
            //'status'      => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else {
          $query = DB::table('project_built_drawing')
          ->where('pbd_id', '=', $pbd_id)
          ->update(['pbd_project_id' => $project_id, 'pbd_user_id' => $user_id]);
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
   Get single Build Drawing by passing pbd_id
  --------------------------------------------------------------------------
  */
  public function get_built_drawing(Request $request, $pbd_id)
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
        $query = DB::table('project_built_drawing')
        ->leftJoin('documents', 'project_built_drawing.pbd_file_path', '=', 'documents.doc_id')
        ->leftJoin('projects', 'project_built_drawing.pbd_project_id', '=', 'projects.p_id')
        ->leftJoin('users', 'project_built_drawing.pbd_user_id', '=', 'users.id')
        ->select('project_built_drawing.*', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('pbd_id', '=', $pbd_id)
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
   Get all Build Drawing by passing Project ID
  ----------------------------------------------------------------------------------
  */
  public function get_built_drawing_project(Request $request, $project_id)
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
      $permission_key = 'drawing_view_all';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
          $query = DB::table('project_built_drawing')
        ->leftJoin('documents', 'project_built_drawing.pbd_file_path', '=', 'documents.doc_id')
        ->leftJoin('projects', 'project_built_drawing.pbd_project_id', '=', 'projects.p_id')
        ->leftJoin('users', 'project_built_drawing.pbd_user_id', '=', 'users.id')
        ->select('project_built_drawing.*', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('pbd_project_id', '=', $project_id)
        ->orderBy('project_built_drawing.pbd_id','ASC')
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