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
use App\ProjectTestResult; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post; 
use App\Repositories\Util\AclPolicy;

 
class TestResultController extends Controller {

  /*
  --------------------------------------------------------------------------
   Add Test Result
  --------------------------------------------------------------------------
  */
  public function add_text_result(Request $request)
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
        $compaction_firm            = $request['compaction_firm'];
        $compaction_date            = $request['compaction_date'];
        $compaction_test_num        = $request['compaction_test_num'];
        $compaction_location        = $request['compaction_location'];
        $compaction_result          = $request['compaction_result'];
        $compaction_doc_id          = $request['compaction_doc_id'];
        $strength_firm              = $request['strength_firm'];
        $strength_date              = $request['strength_date'];
        $strength_test_num          = $request['strength_test_num'];
        $strength_location          = $request['strength_location'];
        $strength_result            = $request['strength_result'];
        $strength_doc_id            = $request['strength_doc_id'];
        $etc_test_name              = $request['etc_test_name'];
        $etc_firm                   = $request['etc_firm'];
        $etc_date                   = $request['etc_date'];
        $etc_test_num               = $request['etc_test_num'];
        $etc_location               = $request['etc_location'];
        $etc_result                 = $request['etc_result'];
        $etc_doc_id                 = $request['etc_doc_id'];
        $project_id                 = $request['project_id'];
        $user_id                    = Auth::user()->id;
        $status                     = 'active';
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'test_result_add';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            "project_id"            => $project_id,
            "user_id"               => $user_id,
            "status"                => $status
        );

        $rules = [
            'project_id'            => 'required|numeric',
            'user_id'               => 'required|numeric',
            'status'                => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_test_result')
            ->insert(['tr_compaction_firm' => $compaction_firm, 'tr_compaction_date' => $compaction_date, 'tr_compaction_test_num' => $compaction_test_num, 'tr_compaction_location' => $compaction_location, 'tr_compaction_result' => $compaction_result, 'tr_compaction_doc_id' => $compaction_doc_id, 'tr_strength_firm' => $strength_firm, 'tr_strength_date' => $strength_date, 'tr_strength_test_num' => $strength_test_num, 'tr_strength_location' => $strength_location, 'tr_strength_result' => $strength_result, 'tr_strength_doc_id' => $strength_doc_id, 'tr_etc_test_name' => $etc_test_name, 'tr_etc_firm' => $etc_firm, 'tr_etc_date' => $etc_date,  'tr_etc_test_num' => $etc_test_num,  'tr_etc_location' => $etc_location, 'tr_etc_result' => $etc_result, 'tr_etc_doc_id' => $etc_doc_id, 'tr_project_id' => $project_id, 'tr_user_id' => $user_id, 'tr_status' => $status]);

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
//                $user_id              = $check_project_user->id;
//                $permission_key       = 'test_result_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Add new test result in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/test_result";
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Add new test result in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
//
//                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
//                if(count($check_single_user_permission) < 1){
//                  continue;
//                }
//                else {
//                  // Send Notification to users
//                  $project_notification_query = app('App\Http\Controllers\Projects\NotificationController')->add_notification($notification_title, $link, $project_id, $check_single_user_permission[0]->pup_user_id);
//               
//                  $user_detail = array(
//                    'id'              => $check_project_user->id,
//                    'name'            => $check_project_user->username,
//                    'email'           => $check_project_user->email,
//                    'link'            => $link,
//                    'date'            => $date,
//                    'project_name'    => $check_project_user->p_name,
//                    'title'           => $notification_title,
//                    'description'     => $email_description
//                  );
//                  $user_single = (object) $user_detail;
//                  Mail::send('emails.send_notification',['user' => $user_single], function ($message) use ($user_single) {
//                      $message->from('no-reply@sw.ai', 'StratusCM');
//                      $message->to($user_single->email, $user_single->name)->subject($user_single->title);
//                  });
//                }

              } // End Foreach
              // End Check User Permission and send notification and email 

              $result = array('description'=>"Added text result successfully",'code'=>200);
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
   Update Test Result by passing tr_id
  --------------------------------------------------------------------------
  */
  public function update_text_result(Request $request, $tr_id)
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
        $project_id                     = $request['project_id'];
        $user_id                        = Auth::user()->id;
        $status                         = $request['status'];
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'test_result_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            "project_id"            => $project_id,
            "user_id"               => $user_id,
            "status"                => $status
        );

        $rules = [
            'project_id'            => 'required|numeric',
            'user_id'               => 'required|numeric',
            'status'                => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_test_result')
            ->where('tr_id', '=', $tr_id)
            ->update(['tr_project_id' => $project_id, 'tr_user_id' => $user_id, 'tr_status' => $status]);
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
//                $user_id              = $check_project_user->id;
//                $permission_key       = 'test_result_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Update status Test Result # '.$tr_id.' in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/test_result";
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Update status Test Result # '.$tr_id.' in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
//
//                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
//                if(count($check_single_user_permission) < 1){
//                  continue;
//                }
//                else {
//                  // Send Notification to users
//                  $project_notification_query = app('App\Http\Controllers\Projects\NotificationController')->add_notification($notification_title, $link, $project_id, $check_single_user_permission[0]->pup_user_id);
//               
//                  $user_detail = array(
//                    'id'              => $check_project_user->id,
//                    'name'            => $check_project_user->username,
//                    'email'           => $check_project_user->email,
//                    'link'            => $link,
//                    'date'            => $date,
//                    'project_name'    => $check_project_user->p_name,
//                    'title'           => $notification_title,
//                    'description'     => $email_description
//                  );
//                  $user_single = (object) $user_detail;
//                  Mail::send('emails.send_notification',['user' => $user_single], function ($message) use ($user_single) {
//                      $message->from('no-reply@sw.ai', 'StratusCM');
//                      $message->to($user_single->email, $user_single->name)->subject($user_single->title);
//                  });
//                }

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
   Get single Test Result by passing tr_id
  --------------------------------------------------------------------------
  */
  public function get_test_result_single(Request $request, $tr_id)
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
        $query = DB::table('project_test_result')
->leftJoin('project_firm as compaction_firm', 'project_test_result.tr_compaction_firm', '=', 'compaction_firm.f_id')
->leftJoin('documents as compaction_doc', 'project_test_result.tr_compaction_doc_id', '=', 'compaction_doc.doc_id')
->leftJoin('project_firm as strength_firm', 'project_test_result.tr_strength_firm', '=', 'strength_firm.f_id')
->leftJoin('documents as strength_doc', 'project_test_result.tr_strength_doc_id', '=', 'strength_doc.doc_id')
// ->leftJoin('project_firm as etc_firm', 'project_test_result.tr_etc_firm', '=', 'etc_firm.f_id')
->leftJoin('documents as etc_doc_id', 'project_test_result.tr_etc_doc_id', '=', 'etc_doc_id.doc_id')
->leftJoin('projects', 'project_test_result.tr_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_test_result.tr_user_id', '=', 'users.id')
        ->select('project_test_result.*',
          'compaction_firm.f_name as compaction_firm', 
          'compaction_doc.doc_path as compaction_doc',
          'strength_firm.f_name as strength_firm', 
          'strength_doc.doc_path as strength_doc',
          // 'etc_firm.f_name as etc_firm', 
          'etc_doc_id.doc_path as etc_doc_id',
          'projects.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('tr_id', '=', $tr_id)
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
   Get all test result by passing Project ID
  ----------------------------------------------------------------------------------
  */
  public function get_test_result_project(Request $request, $project_id)
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
        $permission_key = 'test_result_view_all';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
          $query = DB::table('project_test_result')
->leftJoin('project_firm as compaction_firm', 'project_test_result.tr_compaction_firm', '=', 'compaction_firm.f_id')
->leftJoin('documents as compaction_doc', 'project_test_result.tr_compaction_doc_id', '=', 'compaction_doc.doc_id')
->leftJoin('project_firm as strength_firm', 'project_test_result.tr_strength_firm', '=', 'strength_firm.f_id')
->leftJoin('documents as strength_doc', 'project_test_result.tr_strength_doc_id', '=', 'strength_doc.doc_id')
->leftJoin('project_firm as etc_firm', 'project_test_result.tr_etc_firm', '=', 'etc_firm.f_id')
->leftJoin('documents as etc_doc_id', 'project_test_result.tr_etc_doc_id', '=', 'etc_doc_id.doc_id')
->leftJoin('projects', 'project_test_result.tr_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_test_result.tr_user_id', '=', 'users.id')
        ->select('project_test_result.*',
          'compaction_firm.f_name as compaction_firm', 
          'compaction_doc.doc_path as compaction_doc',
          'strength_firm.f_name as strength_firm', 
          'strength_doc.doc_path as strength_doc',
          'etc_firm.f_name as etc_firm', 
          'etc_doc_id.doc_path as etc_doc_id',
          'projects.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('tr_project_id', '=', $project_id)
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