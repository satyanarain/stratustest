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
use App\ProjectRequestInfo; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post; 
use App\Repositories\Util\AclPolicy;


class RequestInfoController extends Controller {
  
  /*
  --------------------------------------------------------------------------
   ADD REQUEST INFORMATION
  --------------------------------------------------------------------------
  */
  public function add_request_information(Request $request)
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
        $request_number             = $request['request_number'];
        $request_date               = $request['request_date'];
        $question_request           = $request['question_request'];
        $question_proposed          = $request['question_proposed'];
        $additional_cost            = $request['additional_cost'];
        // $additional_cost_currency   = $request['additional_cost_currency'];
        $additional_cost_amount     = $request['additional_cost_amount'];
        $additional_day             = $request['additional_day'];
        $additional_day_add         = $request['additional_day_add'];
        $file_path                  = $request['file_path'];
        $project_id                 = $request['project_id'];
        $user_id                    = Auth::user()->id;
        $request_status             = 'active';
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'rfi_add';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            "request_date"          => $request_date,
            "question_request"      => $question_request,
            "question_proposed"     => $question_proposed,
            "additional_cost"       => $additional_cost,
            "additional_day"        => $additional_day,
            "project_id"            => $project_id,
            "user_id"               => $user_id,
            "request_status"        => $request_status
        );

        $rules = [
            'request_date'          => 'required',
            'question_request'      => 'required',
            'question_proposed'     => 'required',
            'additional_cost'       => 'required',
            'additional_day'        => 'required',
            'project_id'            => 'required|numeric',
            'user_id'               => 'required|numeric',
            'request_status'        => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $request_info = ProjectRequestInfo::create(['ri_number' => $request_number, 'ri_date' => $request_date, 'ri_question_request' => $question_request, 'ri_question_proposed' => $question_proposed, 'ri_additional_cost' =>$additional_cost, 'ri_additional_cost_amount' => $additional_cost_amount, 'ri_additional_day' => $additional_day, 'ri_additional_day_add' => $additional_day_add, 'ri_file_path' => $file_path, 'ri_user_id' => $user_id, 'ri_project_id' => $project_id, 'ri_request_status' => $request_status]);

            $request_info_id = $request_info->id;
            // $query = DB::table('project_request_info')
            // ->insert(['ri_number' => $request_number, 'ri_date' => $request_date, 'ri_question_request' => $question_request, 'ri_question_proposed' => $question_proposed, 'ri_additional_cost' =>$additional_cost, 'ri_additional_cost_currency' => $additional_cost_currency, 'ri_additional_cost_amount' => $additional_cost_amount, 'ri_additional_day' => $additional_day, 'ri_additional_day_add' => $additional_day_add, 'ri_file_path' => $file_path, 'ri_user_id' => $user_id, 'ri_project_id' => $project_id, 'ri_request_status' => $request_status]);

              if(count($request_info) < 1)
              {
                $result = array('code'=>404, "description"=>"No records found");
                return response()->json($result, 404);
              }
              else
              {
                $query = DB::table('project_request_info_review')
                ->insert(['rir_review_parent' => $request_info_id, 'rir_review_status' => 'response_due', 'rir_project_id' => $project_id, 'rir_status' => $request_status]);
                
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
                      $permission_key       = 'rfi_view_all';
                      // Notification Parameter
                      $project_id           = $project_id;
                      //$notification_title   = 'Add new request information # '.$request_number .' in Project: ' .$check_project_user->p_name;
                      $notification_title   = 'Request for information # '.$request_number .' received in Project: ' .$check_project_user->p_name;
                      $url                  = App::make('url')->to('/');
                      $link                 = "/dashboard/".$project_id."/req_for_info/".$request_info->id;
                      $date                 = date("M d, Y h:i a");
                      $email_description    = 'A new request for information has been added in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

                      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
                      if(count($check_single_user_permission) < 1){
                        continue;
                      }
                      else {
                        // Send Notification to users
                        $notification_key     = 'rfi';
                        $check_project_user_notification = app('App\Http\Controllers\Projects\PermissionController')->check_project_user_notification($project_id,$user_id,$notification_key);
                        if(count($check_project_user_notification) < 1){
                          continue;
                        }else{
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

                      $result = array('description'=>"RFI uploaded successfully",'code'=>200);
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
   Update Request Information by passing ri_id
  --------------------------------------------------------------------------
  */
  public function update_request_information(Request $request, $ri_id)
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
        // $request_number             = $request['request_number'];
        // $request_date               = $request['request_date'];
        // $question_request           = $request['question_request'];
        // $question_proposed          = $request['question_proposed'];
        // $additional_cost            = $request['additional_cost'];
        // $additional_cost_currency   = $request['additional_cost_currency'];
        // $additional_cost_amount     = $request['additional_cost_amount'];
        // $additional_day             = $request['additional_day'];
        // $additional_day_add         = $request['additional_day_add'];
        // $file_path                  = $request['file_path'];
        $project_id                 = $request['project_id'];
        $user_id                    = Auth::user()->id;
        $request_status             = $request['request_status'];
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'rfi_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            // "request_number"        => $request_number,
            // "request_date"          => $request_date,
            // "question_request"      => $question_request,
            // "question_proposed"     => $question_proposed,
            // "additional_cost"       => $additional_cost,
            // "additional_day"        => $additional_day,
            "project_id"            => $project_id,
            "user_id"               => $user_id,
            "request_status"        => $request_status
        );

        $rules = [
            // 'request_number'        => 'required',
            // 'request_date'          => 'required',
            // 'question_request'      => 'required',
            // 'question_proposed'     => 'required',
            // 'additional_cost'       => 'required',
            // 'additional_day'        => 'required',
            'project_id'            => 'required|numeric',
            'user_id'               => 'required|numeric',
            'request_status'        => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_request_info')
            ->where('ri_id', '=', $ri_id)
            // ->update(['ri_number' => $request_number, 'ri_date' => $request_date, 'ri_question_request' => $question_request, 'ri_question_proposed' => $question_proposed, 'ri_additional_cost' =>$additional_cost, 'ri_additional_cost_currency' => $additional_cost_currency, 'ri_additional_cost_amount' => $additional_cost_amount, 'ri_additional_day' => $additional_day, 'ri_additional_day_add' => $additional_day_add, 'ri_file_path' => $file_path, 'ri_user_id' => $user_id, 'ri_project_id' => $project_id, 'ri_request_status' => $request_status]);
            ->update(['ri_user_id' => $user_id, 'ri_project_id' => $project_id, 'ri_request_status' => $request_status]);
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
                $permission_key       = 'rfi_view_all';
                // Notification Parameter
                $project_id           = $project_id;
                $notification_title   = 'Request for information # '.$ri_id .' updated in Project: ' .$check_project_user->p_name;
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/req_for_info/".$ri_id;
                $date                 = date("M d, Y h:i a");
                $email_description    = 'A request for information # '.$ri_id .' has been updated in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
                if(count($check_single_user_permission) < 1){
                  continue;
                }
                else {
                    $notification_key     = 'rfi';
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
   Get single Request Information by passing ri_id
  --------------------------------------------------------------------------
  */
  public function get_request_information_single(Request $request, $ri_id)
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
        $query = DB::table('project_request_info')
->leftJoin('currency', 'project_request_info.ri_additional_cost_currency', '=', 'currency.cur_id')
->leftJoin('documents as file_path', 'project_request_info.ri_file_path', '=', 'file_path.doc_id')
->leftJoin('projects', 'project_request_info.ri_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_request_info.ri_user_id', '=', 'users.id')
->leftJoin('project_request_info_review', 'project_request_info.ri_id', '=', 'project_request_info_review.rir_review_parent')
->leftJoin('users as review_user', 'project_request_info_review.rir_user_id', '=', 'review_user.id')
        ->select('currency.cur_symbol as currency_symbol',
          'file_path.doc_path as file_path',  
          'project_request_info.*', 'project_request_info_review.*', 'projects.*', 
          'users.username as rfi_user_name', 'users.email as rfi_user_email', 'users.first_name as rfi_user_firstname', 'users.last_name as rfi_user_lastname', 'users.company_name as rfi_user_company', 'users.phone_number as rfi_user_phonenumber', 'users.status as rfi_user_status', 'users.role as rfi_user_role', 
          'review_user.username as review_user_name', 'review_user.email as review_user_email', 'review_user.first_name as review_user_firstname', 'review_user.last_name as review_user_lastname', 'review_user.company_name as review_user_company', 'review_user.phone_number as review_user_phonenumber', 'review_user.status as review_user_status', 'review_user.role as review_user_role')
        ->where('ri_id', '=', $ri_id)
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
   Get all Request Information by passing Project ID
  ----------------------------------------------------------------------------------
  */
  public function get_request_information_project(Request $request, $project_id)
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
      $permission_key = 'rfi_view_all';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
          $query = DB::table('project_request_info')
->leftJoin('currency', 'project_request_info.ri_additional_cost_currency', '=', 'currency.cur_id')
->leftJoin('documents as file_path', 'project_request_info.ri_file_path', '=', 'file_path.doc_id')
->leftJoin('projects', 'project_request_info.ri_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_request_info.ri_user_id', '=', 'users.id')
->leftJoin('project_request_info_review', 'project_request_info.ri_id', '=', 'project_request_info_review.rir_review_parent')
        ->select('currency.cur_symbol as currency_symbol',
          'file_path.doc_path as file_path',  
          'project_request_info.*', 'project_request_info_review.*', 'projects.*',
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('ri_project_id', '=', $project_id)
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
   ADD REQUEST REVIEW
  --------------------------------------------------------------------------
  */
  public function add_request_review(Request $request)
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
        $review_parent                      = $request['review_parent'];
        $review_respond                     = $request['review_respond'];
        $additional_info                    = $request['additional_info'];
        $additional_cost                    = $request['additional_cost'];
        $additional_cost_currency           = $request['additional_cost_currency'];
        $additional_cost_amount             = $request['additional_cost_amount'];
        $additional_day                     = $request['additional_day'];
        $additional_day_add                 = $request['additional_day_add'];
        $project_id                         = $request['project_id'];
        $user_id                            = Auth::user()->id;
        $status                             = 'active';
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'rfi_review_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            "review_parent"                 => $review_parent,
            "review_respond"                => $review_respond,
            "additional_cost"               => $additional_cost,
            "additional_day"                => $additional_day,
            "project_id"                    => $project_id,
            "user_id"                       => $user_id,
            "status"                        => $status
        );

        $rules = [
            'review_parent'                 => 'required|numeric',
            'review_respond'                => 'required',
            'additional_cost'               => 'required',
            'additional_day'                => 'required',
            'project_id'                    => 'required|numeric',
            'user_id'                       => 'required|numeric',
            'status'                        => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_request_info_review')
            ->insert(['rir_review_parent' => $review_parent, 'rir_review_respond' => $review_respond, 'rir_additional_info' => $additional_info, 'ri_additional_cost' => $additional_cost, 'ri_additional_cost_currency' => $additional_cost_currency, 'ri_additional_cost_amount' => $additional_cost_amount, 'ri_additional_day' => $additional_day, 'ri_additional_day_add' => $additional_day_add, 'rir_project_id' => $project_id, 'rir_user_id' => $user_id, 'rir_status' => $status]);

            if(count($query) < 1)
            {
              $result = array('code'=>404, "description"=>"No records found");
              return response()->json($result, 404);
            }
            else
            {
              $result = array('description'=>"Add request review successfully",'code'=>200);
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
   Update Request Review by passing rir_id
  --------------------------------------------------------------------------
  */
  public function update_request_review(Request $request, $rir_id)
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

        $rfi_number                         = $request['rfi_number'];
        $review_respond                     = $request['review_respond'];
        $additional_info                    = $request['additional_info'];
        $additional_cost                    = $request['additional_cost'];
        // $additional_cost_currency           = $request['additional_cost_currency'];
        $additional_cost_amount             = $request['additional_cost_amount'];
        $additional_day                     = $request['additional_day'];
        $additional_day_add                 = $request['additional_day_add'];
        $project_id                         = $request['project_id'];
        $user_id                            = Auth::user()->id;
        $status                             = $request['status'];
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'rfi_review_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            "review_respond"                => $review_respond,
            "additional_info"               => $additional_info,
            "additional_cost"               => $additional_cost,
            "additional_day"                => $additional_day,
            "additional_cost_amount"        => $additional_cost_amount,
            "additional_day_add"            => $additional_day_add,
            "project_id"                    => $project_id,
            "user_id"                       => $user_id,
            "status"                        => $status
        );

        if($additional_cost == 'yes'){
          $rules = [
            'review_respond'                => 'required',
            'additional_info'               => 'required',
            'additional_cost'               => 'required',
            'additional_cost_amount'        => 'required',
            'additional_day'                => 'required',
            'project_id'                    => 'required|numeric',
            'user_id'                       => 'required|numeric',
            'status'                        => 'required'
          ];
        }
        else if($additional_day == 'yes'){
          $rules = [
            'review_respond'                => 'required',
            'additional_info'               => 'required',
            'additional_cost'               => 'required',
            'additional_day'                => 'required',
            'additional_day_add'            => 'required',
            'project_id'                    => 'required|numeric',
            'user_id'                       => 'required|numeric',
            'status'                        => 'required'
          ];
        }
        // else if($additional_cost == 'yes' && $additional_day == 'yes'){
        //   $rules = [
        //     'review_respond'                => 'required',
        //     'additional_info'               => 'required',
        //     'additional_cost'               => 'required',
        //     'additional_cost_amount'        => 'required',
        //     'additional_day'                => 'required',
        //     'additional_day_add'            => 'required',
        //     'project_id'                    => 'required|numeric',
        //     'user_id'                       => 'required|numeric',
        //     'status'                        => 'required'
        //   ];
        // }
        else {
          $rules = [
              'review_respond'                => 'required',
              'additional_info'               => 'required',
              'additional_cost'               => 'required',
              'additional_day'                => 'required',
              'project_id'                    => 'required|numeric',
              'user_id'                       => 'required|numeric',
              'status'                        => 'required'
          ];
        }

        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_request_info_review')
            ->where('rir_review_parent', '=', $rir_id)
            ->update(['rir_review_respond' => $review_respond, 'rir_additional_info' => $additional_info, 'rir_additional_cost' => $additional_cost, 'rir_additional_cost_amount' => $additional_cost_amount, 'rir_additional_day' => $additional_day, 'rir_additional_day_add' => $additional_day_add, 'rir_project_id' => $project_id, 'rir_user_id' => $user_id, 'rir_review_status' => $status]);
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
//                $permission_key       = 'rfi_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Received review request information # '.$rfi_number .' in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/req_for_info/".$rir_id;
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Received review request information # '.$rfi_number .' in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
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
   Get single Request Review by passing rir_id
  --------------------------------------------------------------------------
  */
  public function get_request_review_single(Request $request, $rir_id)
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
        $query = DB::table('project_request_info_review')
->leftJoin('project_request_info', 'project_request_info_review.rir_review_parent', '=', 'project_request_info.ri_id')
->leftJoin('currency', 'project_request_info_review.ri_additional_cost_currency', '=', 'currency.cur_id')
->leftJoin('projects', 'project_request_info_review.rir_project_id', '=', 'projects.p_id')
->leftJoin('users as review_user', 'project_request_info_review.rir_user_id', '=', 'review_user.id')
->leftJoin('users', 'project_request_info.ri_user_id', '=', 'users.id')
        ->select('currency.cur_symbol as currency_symbol',  
          'project_request_info.*', 'project_request_info_review.*', 'projects.*', 
          'users.username as rfi_user_name', 'users.email as rfi_user_email', 'users.first_name as rfi_user_firstname', 'users.last_name as rfi_user_lastname', 'users.company_name as rfi_user_company', 'users.phone_number as rfi_user_phonenumber', 'users.status as rfi_user_status', 'users.role as rfi_user_role', 
          'review_user.username as review_user_name', 'review_user.email as review_user_email', 'review_user.first_name as review_user_firstname', 'review_user.last_name as review_user_lastname', 'review_user.company_name as review_user_company', 'review_user.phone_number as review_user_phonenumber', 'review_user.status as review_user_status', 'review_user.role as review_user_role')
        ->where('rir_review_parent', '=', $rir_id)
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
   Get all Request Review by passing Request ID
  ----------------------------------------------------------------------------------
  */
  public function get_request_review_project(Request $request, $request_id)
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
      $permission_key = 'rfi_review_view_all';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($request_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
          $query = DB::table('project_request_info_review')
->leftJoin('project_request_info', 'project_request_info_review.rir_review_parent', '=', 'project_request_info.ri_id')
->leftJoin('currency', 'project_request_info_review.rir_additional_cost_currency', '=', 'currency.cur_id')
->leftJoin('projects', 'project_request_info_review.rir_project_id', '=', 'projects.p_id')
->leftJoin('users as review_user', 'project_request_info_review.rir_user_id', '=', 'review_user.id')
->leftJoin('project_firm', 'review_user.company_name', '=', 'project_firm.f_id')
->leftJoin('users', 'project_request_info.ri_user_id', '=', 'users.id')
        ->select('currency.cur_symbol as currency_symbol',  
          'project_request_info.*', 'project_request_info_review.*', 'projects.*', 
          'users.username as rfi_user_name', 'users.email as rfi_user_email', 'users.first_name as rfi_user_firstname', 'users.last_name as rfi_user_lastname', 'users.company_name as rfi_user_company', 'users.phone_number as rfi_user_phonenumber', 'users.status as rfi_user_status', 'users.role as rfi_user_role', 'project_firm.f_name as company_name',
          'review_user.username as review_user_name', 'review_user.email as review_user_email', 'review_user.first_name as review_user_firstname', 'review_user.last_name as review_user_lastname', 'review_user.company_name as review_user_company', 'review_user.phone_number as review_user_phonenumber', 'review_user.status as review_user_status', 'review_user.role as review_user_role')
          ->where('rir_project_id', '=', $request_id)
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
   Get Request Log by passing Project ID
  ----------------------------------------------------------------------------------
  */
  public function get_request_information_log(Request $request, $project_id)
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
      $permission_key = 'rfi_log';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
          $query = DB::table('project_request_info')
->leftJoin('project_request_info_review', 'project_request_info.ri_id', '=', 'project_request_info_review.rir_review_parent')
->leftJoin('documents as file_path', 'project_request_info.ri_file_path', '=', 'file_path.doc_id')
->leftJoin('users as rfi_user', 'project_request_info.ri_user_id', '=', 'rfi_user.id')
->leftJoin('project_firm as rfi_firm_name', 'rfi_user.company_name', '=', 'rfi_firm_name.f_id')
->leftJoin('users as review_user', 'project_request_info_review.rir_user_id', '=', 'review_user.id')
->leftJoin('project_firm as review_firm_name', 'review_user.company_name', '=', 'review_firm_name.f_id')
        ->select(
          'project_request_info.*', 'project_request_info_review.*',
        'file_path.doc_path as file_path',
          'rfi_user.username as rfi_user_name', 'rfi_user.email as rfi_user_email', 'rfi_user.first_name as rfi_user_firstname', 'rfi_user.last_name as rfi_user_lastname', 'rfi_firm_name.f_name as rfi_user_company', 'rfi_user.phone_number as rfi_user_phonenumber', 'rfi_user.status as rfi_user_status', 'rfi_user.role as rfi_user_role', 
          'review_user.username as review_user_name', 'review_user.email as review_user_email', 'review_user.first_name as review_user_firstname', 'review_user.last_name as review_user_lastname', 'review_firm_name.f_name as review_user_company', 'review_user.phone_number as review_user_phonenumber', 'review_user.status as review_user_status', 'review_user.role as review_user_role')
          ->where('ri_project_id', '=', $project_id)
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
   Get New Request Number
  ----------------------------------------------------------------------------------
  */
  public function get_new_request_number(Request $request, $project_id)
  {
      $query = DB::table('project_request_info')
      ->select()
      ->where('ri_project_id', '=', $project_id)
      ->orderBy('ri_id', 'desc')
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
   Get All Unverified User and expire if X day value cross
  --------------------------------------------------------------------------
  */
  public function check_request_review_response(Request $request)
  {
      try
      {
        $days = config('app.request_review_status_change');
        $query = DB::table('project_request_info_review')
        ->select()
        ->where('rir_review_status', '=', 'response_due')
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
            $reg_time = $review->rir_timestamp;
            $reg_date = strtotime($reg_time);
            $date = date_create($reg_time);
            date_add($date, date_interval_create_from_date_string($days.'days'));
            $plus_time = date_format($date, 'Y-m-d H:i:s');
            $plus_date = strtotime($plus_time);
            $current_date = time();
              if($current_date >= $plus_date){
                $review = DB::table('project_request_info_review')
                ->where('rir_id', '=', $review->rir_id)
                ->where('rir_review_respond', '=', null)
                ->update(['rir_review_status' => 'past_due']);
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