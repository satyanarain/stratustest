<?php 
namespace App\Http\Controllers\Projects;

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
use Illuminate\Support\Facades\Input;
use App;
use Session;

use Gate;
use App\User; //your model
use App\Document; //your model
use App\ProjectSubmittals; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post; 
use App\Repositories\Util\AclPolicy;
 

class SubmittalsController extends Controller {
  
  /*
  --------------------------------------------------------------------------
   ADD SUBMITTAL
  --------------------------------------------------------------------------
  */
  public function add_submittal(Request $request)
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
        $submittal_type                     = $request['submittal_type'];
        $submittal_number                   = $request['submittal_number'];
        $submittal_exist_parent             = $request['submittal_exist_parent'];
        $submittal_rev_number               = $request['submittal_rev_number'];
        // $sub_submittal_type                 = $request['sub_submittal_type'];
        $submittal_date                     = $request['submittal_date'];
        $submittal_description              = $request['submittal_description'];
        $submittal_specification            = $request['submittal_specification'];
        $submittal_additional_comments      = $request['submittal_additional_comments'];
        $submittal_request_expedited_review = $request['submittal_request_expedited_review'];
        $submittal_additional_path          = $request['submittal_additional_path'];
        $submittal_review_type              = $request['submittal_review_type'];
        $project_id                         = $request['project_id'];
        $user_id                            = Auth::user()->id;
        $status                             = 'active';
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'submittal_add';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {

        $information = array(
            "submittal_type"            => $submittal_type,
            "submittal_number"          => $submittal_number,
            // "sub_submittal_type"        => $sub_submittal_type,
            "submittal_date"            => $submittal_date,
            "submittal_description"     => $submittal_description,
            "submittal_specification"   => $submittal_specification,
            "submittal_additional_path" => $submittal_additional_path,
            "project_id"                => $project_id,
            "user_id"                   => $user_id,
            "status"                    => $status
        );

        $rules = [
            'submittal_type'            => 'required',
            'submittal_number'          => 'required',
            // 'sub_submittal_type'        => 'required',
            'submittal_date'            => 'required',
            'submittal_description'     => 'required',
            'submittal_specification'   => 'required',
            'submittal_additional_path' => 'required',
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

          $submittal = ProjectSubmittals::create(['sub_type' => $submittal_type, 'sub_exist_parent' => $submittal_exist_parent, 'sub_number' => $submittal_number, 'sub_rev_number' => $submittal_rev_number, 'sub_date' => $submittal_date, 'sub_description' => $submittal_description, 'sub_specification' => $submittal_specification, 'sub_additional_comments' => $submittal_additional_comments, 'sub_additional_path' => $submittal_additional_path, 'sub_review_type' => $submittal_review_type, 'sub_request_expedited_review' => $submittal_request_expedited_review, 'sub_project_id' => $project_id, 'sub_user_id' => $user_id, 'sub_status' => $status]);

            $submittal_id = $submittal->id;
            // print_r($submittal_id);
            // exit;

            // if(count($submittal) < 1)
            // $query = DB::table('project_submittal')
            // ->insert(['sub_type' => $submittal_type, 'sub_exist_parent' => $submittal_exist_parent, 'sub_number' => $submittal_number, 'sub_rev_number' => $submittal_rev_number, 'sub_submittal_type' => $sub_submittal_type, 'sub_date' => $submittal_date, 'sub_description' => $submittal_description, 'sub_specification' => $submittal_specification, 'sub_additional_comments' => $submittal_additional_comments, 'sub_additional_path' => $submittal_additional_path, 'sub_request_expedited_review' => $submittal_request_expedited_review, 'sub_project_id' => $project_id, 'sub_user_id' => $user_id, 'sub_status' => $status]);
         

            if(count($submittal) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {
              // ->insert(['sr_submittal_id' => $submittal_id, 'sr_review_type' => $sub_submittal_type, '  sr_project_id' => $project_id, 'sr_user_id' => $user_id, 'sr_status' => $status]);
              if($submittal_type == 'new'){
                $query = DB::table('project_submittal_review')
                ->insert(['sr_type' => $submittal_type, 'sr_submittal_id' => $submittal_id, 'sr_exist_parent' => $submittal_number, 'sr_review_type' => 'pending', 'sr_project_id' => $project_id, 'sr_status' => $status]);
              }
              else {
                $query = DB::table('project_submittal_review')
                ->insert(['sr_type' => $submittal_type, 'sr_submittal_id' => $submittal_id, 'sr_exist_parent' => $submittal_exist_parent, 'sr_review_type' => 'pending', 'sr_project_id' => $project_id, 'sr_status' => $status]);
              }
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
                $check_engineer =  strpos(strtolower($check_project_user->ct_name), 'engineer');
                if(isset($check_engineer) && $check_engineer>=0)
                {
                    //print_r($check_project_user);die;
                    // Check User Permission Parameter 
                    $user_id              = $check_project_user->id;
                    $permission_key       = 'submittal_view_all';
                    // Notification Parameter
                    $project_id           = $project_id;
                    $notification_title   = 'New submittal # '.$submittal->id .' added in Project: ' .$check_project_user->p_name;
                    $url                  = App::make('url')->to('/');
                    $link                 = "/dashboard/".$project_id."/submittals/".$submittal->id;
                    $date                 = date("M d, Y h:i a");
                    $email_description    = 'A new submittal # '.$submittal->id .' has been added in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

                    $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
                    if(count($check_single_user_permission) < 1){
                        continue;
                    }
                    else {
                        $notification_key     = 'submittals_eor';
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
                }
              } // End Foreach
              // End Check User Permission and send notification and email 
                
                $result = array('description'=>"Submittal added successfully",'code'=>200);
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
   Update Submittal by passing sub_id
  --------------------------------------------------------------------------
  */
  public function update_submittal(Request $request, $sub_id)
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
        // $submittal_type                     = $request['submittal_type'];
        // $submittal_exist_parent             = $request['submittal_exist_parent'];
        // $submittal_number                   = $request['submittal_number'];
        // $submittal_date                     = $request['submittal_date'];
        // $submittal_description              = $request['submittal_description'];
        // $submittal_specification            = $request['submittal_specification'];
        // $submittal_additional_comments      = $request['submittal_additional_comments'];
        // $submittal_additional_path          = json_encode($request['submittal_additional_path']);
        // $submittal_request_expedited_review = $request['submittal_request_expedited_review'];
        // $submittal_path                     = $request['submittal_path'];
        $project_id                         = $request['project_id'];
        $user_id                            = Auth::user()->id;
        $status                             = $request['status'];
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'submittal_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            // "submittal_type"        => $submittal_type,
            // "submittal_number"      => $submittal_number,
            // "submittal_date"        => $submittal_date,
            // "submittal_description" => $submittal_description,
            // "submittal_path"        => $submittal_path,
            "project_id"            => $project_id,
            "user_id"               => $user_id,
            "status"                => $status
        );

        $rules = [
            // 'submittal_type'        => 'required',
            // 'submittal_number'      => 'required',
            // 'submittal_date'        => 'required',
            // 'submittal_description' => 'required',
            // 'submittal_path'        => 'required',
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
            $query = DB::table('project_submittals')
            ->where('sub_id', '=', $sub_id)
            // ->update(['sub_type' => $submittal_type, 'sub_exist_parent' => $submittal_exist_parent, 'sub_number' => $submittal_number, 'sub_date' => $submittal_date, 'sub_description' => $submittal_description, 'sub_specification' => $submittal_specification, 'sub_additional_comments' => $submittal_additional_comments, 'sub_additional_path' => $submittal_additional_path, 'sub_request_expedited_review' => $submittal_request_expedited_review, 'sub_path' => $submittal_path, 'sub_project_id' => $project_id, 'sub_user_id' => $user_id, 'sub_status' => $status]);
            ->update(['sub_project_id' => $project_id, 'sub_user_id' => $user_id, 'sub_status' => $status]);
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
                $permission_key       = 'submittal_view_all';
                // Notification Parameter
                $project_id           = $project_id;
                $notification_title   = 'Submittal # '.$sub_id .' updated in Project: ' .$check_project_user->p_name;
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/submittals/".$sub_id;
                $date                 = date("M d, Y h:i a");
                $email_description    = 'Submittal # '.$sub_id .' has been updated in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
                if(count($check_single_user_permission) < 1){
                  continue;
                }
                else {
                    $notification_key     = 'submittals_eor';
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
   Get single Submittal by passing sub_id
  --------------------------------------------------------------------------
  */
  public function get_submittal_single(Request $request, $sub_id)
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
        $query = DB::table('project_submittals')
                ->select('submittal_path.doc_path as submittal_path',  
          'project_submittals.*', 'projects.*', 'project_submittal_review.*',
          //'users.id as id','users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role', 
          'review_user.id as review_user_id','review_user.username as review_username', 'review_user.email as review_email', 'review_user.first_name as review_firstname', 'review_user.last_name as review_lastname', 'review_user.phone_number as review_phonenumber', 'review_user.position_title as review_positiontitle'
                        )
->leftJoin('users as review_user', 'project_submittal_review.sr_user_id', '=', 'users.id')
                ->leftJoin('documents as submittal_path', 'project_submittals.sub_additional_path', '=', 'submittal_path.doc_id')
->leftJoin('project_submittal_review', 'project_submittals.sub_id', '=', 'project_submittal_review.sr_submittal_id')
->leftJoin('projects', 'project_submittals.sub_project_id', '=', 'projects.p_id')
//->leftJoin('users', 'project_submittals.sub_user_id', '=', 'users.id')

        
        ->where('project_submittals.sub_id', '=', $sub_id)
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
   Get all Submittal by passing Project ID
  ----------------------------------------------------------------------------------
  */
  public function get_submittal_project(Request $request, $project_id)
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
        $permission_key = 'submittal_view_all';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
          $query = DB::table('project_submittals')
->leftJoin('project_standards as specification', 'project_submittals.sub_specification', '=', 'specification.ps_id')
->leftJoin('documents as submittal_path', 'project_submittals.sub_additional_path', '=', 'submittal_path.doc_id')
->leftJoin('projects', 'project_submittals.sub_project_id', '=', 'projects.p_id')
->leftJoin('project_submittal_review', 'project_submittals.sub_id', '=', 'project_submittal_review.sr_submittal_id')
->leftJoin('users', 'project_submittals.sub_user_id', '=', 'users.id')
        ->select('specification.ps_name as specification_name',
          'submittal_path.doc_path as submittal_path',  
          'project_submittals.*', 'projects.*','project_submittal_review.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('sub_project_id', '=', $project_id)
          ->orderBy('sub_number', 'desc')
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
   ADD SUBMITTAL REVIEW
  --------------------------------------------------------------------------
  */
  // public function add_submittal_review(Request $request)
  // {
  //   try
  //   {
  //     $user = array(
  //       'id'        => Auth::user()->id,
  //       'role'      => Auth::user()->role
  //     );
  //     $user = (object) $user;
  //     $post = new Resource_Post(); // You create a new resource Post instance
  //     if (Gate::forUser($user)->denies('allow_admin', [$post,false])) { 
  //       $result = array('code'=>403, "description"=>"Access denies");
  //       return response()->json($result, 403);
  //     } 
  //     else { 
  //       $submittal_id                       = $request['submittal_id'];
  //       $submittal_review                   = $request['submittal_review'];
  //       $submittal_review_type              = $request['submittal_review_type'];
  //       $submittal_additional_cost          = $request['submittal_additional_cost'];
  //       $submittal_additional_cost_currency = $request['submittal_additional_cost_currency'];
  //       $submittal_additional_cost_amount   = $request['submittal_additional_cost_amount'];
  //       $submittal_additional_day           = $request['submittal_additional_day'];
  //       $submittal_additional_day_add       = $request['submittal_additional_day_add'];
  //       $project_id                         = $request['project_id'];
  //       $user_id                            = Auth::user()->id;
  //       $status                             = 'active';

  //       $information = array(
  //           "submittal_id"              => $submittal_id,
  //           "submittal_review"          => $submittal_review,
  //           "submittal_review_type"     => $submittal_review_type,
  //           "submittal_additional_cost" => $submittal_additional_cost,
  //           "submittal_additional_day"  => $submittal_additional_day,
  //           "project_id"                => $project_id,
  //           "user_id"                   => $user_id,
  //           "status"                    => $status
  //       );

  //       $rules = [
  //           'submittal_id'              => 'required|numeric',
  //           'submittal_review'          => 'required',
  //           'submittal_review_type'     => 'required',
  //           'submittal_additional_cost' => 'required',
  //           'submittal_additional_day'  => 'required',
  //           'project_id'                => 'required|numeric',
  //           'user_id'                   => 'required|numeric',
  //           'status'                    => 'required'
  //       ];
  //       $validator = Validator::make($information, $rules);
  //       if ($validator->fails()) 
  //       {
  //           return $result = response()->json(["data" => $validator->messages()],400);
  //       }
  //       else
  //       {
  //           $query = DB::table('project_submittal_review')
  //           ->insert(['sr_submittal_id' => $submittal_id, 'sr_review' => $submittal_review, 'sr_review_type' => $submittal_review_type, 'sr_additional_cost' => $submittal_additional_cost, 'sr_additional_cost_currency' => $submittal_additional_cost_currency, 'sr_additional_cost_amount' => $submittal_additional_cost_amount, 'sr_additional_day' => $submittal_additional_day, 'sr_additional_day_add' => $submittal_additional_day_add, 'sr_project_id' => $project_id, 'sr_user_id' => $user_id, 'sr_status' => $status]);

  //           if(count($query) < 1)
  //           {
  //             $result = array('code'=>400, "description"=>"No records found");
  //             return response()->json($result, 400);
  //           }
  //           else
  //           {
  //             $result = array('description'=>"Add submittal review successfully",'code'=>200);
  //             return response()->json($result, 200);
  //           }
  //       }
  //     }
  //   }
  //   catch(Exception $e)
  //   {
  //     return response()->json(['error' => 'Something is wrong'], 500);
  //   }
  // }

  /*
  --------------------------------------------------------------------------
   Update Submittal Review by passing sr_id
  --------------------------------------------------------------------------
  */
  public function update_submittal_review(Request $request, $sr_id)
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
        $submittal_id                       = $request['submittal_id'];
        $submittal_review                   = $request['submittal_review'];
        $submittal_review_type              = $request['submittal_review_type'];
        $respond_date                       = $request['respond_date'];
        // $submittal_additional_cost          = $request['submittal_additional_cost'];
        // $submittal_additional_cost_currency = $request['submittal_additional_cost_currency'];
        // $submittal_additional_cost_amount   = $request['submittal_additional_cost_amount'];
        // $submittal_additional_day           = $request['submittal_additional_day'];
        // $submittal_additional_day_add       = $request['submittal_additional_day_add'];
        $project_id                         = $request['project_id'];
        $user_id                            = Auth::user()->id;
        $status                             = 'active';
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'submittal_review_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            "submittal_id"              => $submittal_id,
            "submittal_review"          => $submittal_review,
            "submittal_review_type"     => $submittal_review_type,
            "respond_date"              => $respond_date,
            // "submittal_additional_cost" => $submittal_additional_cost,
            // "submittal_additional_day"  => $submittal_additional_day,
            "project_id"                => $project_id,
            "user_id"                   => $user_id,
            "status"                    => $status
        );

        $rules = [
            'submittal_id'              => 'required|numeric',
            'submittal_review'          => 'required',
            'submittal_review_type'     => 'required',
            'respond_date'              => 'required',
            // 'submittal_additional_cost' => 'required',
            // 'submittal_additional_day'  => 'required',
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
            $query = DB::table('project_submittal_review')
            ->where('sr_id', '=', $sr_id)
            // ->update(['sr_submittal_id' => $submittal_id, 'sr_review' => $submittal_review, 'sr_review_type' => $submittal_review_type, 'sr_additional_cost' => $submittal_additional_cost, 'sr_additional_cost_currency' => $submittal_additional_cost_currency, 'sr_additional_cost_amount' => $submittal_additional_cost_amount, 'sr_additional_day' => $submittal_additional_day, 'sr_additional_day_add' => $submittal_additional_day_add, 'sr_project_id' => $project_id, 'sr_user_id' => $user_id, 'sr_status' => $status]);
            ->update(['sr_submittal_id' => $submittal_id, 'sr_review' => $submittal_review, 'sr_respond_date' => $respond_date, 'sr_review_type' => $submittal_review_type, 'sr_project_id' => $project_id, 'sr_user_id' => $user_id, 'sr_status' => $status]);
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
//                $permission_key       = 'submittal_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Recevied review in submittal # '.$sr_id .' in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/submittals/".$sr_id;
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Recevied review in submittal # '.$sr_id .' in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
//
//                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
//                if(count($check_single_user_permission) < 1){
//                  continue;
//                }
//                else {
//                  // Send Notification to users
//                  $project_notification_query = app('App\Http\Controllers\Projects\NotificationController')->add_notification($notification_title, $link, $project_id, $check_single_user_permission[0]->pup_user_id);
//               
//                   $user_detail = array(
//                     'id'              => $check_project_user->id,
//                     'name'            => $check_project_user->username,
//                     'email'           => $check_project_user->email,
//                     'link'            => $link,
//                     'date'            => $date,
//                     'project_name'    => $check_project_user->p_name,
//                     'title'           => $notification_title,
//                     'description'     => $email_description
//                   );
//                   $user_single = (object) $user_detail;
//                   Mail::send('emails.send_notification',['user' => $user_single], function ($message) use ($user_single) {
//                       $message->from('no-reply@sw.ai', 'StratusCM');
//                       $message->to($user_single->email, $user_single->name)->subject($user_single->title);
//                   });
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
   Get single Submittal Review by passing sr_id
  --------------------------------------------------------------------------
  */
  public function get_submittal_review_single(Request $request, $sr_id)
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
        $query = DB::table('project_submittal_review')
->leftJoin('project_submittals as submittal', 'project_submittal_review.sr_submittal_id', '=', 'submittal.sub_id')
->leftJoin('documents as submittal_path', 'submittal.sub_additional_path', '=', 'submittal_path.doc_id')
// ->leftJoin('currency', 'project_submittal_review.sr_additional_cost_currency', '=', 'currency.cur_id')
->leftJoin('projects', 'project_submittal_review.sr_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_submittal_review.sr_user_id', '=', 'users.id')
        ->select('submittal_path.doc_path as submittal_path', 'submittal.*', 'project_submittal_review.*', 'projects.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('sr_id', '=', $sr_id)
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
   Get all Submittal Review by passing Submittal ID
  ----------------------------------------------------------------------------------
  */
  public function get_submittal_review_project(Request $request, $submittal_id)
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
      $permission_key = 'submittal_review_view_all';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($submittal_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
          $query = DB::table('project_submittal_review')
->leftJoin('project_submittals as submittal', 'project_submittal_review.sr_submittal_id', '=', 'submittal.sub_id')
// ->leftJoin('currency', 'project_submittal_review.sr_additional_cost_currency', '=', 'currency.cur_id')
->leftJoin('projects', 'project_submittal_review.sr_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_submittal_review.sr_user_id', '=', 'users.id')
        ->select('submittal.*', 'project_submittal_review.*', 'projects.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('sr_project_id', '=', $submittal_id)
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
   Get Submittal Log by passing Project ID
  ----------------------------------------------------------------------------------
  */
  public function get_submittal_log(Request $request, $project_id)
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
      $permission_key = 'submittal_log';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
          $query = DB::table('project_submittals')
->leftJoin('documents as submittal_path', 'project_submittals.sub_additional_path', '=', 'submittal_path.doc_id')
->leftJoin('projects', 'project_submittals.sub_project_id', '=', 'projects.p_id')
->leftJoin('project_submittal_review', 'project_submittals.sub_id', '=', 'project_submittal_review.sr_submittal_id')
->leftJoin('users', 'project_submittals.sub_user_id', '=', 'users.id')
->leftJoin('project_firm', 'users.company_name', '=', 'project_firm.f_id')
        ->select('submittal_path.doc_path as submittal_path', 'project_submittals.*', 'project_submittal_review.*', 'projects.*', 'project_firm.*',
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('sub_project_id', '=', $project_id)
          // ->where('project_submittal_review.sr_review_type', '!=', 'pending')
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
   Get New Submittal Number
  ----------------------------------------------------------------------------------
  */
  public function get_new_submittal_number(Request $request, $project_id)
  {
      $query = DB::table('project_submittals')
      ->select()
      ->where('sub_type', '=', 'new')
      ->where('sub_project_id', '=', $project_id)
      ->orderBy('sub_number', 'desc')
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
  }

  /*
  ----------------------------------------------------------------------------------
   Get New Resubmittal Number
  ----------------------------------------------------------------------------------
  */
  public function get_new_resubmittal_number(Request $request, $project_id, $submittal_id)
  {
      $query = DB::table('project_submittals')
      ->select()
      ->where('sub_type', '=', 'exist')
      ->where('sub_project_id', '=', $project_id)
      ->where('sub_exist_parent', '=', $submittal_id)
      ->orderBy('sub_rev_number', 'desc')
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
  }

  /*
  --------------------------------------------------------------------------
   Get All Unverified User and expire if X day value cross
  --------------------------------------------------------------------------
  */
  public function check_review_response(Request $request)
  {
      try
      {
        $days = config('app.review_status_change');
        $query = DB::table('project_submittal_review')
        ->select()
        ->where('sr_review_type', '=', 'make_corrections_noted')
        ->orwhere('sr_review_type', '=', 'revise_resubmit')
        ->orwhere('sr_review_type', '=', 'rejected')
        ->orwhere('sr_review_type', '=', 'pending')
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
              if($current_date >= $plus_date){
                $review = DB::table('project_submittal_review')
                ->where('sr_id', '=', $review->sr_id)
                ->update(['sr_review_type' => 'past_due']);
                if(count($review) < 1)
                {
                  $result = array('code'=>404, "description"=>"No Records Found");
                  return response()->json($result, 404);
                }
                else {

                }
                // echo $current_date .' - '. $plus_date;
                // echo '<br/>';
              }
              else {
                // echo 'no';
              }

            }
        }
          $result = array('description'=>'Update Status successfully','code'=>200);
          return response()->json($result, 200);
        }
        catch(Exception $e)
        {
          return response()->json(['error' => 'Something is wrong'], 500);
        }
  }


}