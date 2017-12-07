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
use App\ProjectNoticeofAward; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post; 
use App\Repositories\Util\AclPolicy;


class NoticeAwardController extends Controller {

  /*
  --------------------------------------------------------------------------
   Add Notice of Award
  --------------------------------------------------------------------------
  */
  public function add_notice_award(Request $request)
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
        $notice_status      = $request['notice_status'];
        $contactor_name     = $request['contactor_name'];
        $improvement_type   = $request['improvement_type'];
        $contact_amount     = $request['contact_amount'];
        $award_date         = $request['award_date'];
        $notice_path        = $request['notice_path'];
        // $notice_sign        = $request['notice_sign'];
        $project_id         = $request['project_id'];
        $user_id            = Auth::user()->id;
        $status             = 'active';
    // Check User Permission Parameter 
    $user_id = Auth::user()->id;
    $permission_key = 'notice_award_add';
    $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
    if(count($check_single_user_permission) < 1){
      $result = array('code'=>403, "description"=>"Access Denies");
      return response()->json($result, 403);
    }
    else {
        if($notice_status == 'new'){
          $information = array(
            "notice_status"   => $notice_status,
            "improvement_type"=> $improvement_type,
            "contactor_name"  => $contactor_name,
            "contact_amount"  => $contact_amount,
            "award_date"      => $award_date,
            "project_id"      => $project_id,
            "user_id"         => $user_id,
            "status"          => $status
          );

          $rules = [
            'notice_status'   => 'required',
            'improvement_type'=> 'required',
            'contactor_name'  => 'numeric',
            'contact_amount'  => 'required',
            'award_date'      => 'required',
            'project_id'      => 'required|numeric',
            'user_id'         => 'required|numeric',
            'status'          => 'required'
          ];
        }
        else {
          $information = array(
            "notice_path"     => $notice_path,
            "improvement_type"=> $improvement_type,
            "project_id"      => $project_id,
            "user_id"         => $user_id,
            "status"          => $status
          );

          $rules = [
            'notice_path'     => 'required',
            'improvement_type'=> 'required',
            'project_id'      => 'required|numeric',
            'user_id'         => 'required|numeric',
            'status'          => 'required'
          ];
        }

        // $information = array(
        //     "notice_status"   => $notice_status,
        //     // "contactor_name"  => $contactor_name,
        //     "project_id"      => $project_id,
        //     "user_id"         => $user_id,
        //     "status"          => $status
        // );

        // $rules = [
        //     'notice_status'   => 'required',
        //     // 'contactor_name'  => 'numeric',
        //     'project_id'      => 'required|numeric',
        //     'user_id'         => 'required|numeric',
        //     'status'          => 'required'
        // ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            // $query = DB::table('project_notice_award')
            // ->insert(['pna_notice_status' => $notice_status, 'pna_improvement_type' => $improvement_type, 'pna_contactor_name' => $contactor_name, 'pna_contact_amount' => $contact_amount, 'pna_award_date' => $award_date, 'pna_notice_path' => $notice_path, 'pna_project_id' => $project_id, 'pna_user_id' => $user_id, 'pna_status' => $status]);
            $notice_award = ProjectNoticeofAward::create(['pna_notice_status' => $notice_status, 'pna_improvement_type' => $improvement_type, 'pna_contactor_name' => $contactor_name, 'pna_contact_amount' => $contact_amount, 'pna_award_date' => $award_date, 'pna_notice_path' => $notice_path, 'pna_project_id' => $project_id, 'pna_user_id' => $user_id, 'pna_status' => $status]);

            $notice_award_id = $notice_award->id;

            if(count($notice_award) < 1)
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
                $permission_key       = 'notice_award_view_all';
                $notification_key     = 'notice_of_award_upload';
                // Notification Parameter
                $project_id           = $project_id;
                $notification_title   = 'Add new notice of award in Project: ' .$check_project_user->p_name;
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/notice_award";
                $date                 = date("M d, Y h:i a");
                $email_description    = 'Add new notice of award in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
                if(count($check_single_user_permission) < 1){
                  continue;
                }
                else {
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

              $result = array('description'=>$notice_award_id,'code'=>200);
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
   Update Notice of Award by passing sw_id
  --------------------------------------------------------------------------
  */
  public function update_notice_award(Request $request, $pna_id)
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
        // $notice_status      = $request['notice_status'];
        // $contactor_name     = $request['contactor_name'];
        // $currency           = $request['currency'];
        $notice_sign_owner        = $request['notice_sign_owner'];
        $notice_review_owner      = $request['notice_review_owner'];
        $notice_sign_contractor   = $request['notice_sign_contractor'];
        $notice_review_contractor = $request['notice_review_contractor'];
        $project_id               = $request['project_id'];
        $user_id                  = Auth::user()->id;
        $status                   = $request['status'];
    // Check User Permission Parameter 
    $user_id = Auth::user()->id;
    $permission_key = 'notice_award_update';
    $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
    if(count($check_single_user_permission) < 1){
      $result = array('code'=>403, "description"=>"Access Denies");
      return response()->json($result, 403);
    }
    else {    
        $information = array(
            // "notice_status"   => $notice_status,
            // "contactor_name"  => $contactor_name,
            "project_id"      => $project_id,
            "user_id"         => $user_id,
            "status"          => $status
        );

        $rules = [
            // 'notice_status'   => 'required',
            // 'contactor_name'  => 'numeric',
            'project_id'      => 'required|numeric',
            'user_id'         => 'required|numeric',
            'status'          => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {   
            $user_detail = DB::table('project_notice_award')
            ->select()
            ->where('pna_id', '=', $pna_id)
            ->first();
            if(($notice_sign_owner == "") ? $notice_sign_owner = $user_detail->pna_notice_sign_owner : $notice_sign_owner = $notice_sign_owner);
            if(($notice_review_owner == "") ? $notice_review_owner = $user_detail->pna_notice_review_owner : $notice_review_owner = $notice_review_owner);
            if(($notice_sign_contractor == "") ? $notice_sign_contractor = $user_detail->pna_notice_sign_contractor : $notice_sign_contractor = $notice_sign_contractor);
            if(($notice_review_contractor == "") ? $notice_review_contractor = $user_detail->pna_notice_review_contractor : $notice_review_contractor = $notice_review_contractor);

            $query = DB::table('project_notice_award')
            ->where('pna_id', '=', $pna_id)
            // ->update(['pna_notice_status' => $notice_status, 'pna_contactor_name' => $contactor_name, 'pna_currency' => $currency, 'pna_contact_amount' => $contact_amount, 'pna_award_date' => $award_date, 'pna_notice_path' => $notice_path, 'pna_notice_sign' => $notice_sign, 'pna_project_id' => $project_id, 'pna_user_id' => $user_id, 'pna_status' => $status]);
            ->update(['pna_project_id' => $project_id, 'pna_notice_sign_owner' => $notice_sign_owner, 'pna_notice_review_owner' => $notice_review_owner, 'pna_notice_sign_contractor' => $notice_sign_contractor, 'pna_notice_review_contractor' => $notice_review_contractor, 'pna_user_id' => $user_id, 'pna_status' => $status]);
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
//                $permission_key       = 'notice_award_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Update notice of award status in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/notice_award/";
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Update status notice of award # '.$pna_id.' in Project : <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
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
   Get single Notice of Award by passing pna_id
  --------------------------------------------------------------------------
  */
  public function get_notice_award_single(Request $request, $pna_id)
  {
    try
    {
      $user = array(
        'userid'    => Auth::user()->id,
        'role'      => Auth::user()->role
      );
      // $user = (object) $user;
      // $post = new Resource_Post(); // You create a new resource Post instance
      // if (Gate::forUser($user)->denies('allow_admin', [$post,false])) { 
      //   $result = array('code'=>403, "description"=>"Access denies");
      //   return response()->json($result, 403);
      // } 
      // else {
        $query = DB::table('project_notice_award')
        ->leftJoin('project_firm', 'project_notice_award.pna_contactor_name', '=', 'project_firm.f_id')
        ->leftJoin('project_type_improvement', 'project_notice_award.pna_improvement_type', '=', 'project_type_improvement.pt_id')
        ->leftJoin('documents', 'project_notice_award.pna_notice_path', '=', 'documents.doc_id')
        ->leftJoin('documents as owner_sign', 'project_notice_award.pna_notice_sign_owner', '=', 'owner_sign.doc_id')
        ->leftJoin('documents as contractor_sign', 'project_notice_award.pna_notice_sign_contractor', '=', 'contractor_sign.doc_id')
        ->leftJoin('projects', 'project_notice_award.pna_project_id', '=', 'projects.p_id')
        ->leftJoin('users', 'project_notice_award.pna_user_id', '=', 'users.id')
        ->select('project_notice_award.*', 'project_firm.*', 'documents.*', 'owner_sign.doc_path as owner_sign_doc_path', 'contractor_sign.doc_path as contractor_sign_doc_path', 'projects.*', 'project_type_improvement.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('pna_id', '=', $pna_id)
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
   Get all Notice of Award by passing Project ID
  ----------------------------------------------------------------------------------
  */
  public function get_notice_award_project(Request $request, $project_id)
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
    $permission_key = 'notice_award_view_all';
    $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
    if(count($check_single_user_permission) < 1){
      $result = array('code'=>403, "description"=>"Access Denies");
      return response()->json($result, 403);
    }
    else { 
         $query = DB::table('project_notice_award')
          ->leftJoin('project_firm', 'project_notice_award.pna_contactor_name', '=', 'project_firm.f_id')
          ->leftJoin('project_type_improvement', 'project_notice_award.pna_improvement_type', '=', 'project_type_improvement.pt_id')
          ->leftJoin('project_settings', 'project_notice_award.pna_project_id', '=', 'project_settings.pset_project_id')
          ->leftJoin('currency', 'project_settings.pset_meta_value', '=', 'currency.cur_id')
          ->leftJoin('documents', 'project_notice_award.pna_notice_path', '=', 'documents.doc_id')
          ->leftJoin('projects', 'project_notice_award.pna_project_id', '=', 'projects.p_id')
          ->leftJoin('users', 'project_notice_award.pna_user_id', '=', 'users.id')
          ->select('currency.cur_symbol as currency_symbol', 'project_notice_award.*', 'project_firm.*', 'documents.*', 'projects.*', 'project_type_improvement.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('pna_project_id', '=', $project_id)
          ->groupBy('project_notice_award.pna_id')
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