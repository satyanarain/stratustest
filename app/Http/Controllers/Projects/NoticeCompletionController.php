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


class NoticeCompletionController extends Controller {
 
  /*
  --------------------------------------------------------------------------
   Add Notice Completion Controller
  --------------------------------------------------------------------------
  */
  public function add_notice_completion(Request $request)
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
        $noc_rec_text           = $request['noc_rec_text'];
        $noc_rec_name           = $request['noc_rec_name'];
        $noc_rec_street         = $request['noc_rec_street'];
        $noc_rec_adress         = $request['noc_rec_adress'];
        $noc_notice_text_1      = $request['noc_notice_text_1'];
        $noc_notice_text_2      = $request['noc_notice_text_2'];
        $noc_notice_text_3      = $request['noc_notice_text_3'];
        $noc_notice_text_4      = $request['noc_notice_text_4'];
        $noc_notice_text_5      = $request['noc_notice_text_5'];
        $noc_notice_text_6      = $request['noc_notice_text_6'];
        $noc_notice_text_7      = $request['noc_notice_text_7'];
        $noc_notice_text_8      = $request['noc_notice_text_8'];
        $noc_notice_text_9      = $request['noc_notice_text_9'];
        $noc_notice_text_10     = $request['noc_notice_text_10'];
        $noc_notice_text_11     = $request['noc_notice_text_11'];
        $noc_notice_text_12     = $request['noc_notice_text_12'];
        $noc_notice_text_13     = $request['noc_notice_text_13'];
        $noc_notice_text_14     = $request['noc_notice_text_14'];
        $noc_notice_text_15     = $request['noc_notice_text_15'];
        $noc_notice_text_16     = $request['noc_notice_text_16'];
        $noc_notice_text_17     = $request['noc_notice_text_17'];
        $noc_notice_text_18     = $request['noc_notice_text_18'];
        $noc_notice_text_19     = $request['noc_notice_text_19'];
        $noc_notice_text_20     = $request['noc_notice_text_20'];
        $noc_notice_text_21     = $request['noc_notice_text_21'];
        $noc_notice_text_22     = $request['noc_notice_text_22'];
        $noc_ver_text_1         = $request['noc_ver_text_1'];
        $noc_ver_text_2         = $request['noc_ver_text_2'];
        $noc_ver_text_3         = $request['noc_ver_text_3'];
        $noc_ver_text_4         = $request['noc_ver_text_4'];
        $noc_ver_text_5         = $request['noc_ver_text_5'];
        $noc_ver_text_6         = $request['noc_ver_text_6'];
        $noc_ver_text_7         = $request['noc_ver_text_7'];
        $noc_ser_text_1         = $request['noc_ser_text_1'];
        $noc_ser_text_2         = $request['noc_ser_text_2'];
        $noc_ser_text_3         = $request['noc_ser_text_3'];
        $noc_ser_text_4         = $request['noc_ser_text_4'];
        $noc_ser_text_5         = $request['noc_ser_text_5'];
        $noc_ser_text_6         = $request['noc_ser_text_6'];
        $noc_ser_text_7         = $request['noc_ser_text_7'];
        $noc_ser_text_8         = $request['noc_ser_text_8'];
        $noc_ser_text_9         = $request['noc_ser_text_9'];
        $noc_ser_text_10        = $request['noc_ser_text_10'];
        $noc_ser_text_11        = $request['noc_ser_text_11'];
        $noc_ser_text_12        = $request['noc_ser_text_12'];
        $noc_ser_text_13        = $request['noc_ser_text_13'];
        $noc_ser_text_14        = $request['noc_ser_text_14'];
        $noc_ser_text_15        = $request['noc_ser_text_15'];
        $noc_ser_text_16        = $request['noc_ser_text_16'];
        $noc_ser_text_17        = $request['noc_ser_text_17'];
        $noc_con_text_1         = $request['noc_con_text_1'];
        $noc_con_text_2         = $request['noc_con_text_2'];
        $noc_con_text_3         = $request['noc_con_text_3'];
        $noc_con_text_4         = $request['noc_con_text_4'];
        $noc_con_text_5         = $request['noc_con_text_5'];
        $noc_con_text_6         = $request['noc_con_text_6'];
        $noc_all_potential_claimants = $request['noc_potential'];
        $noc_project_id         = $request['noc_project_id'];
        $noc_file_path          = $request['noc_file_path'];
        $date_noc_filed         = $request['date_noc_filed'];
        $improvement_type       = $request['improvement_type'];
        $noc_user_id            = Auth::user()->id;
        $noc_status             = 'active';
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'notice_completion_add';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($noc_project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            // "noc_rec_text"        => $noc_rec_text,
            // "noc_rec_name"        => $noc_rec_name,
            // "noc_rec_street"      => $noc_rec_street,
            // "noc_rec_adress"      => $noc_rec_adress,
            "noc_file_path"       => $noc_file_path,
            "noc_project_id"      => $noc_project_id,
            "noc_user_id"         => $noc_user_id,
        );

        $rules = [
            // 'noc_rec_text'        => 'required',
            // 'noc_rec_name'        => 'required',
            // 'noc_rec_street'      => 'required',
            // 'noc_rec_adress'      => 'required',
            'noc_file_path'       => 'required|numeric',
            'noc_project_id'      => 'required|numeric',
            'noc_user_id'         => 'required|numeric'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
             $query = DB::table('project_notice_of_completion')
            ->insert(['date_noc_filed'=>$date_noc_filed,'improvement_type'=>$improvement_type,'noc_rec_text' => $noc_rec_text, 'noc_rec_name' => $noc_rec_name, 'noc_rec_street' => $noc_rec_street, 'noc_rec_adress' => $noc_rec_adress, 'noc_notice_text_1' => $noc_notice_text_1, 'noc_notice_text_2' => $noc_notice_text_2, 'noc_notice_text_3' => $noc_notice_text_3, 'noc_notice_text_4' => $noc_notice_text_4, 'noc_notice_text_5' => $noc_notice_text_5, 'noc_notice_text_6' => $noc_notice_text_6, 'noc_notice_text_7' => $noc_notice_text_7, 'noc_notice_text_8' => $noc_notice_text_8, 'noc_notice_text_9' => $noc_notice_text_9, 'noc_notice_text_10' => $noc_notice_text_10, 'noc_notice_text_11' => $noc_notice_text_11, 'noc_notice_text_12' => $noc_notice_text_12, 'noc_notice_text_13' => $noc_notice_text_13, 'noc_notice_text_14' => $noc_notice_text_14, 'noc_notice_text_15' => $noc_notice_text_15, 'noc_notice_text_16' => $noc_notice_text_16, 'noc_notice_text_17' => $noc_notice_text_17, 'noc_notice_text_18' => $noc_notice_text_18, 'noc_notice_text_19' => $noc_notice_text_19, 'noc_notice_text_20' => $noc_notice_text_20, 'noc_notice_text_21' => $noc_notice_text_21, 'noc_notice_text_22' => $noc_notice_text_22, 'noc_ver_text_1' => $noc_ver_text_1, 'noc_ver_text_2' => $noc_ver_text_2, 'noc_ver_text_3' => $noc_ver_text_3, 'noc_ver_text_4' => $noc_ver_text_4, 'noc_ver_text_5' => $noc_ver_text_5, 'noc_ver_text_6' => $noc_ver_text_6, 'noc_ver_text_7' => $noc_ver_text_7, 'noc_ser_text_1' => $noc_ser_text_1, 'noc_ser_text_2' => $noc_ser_text_2, 'noc_ser_text_3' => $noc_ser_text_3, 'noc_ser_text_4' => $noc_ser_text_4, 'noc_ser_text_5' => $noc_ser_text_5, 'noc_ser_text_6' => $noc_ser_text_6, 'noc_ser_text_7' => $noc_ser_text_7, 'noc_ser_text_8' => $noc_ser_text_8, 'noc_ser_text_9' => $noc_ser_text_9, 'noc_ser_text_10' => $noc_ser_text_10, 'noc_ser_text_11' => $noc_ser_text_11, 'noc_ser_text_12' => $noc_ser_text_12, 'noc_ser_text_13' => $noc_ser_text_13, 'noc_ser_text_14' => $noc_ser_text_14, 'noc_ser_text_15' => $noc_ser_text_15, 'noc_ser_text_16' => $noc_ser_text_16, 'noc_ser_text_17' => $noc_ser_text_17, 'noc_con_text_1' => $noc_con_text_1, 'noc_con_text_2' => $noc_con_text_2, 'noc_con_text_3' => $noc_con_text_3, 'noc_con_text_4' => $noc_con_text_4, 'noc_con_text_5' => $noc_con_text_5, 'noc_con_text_6' => $noc_con_text_6, 'noc_all_potential_claimants' => $noc_all_potential_claimants, 'noc_project_id' => $noc_project_id, 'noc_file_path' => $noc_file_path, 'noc_user_id' => $noc_user_id, 'noc_status' => $noc_status]);

            if(count($query) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {
              $project_id = $noc_project_id;
              // Start Check User Permission and send notification and email  
              // Get Project Users
              $check_project_users = app('App\Http\Controllers\Projects\PermissionController')->check_project_user($project_id);

              // Check User Project Permission  
              foreach ($check_project_users as $check_project_user) {
                // Check User Permission Parameter 
                $user_id              = $check_project_user->id;
                $permission_key       = 'notice_completion_view_all';
                // Notification Parameter
                $project_id           = $project_id;
                $notification_title   = 'Add new notice of completion in Project: ' .$check_project_user->p_name;
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/notice_completion";
                $date                 = date("M d, Y h:i a");
                $email_description    = 'Add new notice of completion in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

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

              $result = array('description'=>"Add notice of completion successfully",'code'=>200);
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
   Update Notice Completion by passing noc_id
  --------------------------------------------------------------------------
  */
  public function update_notice_completion(Request $request, $noc_id)
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
        $status       = $request['status'];
        $date_noc_filed  = $request['date_noc_filed'];
        $improvement_type = $request['improvement_type'];
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'notice_completion_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            "noc_project_id"  => $project_id,
            "user_id"         => $user_id,
            "noc_status"      => $status
        );

        $rules = [
            'noc_project_id'  => 'required|numeric',
            'user_id'         => 'required|numeric',
            'noc_status'      => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else {
          $query = DB::table('project_notice_of_completion')
          ->where('noc_id', '=', $noc_id)
          ->update(['date_noc_filed'=>$date_noc_filed,'improvement_type'=>$improvement_type,'noc_project_id' => $project_id, 'noc_user_id' => $user_id, 'noc_status' => $status]);
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
              $permission_key       = 'notice_completion_view_all';
              // Notification Parameter
              $project_id           = $project_id;
              $notification_title   = 'Update status notice of completion in Project: ' .$check_project_user->p_name;
              $url                  = App::make('url')->to('/');
              $link                 = "/dashboard/".$project_id."/notice_completion";
              $date                 = date("M d, Y h:i a");
              $email_description    = 'Update status notice of completion in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

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
      }
    }
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }

  /*
  --------------------------------------------------------------------------
   Get Notice Completion by passing noc_id
  --------------------------------------------------------------------------
  */
  public function get_notice_completion(Request $request, $noc_id)
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
        $query = DB::table('project_notice_of_completion')
        ->leftJoin('documents', 'project_notice_of_completion.noc_file_path', '=', 'documents.doc_id')
        ->leftJoin('projects', 'project_notice_of_completion.noc_project_id', '=', 'projects.p_id')
        ->leftJoin('users', 'project_notice_of_completion.noc_user_id', '=', 'users.id')
        ->select('project_notice_of_completion.*', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('noc_id', '=', $noc_id)
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
   Get all Notice Completion by passing Project ID
  ----------------------------------------------------------------------------------
  */
  public function get_notice_completion_project(Request $request, $project_id)
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
        $permission_key = 'agency_acceptance_view_all';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
          $query = DB::table('project_notice_of_completion')
          ->leftJoin('documents', 'project_notice_of_completion.noc_file_path', '=', 'documents.doc_id')
          ->leftJoin('projects', 'project_notice_of_completion.noc_project_id', '=', 'projects.p_id')
          ->leftJoin('users', 'project_notice_of_completion.noc_user_id', '=', 'users.id')
          ->select('project_notice_of_completion.*', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('noc_project_id', '=', $project_id)
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