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
use App\ProjectSurvey; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post; 
use App\Repositories\Util\AclPolicy;


class SurveyController extends Controller {
  
   
  /*
  --------------------------------------------------------------------------
   ADD SURVEY
  --------------------------------------------------------------------------
  */
  public function add_survey(Request $request)
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
        $survey_number                  = $request['survey_number'];
        $survey_date                    = $request['survey_date'];
        $survey_description             = $request['survey_description'];
        $survey_request_completion_date = $request['survey_request_completion_date'];
        $survey_request_expedited       = $request['survey_request_expedited'];
        $survey_request_path            = $request['survey_request_path'];
        $project_id                     = $request['project_id'];
        $user_id                        = Auth::user()->id;
        $sur_req_status                 = 'active';
    // Check User Permission Parameter 
    $user_id = Auth::user()->id;
    $permission_key = 'survey_add';
    $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
    if(count($check_single_user_permission) < 1){
      $result = array('code'=>403, "description"=>"Access Denies");
      return response()->json($result, 403);
    }
    else {
        $information = array(
            "survey_date"                     => $survey_date,
            "survey_description"              => $survey_description,
            "survey_request_completion_date"  => $survey_request_completion_date,
            "survey_request_expedited"        => $survey_request_expedited,
            "project_id"                      => $project_id,
            "user_id"                         => $user_id,
            "sur_req_status"                  => $sur_req_status
        );

        $rules = [
            'survey_date'                     => 'required',
            'survey_description'              => 'required',
            'survey_request_completion_date'  => 'required',
            'survey_request_expedited'        => 'required',
            'project_id'                      => 'required|numeric',
            'user_id'                         => 'required|numeric',
            'sur_req_status'                  => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $survey = ProjectSurvey::create(['sur_number' => $survey_number, 'sur_date' => $survey_date, 'sur_description' => $survey_description, 'sur_request_completion_date' => $survey_request_completion_date, 'sur_request_expedited' =>$survey_request_expedited, 'sur_request_path' => $survey_request_path, 'sur_user_id' => $user_id, 'sur_project_id' => $project_id, 'sur_req_status' => $sur_req_status]);

            $survey_id = $survey->id;

            if(count($survey) < 1)
            {
              $result = array('code'=>404, "description"=>"No records found");
              return response()->json($result, 404);
            }
            else
            {
              $query = DB::table('project_survey_review')
                ->insert(['sr_survey_id' => $survey_id, 'sr_review_status' => 'pending', 'sr_responsible' => '', 'sr_project_id' => $project_id, 'sr_req_status' => $sur_req_status]);
                
                    if(count($query) < 1)
                    {
                      $result = array('code'=>404, "description"=>"No records found");
                      return response()->json($result, 404);
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
                        $permission_key       = 'survey_view_all';
                        // Notification Parameter
                        $project_id           = $project_id;
                        $notification_title   = 'New survey # '.$survey_number .' added in Project: ' .$check_project_user->p_name;
                        $url                  = App::make('url')->to('/');
                        $link                 = "/dashboard/".$project_id."/survey/".$survey->id;
                        $date                 = date("M d, Y h:i a");
                        $email_description    = 'A new survey # '.$survey_number .' has been added in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

                        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
                        if(count($check_single_user_permission) < 1){
                          continue;
                        }
                        else {
                            $notification_key     = 'survey_cut_sheet';
                            $check_project_user_notification = app('App\Http\Controllers\Projects\PermissionController')->check_project_user_notification($project_id,$user_id,$notification_key);
                            if(count($check_project_user_notification) < 1){
                              continue;
                            }else{
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
                      } // End Foreach
                      // End Check User Permission and send notification and email 

                      $result = array('description'=>"Add survey successfully",'code'=>200);
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
   Update Survey by passing sur_id
  --------------------------------------------------------------------------
  */
  public function update_survey(Request $request, $sur_id)
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
        // $survey_number                  = $request['survey_number'];
        // $survey_date                    = $request['survey_date'];
        // $survey_description             = $request['survey_description'];
        // $survey_request_completion_date = $request['survey_request_completion_date'];
        // $survey_request_expedited       = $request['survey_request_expedited'];
        // $survey_request_path            = $request['survey_request_path'];
        // $survey_cut_sheet               = $request['survey_cut_sheet'];
        // $survey_status                  = $request['survey_status'];
        $project_id                     = $request['project_id'];
        $user_id                        = Auth::user()->id;
        $sur_req_status                 = $request['sur_req_status'];
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'survey_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            // "survey_number"                   => $survey_number,
            // "survey_date"                     => $survey_date,
            // "survey_description"              => $survey_description,
            // "survey_request_completion_date"  => $survey_request_completion_date,
            // "survey_request_expedited"        => $survey_request_expedited,
            "project_id"                      => $project_id,
            "user_id"                         => $user_id,
            "sur_req_status"                  => $sur_req_status
        );

        $rules = [
            // 'survey_number'                   => 'required',
            // 'survey_date'                     => 'required',
            // 'survey_description'              => 'required',
            // 'survey_request_completion_date'  => 'required',
            // 'survey_request_expedited'        => 'required',
            'project_id'                      => 'required|numeric',
            'user_id'                         => 'required|numeric',
            'sur_req_status'                  => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_survey')
            ->where('sur_id', '=', $sur_id)
            // ->update(['sur_number' => $survey_number, 'sur_date' => $survey_date, 'sur_description' => $survey_description, 'sur_request_completion_date' => $survey_request_completion_date, 'sur_request_expedited' =>$survey_request_expedited, 'sur_request_path' => $survey_request_path, 'sur_cut_sheet' => $survey_cut_sheet, 'sur_status' => $survey_status, 'sur_user_id' => $user_id, 'sur_project_id' => $project_id, 'sur_req_status' => $sur_req_status]);
            ->update(['sur_user_id' => $user_id, 'sur_project_id' => $project_id, 'sur_req_status' => $sur_req_status]);
            if(count($query) < 1)
            {
              $result = array('code'=>404, "description"=>"No records found");
              return response()->json($result, 404);
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
                $permission_key       = 'survey_view_all';
                // Notification Parameter
                $project_id           = $project_id;
                $notification_title   = 'Survey # '.$sur_id .' updated in Project: ' .$check_project_user->p_name;
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/survey/".$sur_id;
                $date                 = date("M d, Y h:i a");
                $email_description    = 'Survey # '.$sur_id .' has been updated in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
                if(count($check_single_user_permission) < 1){
                  continue;
                }
                else {
                    $notification_key     = 'survey_cut_sheet';
                    $check_project_user_notification = app('App\Http\Controllers\Projects\PermissionController')->check_project_user_notification($project_id,$user_id,$notification_key);
                    if(count($check_project_user_notification) < 1){
                      continue;
                    }else{
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
   Get single Survey by passing sur_id
  --------------------------------------------------------------------------
  */
  public function get_survey_single(Request $request, $sur_id)
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
        $query = DB::table('project_survey')
->leftJoin('projects', 'project_survey.sur_project_id', '=', 'projects.p_id')
->leftJoin('project_survey_review', 'project_survey.sur_id', '=', 'project_survey_review.sr_survey_id')
->leftJoin('users', 'project_survey.sur_user_id', '=', 'users.id')
->leftJoin('users as review_user', 'project_survey_review.sr_user_id', '=', 'review_user.id')
->leftJoin('documents', 'project_survey.sur_request_path', '=', 'documents.doc_id')
->leftJoin('documents as review_file_path', 'project_survey_review.sr_file_path', '=', 'review_file_path.doc_id')
        ->select('documents.doc_path as sr_file_path', 'review_file_path.doc_path as review_file_path', 'project_survey.*', 'project_survey_review.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role', 'review_user.username as review_user_name', 'review_user.email as review_user_email', 'review_user.first_name as review_user_firstname', 'review_user.last_name as review_user_lastname', 'review_user.company_name as review_user_company', 'review_user.phone_number as review_user_phonenumber', 'review_user.status as review_user_status', 'review_user.role as review_user_role')
        ->where('sur_id', '=', $sur_id)
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
   Get all Survey by passing survey_id
  ----------------------------------------------------------------------------------
  */
  public function get_survey_project(Request $request, $survey_id)
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
      $permission_key = 'survey_view_all';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($survey_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
          $query = DB::table('project_survey')
->leftJoin('documents', 'project_survey.sur_request_path', '=', 'documents.doc_id')
->leftJoin('project_survey_review', 'project_survey.sur_id', '=', 'project_survey_review.sr_survey_id')                  
->leftJoin('documents as survey_rew_path', 'project_survey_review.sr_file_path', '=', 'survey_rew_path.doc_id')                  
->leftJoin('project_survey_review', 'project_survey.sur_id', '=', 'project_survey_review.sr_survey_id')
->leftJoin('projects', 'project_survey.sur_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_survey.sur_user_id', '=', 'users.id')
        ->select('documents.doc_path as file_path', 'project_survey.*','survey_rew_path.doc_path as survey_rew_path', 'project_survey_review.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('sur_project_id', '=', $survey_id)
          ->orderBy('sur_number')
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

  /*
  --------------------------------------------------------------------------
   ADD SURVEY REVIEW
  --------------------------------------------------------------------------
  */
  public function add_survey_review(Request $request)
  {
    try
    {
      // $user = array(
      //   'id'        => Auth::user()->id,
      //   'role'      => Auth::user()->role
      // );
      // $user = (object) $user;
      // $post = new Resource_Post(); // You create a new resource Post instance
      // if (Gate::forUser($user)->denies('allow_admin_contractor_user', [$post,false])) { 
      //   $result = array('code'=>403, "description"=>"Access denies");
      //   return response()->json($result, 403);
      // } 
      // else { 
        $review_responsible             = $request['review_responsible'];
        $review_request                 = $request['review_request'];
        $review_date                    = $request['review_date'];
        $review_file_path               = $request['review_file_path'];
        $review_survey_id               = $request['review_survey_id'];
        $project_id                     = $request['project_id'];
        $user_id                        = Auth::user()->id;
        $review_req_status              = 'active';
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'survey_review_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            "review_responsible"        => $review_responsible,
            "review_request"            => $review_request,
            "review_date"               => $review_date,
            "project_id"                => $project_id,
            "user_id"                   => $user_id,
            "review_req_status"         => $review_req_status
        );

        $rules = [
            'review_responsible'        => 'required',
            'review_request'            => 'required',
            'review_date'               => 'required',
            'project_id'                => 'required|numeric',
            'user_id'                   => 'required|numeric',
            'review_req_status'         => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_survey_review')
            ->insert(['sr_responsible' => $review_responsible, 'sr_request' => $review_request, 'sr_date' => $review_date, 'sr_file_path' => $review_file_path, 'sr_survey_id' =>$review_survey_id, 'sr_user_id' => $user_id, 'sr_project_id' => $project_id, 'sr_req_status' => $review_req_status]);

            if(count($query) < 1)
            {
              $result = array('code'=>404, "description"=>"No records found");
              return response()->json($result, 404);
            }
            else
            {
              $result = array('description'=>"Add survey review successfully",'code'=>200);
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
   Update Survey Review by passing sr_id
  --------------------------------------------------------------------------
  */
  public function update_survey_review(Request $request, $sr_id)
  {
    try
    {
      // $user = array(
      //   'userid'    => Auth::user()->id,
      //   'role'      => Auth::user()->role
      // );
      // $user = (object) $user;
      // $post = new Resource_Post(); // You create a new resource Post instance
      // if (Gate::forUser($user)->denies('allow_admin_contractor_user', [$post,false])) { 
      //   $result = array('code'=>403, "description"=>"Access denies");
      //   return response()->json($result, 403);
      // } 
      // else {
        $survey_number                  = $request['survey_number'];
        $review_responsible             = $request['review_responsible'];
        $review_request                 = $request['review_request'];
        $review_date                    = $request['review_date'];
        $review_file_path               = $request['review_file_path'];
        $project_id                     = $request['project_id'];
        $user_id                        = Auth::user()->id;
        $sr_review_status               = 'completed';
        $status                         = 'active';

      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'survey_review_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {

        $information = array(
            "review_responsible"        => $review_responsible,
            "review_file_path"          => $review_file_path,
            "project_id"                => $project_id,
            "user_id"                   => $user_id,
            "status"                    => $status
        );

        $rules = [
            'review_responsible'        => 'required',
            'review_file_path'          => 'required|numeric',
            'project_id'                => 'required|numeric',
            'user_id'                   => 'required|numeric',
            'status'                    => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_survey_review')
            ->where('sr_survey_id', '=', $sr_id)
            ->update(['sr_responsible' => $review_responsible, 'sr_request' => $review_request, 'sr_date' => $review_date, 'sr_file_path' => $review_file_path, 'sr_review_status' => $sr_review_status, 'sr_user_id' => $user_id, 'sr_project_id' => $project_id, 'sr_req_status' => $status]);
            if(count($query) < 1)
            {
              $result = array('code'=>404, "description"=>"No records found");
              return response()->json($result, 404);
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
//                $permission_key       = 'survey_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Received review survey # '.$survey_number .' in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/survey/".$sr_id;
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Received review survey # '.$survey_number .' in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
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
   Get single Survey Review by passing sr_id
  --------------------------------------------------------------------------
  */
  public function get_survey_review_single(Request $request, $sr_id)
  {
    try
    {
      // $user = array(
      //   'userid'    => Auth::user()->id,
      //   'role'      => Auth::user()->role
      // );
      // $user = (object) $user;
      // $post = new Resource_Post(); // You create a new resource Post instance
      // if (Gate::forUser($user)->denies('allow_admin_contractor_user', [$post,false])) { 
      //   $result = array('code'=>403, "description"=>"Access denies");
      //   return response()->json($result, 403);
      // } 
      // else {
        $query = DB::table('project_survey_review')
->leftJoin('project_survey', 'project_survey_review.sr_survey_id', '=', 'project_survey.sur_id')
->leftJoin('documents', 'project_survey_review.sr_file_path', '=', 'documents.doc_id')
->leftJoin('documents as sur_document', 'project_survey.sur_request_path', '=', 'sur_document.doc_id')
->leftJoin('projects', 'project_survey_review.sr_project_id', '=', 'projects.p_id')
->leftJoin('users as review_user', 'project_survey_review.sr_user_id', '=', 'review_user.id')
->leftJoin('users', 'project_survey.sur_user_id', '=', 'users.id')
        ->select('documents.doc_path as review_file_path', 'sur_document.doc_path as survey_file_path', 'project_survey_review.*', 'project_survey.*', 'projects.*', 'review_user.username as review_user_name', 'review_user.email as review_user_email', 'review_user.first_name as review_user_firstname', 'review_user.last_name as review_user_lastname', 'review_user.company_name as review_user_company', 'review_user.phone_number as review_user_phonenumber', 'review_user.status as review_user_status', 'review_user.role as review_user_role',
          'users.username as sur_user_name', 'users.email as sur_user_email', 'users.first_name as sur_user_firstname', 'users.last_name as sur_user_lastname', 'users.company_name as sur_user_company', 'users.phone_number as sur_user_phonenumber', 'users.status as sur_user_status', 'users.role as sur_user_role')
        ->where('sr_survey_id', '=', $sr_id)
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
   Get all Survey Review by passing project_id
  ----------------------------------------------------------------------------------
  */
  public function get_survey_review_project(Request $request, $project_id)
  {
      try
      {
        // $user = array(
        //   'userid'    => Auth::user()->id,
        //   'role'      => Auth::user()->role
        // );
        // $user = (object) $user;
        // $post = new Resource_Post(); // You create a new resource Post instance
        // if (Gate::forUser($user)->denies('allow_admin_contractor_user', [$post,false])) { 
        //   $result = array('code'=>403, "description"=>"Access denies");
        //   return response()->json($result, 403);
        // } 
        // else {
        // Check User Permission Parameter 
        $user_id = Auth::user()->id;
        $permission_key = 'survey_review_view_all';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
          $query = DB::table('project_survey_review')
->leftJoin('project_survey', 'project_survey_review.sr_survey_id', '=', 'project_survey.sur_id')
->leftJoin('documents', 'project_survey_review.sr_file_path', '=', 'documents.doc_id')
->leftJoin('projects', 'project_survey_review.sr_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_survey_review.sr_user_id', '=', 'users.id')
        ->select('documents.doc_path as review_file_path', 'project_survey_review.*', 'project_survey.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('sr_project_id', '=', $project_id)
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

  /*
  ----------------------------------------------------------------------------------
   Get all Survey Log by passing project_id
  ----------------------------------------------------------------------------------
  */
  public function get_survey_log(Request $request, $project_id)
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
        $permission_key = 'survey_log';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
          $query = DB::table('project_survey')
->leftJoin('project_survey_review', 'project_survey.sur_id', '=', 'project_survey_review.sr_survey_id')
->leftJoin('documents as survey_req_path', 'project_survey.sur_request_path', '=', 'survey_req_path.doc_id')
->leftJoin('documents as survey_rew_path', 'project_survey_review.sr_file_path', '=', 'survey_rew_path.doc_id')
->leftJoin('projects', 'project_survey.sur_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_survey.sur_user_id', '=', 'users.id')
        ->select('survey_req_path.doc_path as survey_req_path', 'project_survey.*', 'survey_rew_path.doc_path as survey_rew_path', 'project_survey_review.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('sr_project_id', '=', $project_id)
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


  /*
  ----------------------------------------------------------------------------------
   Get New Survey Number
  ----------------------------------------------------------------------------------
  */
  public function get_new_survey_number(Request $request, $project_id)
  {
      // $query = DB::table('project_survey')
      // ->select()
      // ->where('sur_project_id', '=', $project_id)
      // ->orderBy('sur_id', 'desc')
      // ->first();

      $query = DB::table('project_survey')
      ->select()
      ->where('sur_project_id', '=', $project_id)
      ->orderBy('sur_id', 'desc')
      ->count();

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


   /*
  --------------------------------------------------------------------------
   Get All Pending review and expire if X day value cross
  --------------------------------------------------------------------------
  */
  public function check_survey_review_response(Request $request)
  {
      try
      {
        $days = config('app.survey_review_status_change');
        $query = DB::table('project_survey_review')
        ->select()
        ->where('sr_review_status', '=', 'pending')
        ->get();
        $user =  (array) $query;
        if(count($query) < 1)
        {
          $result = array('code'=>404,"description"=>"No Records Found");
          return response()->json($result, 404);
        }
        else
        {
          foreach ($query as $key => $review) {
            $reg_time = $review->sr_timestamp;
            $reg_date = strtotime($reg_time);
            $date = date_create($reg_time);
            date_add($date, date_interval_create_from_date_string($days.'days'));
            $plus_time = date_format($date, 'Y-m-d H:i:s');
            $plus_date = strtotime($plus_time);
            $current_date = time();
            // echo $plus_date .' - '. $current_date;
            // echo '<br/>';
              if($current_date >= $plus_date){
                $review = DB::table('project_survey_review')
                ->where('sr_survey_id', '=', $review->sr_survey_id)
                ->where('sr_review_status', '=', 'pending')
                ->update(['sr_review_status' => 'past_due']);
                $result = array('description'=>'Update Status successfully','code'=>200);
                return response()->json($result, 200);
              }
              else {
                $result = array('code'=>404, "description"=>"No Records Found");
                return response()->json($result, 404);
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