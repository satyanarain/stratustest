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
use App\ProjectChangeOrderRequest; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post; 
use App\Repositories\Util\AclPolicy;
use App\Traits\ProjectImprovement;

 

class ChangeOrderRequestController extends Controller {
use ProjectImprovement;
  /*
  --------------------------------------------------------------------------
   ADD Change Order Request
  --------------------------------------------------------------------------
  */
  public function add_change_order_request(Request $request)
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
        $order_number             = $request['order_number'];
        $order_date               = $request['order_date'];
        $order_contractor_name    = $request['order_contractor_name'];
        //$order_rfi              = $request['order_rfi'];
        $order_status             = '';//$request['order_status'];
        $order_project_id         = $request['order_project_id'];
        $order_user_id            = Auth::user()->id;
        $status                   = 'active'; // $request['status'];
        
        
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'cor_add';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($order_project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            "pco_number"          => $order_number,
            "pco_date"            => $order_date,
            "pco_contractor_name" => $order_contractor_name,
            // "pco_rfi"             => $order_rfi,
            "pco_order_status"    => $order_status,
            "pco_project_id"      => $order_project_id,
            "pco_user_id"         => $order_user_id,
            "pco_status"          => $status
        );

        $rules = [
            'pco_number'          => 'required',
            'pco_date'            => 'required',
            'pco_contractor_name' => 'required',
            // 'pco_rfi'             => 'required',
            // 'pco_order_status'    => 'required',
            'pco_project_id'      => 'required|numeric',
            'pco_user_id'         => 'required|numeric',
            'pco_status'          => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            
            $change_order = ProjectChangeOrderRequest::create(['pco_number' => $order_number, 'pco_date' => $order_date, 'pco_contractor_name' => $order_contractor_name, 'pco_order_status' => $order_status, 'pco_project_id' => $order_project_id, 'pco_user_id' => $order_user_id, 'pco_status' => $status]);

            $change_order_id = $change_order->id;

            if(count($change_order) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {
//              $project_id = $order_project_id;
//              // Start Check User Permission and send notification and email  
//              // Get Project Users
//              $check_project_users = app('App\Http\Controllers\Projects\PermissionController')->check_project_user($project_id);
//
//              // Check User Project Permission  
//              foreach ($check_project_users as $check_project_user) {
//                // Check User Permission Parameter 
//                $user_id              = $check_project_user->id;
//                $permission_key       = 'cor_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'New change order request # '.$order_number.' added in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/change_order_request";
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'A new change order request # '.$order_number.' has been added in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
//
//                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
//                if(count($check_single_user_permission) < 1){
//                  continue;
//                }
//                else {
//                    $notification_key     = 'change_orders';
//                    $check_project_user_notification = app('App\Http\Controllers\Projects\PermissionController')->check_project_user_notification($project_id,$user_id,$notification_key);
//                    if(count($check_project_user_notification) < 1){
//                      continue;
//                    }else{
//                        // Send Notification to users
//                        $project_notification_query = app('App\Http\Controllers\Projects\NotificationController')->add_notification($notification_title, $link, $project_id, $check_single_user_permission[0]->pup_user_id);
//
//                         $user_detail = array(
//                           'id'              => $check_project_user->id,
//                           'name'            => $check_project_user->username,
//                           'email'           => $check_project_user->email,
//                           'link'            => $link,
//                           'date'            => $date,
//                           'project_name'    => $check_project_user->p_name,
//                           'title'           => $notification_title,
//                           'description'     => $email_description
//                         );
//                         $user_single = (object) $user_detail;
//                         Mail::send('emails.send_notification',['user' => $user_single], function ($message) use ($user_single) {
//                             $message->from('no-reply@sw.ai', 'StratusCM');
//                             $message->to($user_single->email, $user_single->name)->subject($user_single->title);
//                         });
//                    }
//                }
//              } // End Foreach
              // End Check User Permission and send notification and email 

              $result = array('change_order_id'=>$change_order_id,'description'=>"Add change order request successfully",'code'=>200);
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
   ADD Change Order Request Item
  --------------------------------------------------------------------------
  */
  public function add_change_order_request_item(Request $request)
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
        $latest_cor = DB::table('project_change_order_request_detail')
                ->select('project_change_order_request_detail.pcd_number')
                ->where('pcd_project_id', '=', $request['order_project_id'])
                ->where('is_potential','=',0)
                ->orderBy('pcd_number', 'desc')
                ->first();
        if($latest_cor)
            $latest_cor_no = $latest_cor->pcd_number+1;
        else
            $latest_cor_no = 1;
        $order_description        = $request['order_description'];
        $order_price              = $request['order_price'];
        $order_unit_price         = $request['order_unit_price'];
        $order_unit_number        = $request['order_unit_number'];
        $order_days               = $request['order_days'];
        $order_file_path          = $request['order_file_path'];
        $order_rfi                = $request['order_rfi'];
        $order_parent_cor         = $request['order_parent_cor'];
        $order_project_id         = $request['order_project_id'];
        $order_user_id            = Auth::user()->id;
        $docusign_status          = 'pending';
        $pcd_approved_by_cm         = "0000-00-00";
        $pcd_approved_by_owner         = "0000-00-00";
        $pcd_denied_by_cm         = "0000-00-00";
        $pcd_denied_by_owner         = "0000-00-00";
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'cor_add';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($order_project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            "pcd_description"     => $order_description,
            "pcd_price"           => $order_price,
            "pcd_unit_number"     => $order_unit_number,
            "pcd_unit_price"      => $order_unit_price,
            "pcd_days"            => $order_days,
            "pcd_file_path"       => $order_file_path,
            "pcd_rfi"             => $order_rfi,
            "pcd_parent_cor"      => $order_parent_cor,
            "pcd_project_id"      => $order_project_id,
            "pcd_user_id"         => $order_user_id
        );

        $rules = [
            'pcd_description'     => 'required',
            // 'pcd_price'           => 'required',
            // 'pcd_unit_number'     => 'required|numeric',
            // 'pcd_unit_price'      => 'required',
            'pcd_days'            => 'required|numeric',
            //'pcd_file_path'       => 'required|numeric',
            'pcd_parent_cor'      => 'required|numeric',
            'pcd_project_id'      => 'required|numeric',
            'pcd_user_id'         => 'required|numeric'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            
            $query = DB::table('project_change_order_request_detail')
            ->insert(['pcd_number'=>$latest_cor_no,'pcd_status'=>"pending",'pcd_denied_by_owner'=>$pcd_denied_by_owner,'pcd_denied_by_cm'=>$pcd_denied_by_cm,'pcd_approved_by_owner'=>$pcd_approved_by_owner,'pcd_approved_by_cm'=>$pcd_approved_by_cm,'docusign_status'=>$docusign_status,'pcd_description' => $order_description, 'pcd_price' => $order_price, 'pcd_unit_number' => $order_unit_number, 'pcd_unit_price' => $order_unit_price, 'pcd_days' => $order_days, 'pcd_file_path' => $order_file_path, 'pcd_rfi' => $order_rfi, 'pcd_parent_cor' => $order_parent_cor, 'pcd_project_id' => $order_project_id, 'pcd_user_id' => $order_user_id]);
            $pcd_id = DB::getPdo()->lastInsertId();
            if(count($query) < 1)
            {
                $result = array('code'=>400, "description"=>"No records found");
                return response()->json($result, 400);
            }
            else
            {
                $project = DB::table('projects')
                ->select('projects.*')
                ->where('p_id', '=', $order_project_id)
                ->first();
                $project_id           = $order_project_id;
                $notification_title   = 'New change order request item # '.$latest_cor_no.' has been added for your review in Project: ' .$project->p_name;
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$order_project_id."/change_order_request_review/".$pcd_id."/update";
                $date                 = date("M d, Y h:i a");
                $email_description    = 'A new change order request item # '.$latest_cor_no.' has been added for your review in Project: <strong>'.$project->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
                if($request['cm_email'])
                {
                    $query = DB::table('project_reviewer')
                    ->insert(['name'=>$request['cm_name'],'email'=>$request['cm_email'],'project_id'=>$order_project_id,'type'=>"change_order",'doc_id'=>$pcd_id,'designation'=>"cm"]);
                    $user_detail = array(
                        'name'            => $request['cm_name'],
                        'email'           => $request['cm_email'],
                        'link'            => $link,
                        'date'            => $date,
                        'project_name'    => $project->p_name,
                        'title'           => $notification_title,
                        'description'     => $email_description
                    );
                    $user_single = (object) $user_detail;
                    Mail::send('emails.send_notification',['user' => $user_single], function ($message) use ($user_single) {
                        $message->from('no-reply@sw.ai', 'StratusCM');
                        $message->to($user_single->email, $user_single->name)->subject($user_single->title);
                    });
                }
                if($request['owner_email'])
                {
                    $query = DB::table('project_reviewer')
                    ->insert(['name'=>$request['owner_name'],'email'=>$request['owner_email'],'project_id'=>$order_project_id,'type'=>"change_order",'doc_id'=>$pcd_id,'designation'=>"owner"]);
                    $user_detail = array(
                        'name'            => $request['owner_name'],
                        'email'           => $request['owner_email'],
                        'link'            => $link,
                        'date'            => $date,
                        'project_name'    => $project->p_name,
                        'title'           => $notification_title,
                        'description'     => $email_description
                    );
                    $user_single = (object) $user_detail;
                    Mail::send('emails.send_notification',['user' => $user_single], function ($message) use ($user_single) {
                        $message->from('no-reply@sw.ai', 'StratusCM');
                        $message->to($user_single->email, $user_single->name)->subject($user_single->title);
                    });
                }
                
                $check_project_users = app('App\Http\Controllers\Projects\PermissionController')->check_project_user($order_project_id);
              // Check User Project Permission  
              foreach ($check_project_users as $check_project_user) {
                // Check User Permission Parameter 
                $user_id              = $check_project_user->id;
                $permission_key       = 'cor_view_all';
                // Notification Parameter
                $project_id           = $order_project_id;
                $notification_title   = 'New change order request # '.$latest_cor_no.' added in Project: ' .$check_project_user->p_name;
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$order_project_id."/change_order_request";
                $date                 = date("M d, Y h:i a");
                $email_description    = 'A new change order request # '.$latest_cor_no.' has been added in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($order_project_id, $user_id, $permission_key);
                if(count($check_single_user_permission) < 1){
                  continue;
                }
                else {
                    $notification_key     = 'change_orders';
                    $check_project_user_notification = app('App\Http\Controllers\Projects\PermissionController')->check_project_user_notification($order_project_id,$user_id,$notification_key);
                    if(count($check_project_user_notification) < 1){
                      continue;
                    }else{
                        // Send Notification to users
                        $project_notification_query = app('App\Http\Controllers\Projects\NotificationController')->add_notification($notification_title, $link, $order_project_id, $check_single_user_permission[0]->pup_user_id);

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
              
              
                $result = array('pcd_id'=>$pcd_id,'description'=>"Add change order request successfully",'code'=>200);
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
   Update Change Order Request by passing psa_id
  --------------------------------------------------------------------------
  */
  public function update_change_order_request(Request $request, $pco_id)
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
        $status                 = $request['status'];
        $project_id             = $request['project_id'];
        $user_id                = Auth::user()->id;
    // Check User Permission Parameter 
    $user_id = Auth::user()->id;
    $permission_key = 'cor_review_update';
    $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
    if(count($check_single_user_permission) < 1){
      $result = array('code'=>403, "description"=>"Access Denies");
      return response()->json($result, 403);
    }
    else {
        $information = array(
            "status"              => $status,
            "project_id"          => $project_id,
            "user_id"             => $user_id,
        );

        $rules = [
            'status'              => 'required',
            'project_id'          => 'required|numeric',
            'user_id'             => 'required|numeric',
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_change_order_request')
            ->where('pco_id', '=', $pco_id)
             ->update(['pco_status' => $status, 'pco_project_id' => $project_id, 'pco_user_id' => $user_id]);
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
   Update Change Order Request Item by passing pcd_id
  --------------------------------------------------------------------------
  */
  public function update_change_order_request_item(Request $request, $pcd_id)
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
        $approved_by_cm           = $request['approved_by_cm'];
        $approved_by_owner        = $request['approved_by_owner'];
        $project_id               = $request['project_id'];
        $user_id                  = Auth::user()->id;
        $cor_description          = $request['cor_description'];
        $pcd_unit_number          = $request['pcd_unit_number'];
        $pcd_unit_price           = $request['pcd_unit_price'];
        $pcd_price                = $request['pcd_price'];
        $change_order_day         = $request['change_order_day'];
        $pco_number               = $request['pco_number'];
        $latest_cor_no            = $request['pco_number'];
        $denied_by_owner          = $request['denied_by_owner'];
        $denied_by_cm             = $request['denied_by_cm'];
        $order_rfi                = $request['order_rfi'];
        $change_order_status      = $request['change_order_status'];
        if($approved_by_cm || $approved_by_owner || $denied_by_cm || $denied_by_owner)
        {
            $job_type   =   'reviewed';
            $link       = "/dashboard/".$project_id."/change_order_request_review/".$pcd_id."/view";
        }else{
            $job_type   =   'updated';
            $link       = "/dashboard/".$project_id."/change_order_request_review/".$pcd_id."/update";
        }
        if($request['remove_potential']==1 && $request['is_potential']==1)
        {
            $latest_cor = DB::table('project_change_order_request_detail')
                ->select('project_change_order_request_detail.pcd_number')
                ->where('pcd_project_id', '=', $project_id)
                ->where('is_potential','=',0)
                ->orderBy('pcd_number', 'desc')
                ->first();
            if($latest_cor)
                $latest_cor_no = $latest_cor->pcd_number+1;
            else
                $latest_cor_no = 1;
            $query = DB::table('project_change_order_request_detail')
            ->where('pcd_id', '=', $pcd_id)
             ->update(['is_potential' => 0]);
            $potential_cors = DB::table('project_change_order_request_detail')
                ->select('*')
                ->where('pcd_project_id', '=', $project_id)
                ->where('is_potential','=',1)
                ->where('pcd_id','>',$pcd_id)
                ->orderBy('pcd_id', 'asc')
                ->get();
            //print_r($potential_cors);die;
            foreach($potential_cors as $cors)
            {
                $pcd_number = $cors->pcd_number-1;
                $query = DB::table('project_change_order_request_detail')
                    ->where('pcd_id', '=', $cors->pcd_id)
                    ->update(['pcd_number'=>$pcd_number]);
            }
        }
        $information = array(
            "approved_by_cm"      => $approved_by_cm,
            "approved_by_owner"   => $approved_by_owner,
            "project_id"          => $project_id,
            "user_id"             => $user_id,
        );

        $rules = [
            // 'status'              => 'required',
            'project_id'          => 'required|numeric',
            'user_id'             => 'required|numeric',
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_change_order_request_detail')
            ->where('pcd_id', '=', $pcd_id)
             ->update(['change_order_status'=>$change_order_status,'pcd_number'=>$latest_cor_no,'pcd_rfi' => $order_rfi,'pcd_status'=>$request['pcd_status'],'owner_rejection_comment'=>$request['owner_rejection_comment'],'cm_rejection_comment'=>$request['cm_rejection_comment'],'pcd_denied_by_cm'=>$denied_by_cm,'pcd_denied_by_owner'=>$denied_by_owner,'pcd_unit_number' => $pcd_unit_number,'pcd_unit_price' => $pcd_unit_price,'pcd_price' => $pcd_price,'pcd_description' => $cor_description,'pcd_days' => $change_order_day,'pcd_approved_by_cm' => $approved_by_cm, 'pcd_approved_by_owner' => $approved_by_owner, 'pcd_user_id' => $user_id]);
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
                $permission_key       = 'cor_view_all';
                // Notification Parameter
                
                $project_id           = $project_id;
                $notification_title   = 'Change order # '.$latest_cor_no.' has been '.$job_type.' in Project: ' .$check_project_user->p_name;
                $url                  = App::make('url')->to('/');
                
                $date                 = date("M d, Y h:i a");
                $email_description    = 'Change order # '.$latest_cor_no.' has been '.$job_type.' in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

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
   Get single Change Order Request by passing pco_id
  --------------------------------------------------------------------------
  */
  public function get_change_order_request_single(Request $request, $pco_id)
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
        $query = DB::table('project_change_order_request')
->leftJoin('projects', 'project_change_order_request.pco_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_change_order_request.pco_user_id', '=', 'users.id')
        ->select('project_change_order_request.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('pco_id', '=', $pco_id)
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


public function get_change_order_request_weeklyreport(Request $request, $project_id)
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
        $query = DB::table('project_change_order_request_detail')
        ->select('project_change_order_request_detail.*')
        ->where('pcd_approved_by_cm', '!=', '0000-00-00')
        ->where('pcd_approved_by_owner', '!=', '0000-00-00')
        ->where('pcd_project_id', '=', $request['project_id'])
         ->orderBy('pcd_timestamp','DESC')
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
   Get single Change Order Request Item by passing pcd_id
  --------------------------------------------------------------------------
  */
  public function get_change_order_request_item_single(Request $request, $pcd_id)
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
        $query = DB::table('project_change_order_request_detail')
->leftJoin('project_change_order_request', 'project_change_order_request_detail.pcd_parent_cor', '=', 'project_change_order_request.pco_id')
->leftJoin('projects', 'project_change_order_request_detail.pcd_project_id', '=', 'projects.p_id')
->leftJoin('documents', 'project_change_order_request_detail.pcd_file_path', '=', 'documents.doc_id')
->leftJoin('project_firm', 'project_change_order_request.pco_contractor_name', '=', 'project_firm.f_id')
->leftJoin('users', 'project_change_order_request_detail.pcd_user_id', '=', 'users.id')
        ->select('project_change_order_request.*', 'project_change_order_request_detail.*', 'projects.*', 'documents.*', 'project_firm.f_name as agency_name', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('pcd_id', '=', $pcd_id)
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
   Get all Change Order Request item by passing project_id
  ----------------------------------------------------------------------------------
  */
  public function get_all_change_order_request_item(Request $request, $project_id)
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
      $permission_key = 'cor_view_all';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
          //DB::enableQueryLog();
          $query = DB::table('project_change_order_request_detail')
->leftJoin('project_settings', 'project_change_order_request_detail.pcd_project_id', '=', 'project_settings.pset_project_id')
->leftJoin('currency', 'project_settings.pset_meta_value', '=', 'currency.cur_id')
->leftJoin('projects', 'project_change_order_request_detail.pcd_project_id', '=', 'projects.p_id')
->leftJoin('project_change_order_request', 'project_change_order_request_detail.pcd_parent_cor', '=', 'project_change_order_request.pco_id')
->leftJoin('project_firm', 'project_change_order_request.pco_contractor_name', '=', 'project_firm.f_id')
->leftJoin('users', 'project_change_order_request_detail.pcd_user_id', '=', 'users.id')
        ->select('currency.cur_symbol as currency_symbol', 'project_change_order_request_detail.*', 
                'project_change_order_request.*', 'projects.*', 'project_firm.f_name as agency_name', 
                'users.username as user_name', 'users.email as user_email', 
                'users.first_name as user_firstname','users.last_name as user_lastname', 
                'users.company_name as user_company','users.phone_number as user_phonenumber',
                'users.status as user_status','users.role as user_role')
          ->where('project_change_order_request_detail.pcd_project_id', '=', $project_id)
          ->groupBy('project_change_order_request_detail.pcd_id')
          ->orderBy('project_change_order_request_detail.pcd_id','ASC')
          ->get();
          //dd(DB::getQueryLog());
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
   Get all Change Order Request by passing project_id
  ----------------------------------------------------------------------------------
  */
  public function get_all_change_order_request(Request $request, $project_id)
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
      $permission_key = 'cor_view_all';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
          $query = DB::table('project_change_order_request')
          ->leftJoin('project_settings', 'project_change_order_request.pco_project_id', '=', 'project_settings.pset_project_id')
          ->leftJoin('currency', 'project_settings.pset_meta_value', '=', 'currency.cur_id')
          ->leftJoin('projects', 'project_change_order_request.pco_project_id', '=', 'projects.p_id')
          ->leftJoin('users', 'project_change_order_request.pco_user_id', '=', 'users.id')
          ->select('currency.cur_symbol as currency_symbol', 'project_change_order_request.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('pco_project_id', '=', $project_id)
          ->groupBy('project_change_order_request.pco_id')
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
   Get New COR Number
  ----------------------------------------------------------------------------------
  */
  public function get_new_change_order_number(Request $request, $project_id)
  {
      $query = DB::table('project_change_order_request_detail')
      ->select()
      ->where('pcd_project_id', '=', $project_id)
      ->where('is_potential','=',0)
      ->orderBy('pcd_number', 'desc')
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
   Get Item RFI Name by passing item_id
  --------------------------------------------------------------------------
  */
  public function get_item_rfi(Request $request, $item_id)
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
          $query = DB::table('project_change_order_request_detail')
           ->select('pcd_rfi')
           ->where('pcd_id', '=', $item_id)
          ->get();

            $pro_type = array();
            // foreach ($query as $q) {
            //    $pro_type[] = $q->p_type;
            // }  
            // print_r($query[0]->p_type);
            $pro_type =  $query[0]->pcd_rfi;
            $faizan = $pro_type;
            $faizan = str_replace('"','',$pro_type);
            $faizan = str_replace('[','',$faizan);
            $faizan = str_replace(']','',$faizan);
            $faizan = explode(",",$faizan);
            // print_r($faizan);
            // exit;  

            $query2 = DB::table('project_request_info')
            ->select()
            ->whereIn('ri_id', $faizan)
            ->where('ri_request_status', '=', 'active')
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
        // }
      }
      catch(Exception $e)
      {
        return response()->json(['error' => 'Something is wrong'], 500);
      }
  }

  /*
  --------------------------------------------------------------------------
   ADD Change Order Request Item
  --------------------------------------------------------------------------
  */
  public function docusign_change_order_request_send(Request $request)
  {
    try
    {
        $order_user_id  = Auth::user()->id;
        $signatory_arr  = $request['signatory_arr'];
        $envelope_id    = '';
        $docusign_status = 'pending';
        $user_id = Auth::user()->id;
        //echo '<pre>';//print_r($request['pcd_id']);die;
        $contract_amount = $this->get_contract_amount($signatory_arr[0]['project_id']);
        $improvementType = array();
        if($signatory_arr[0]['project_id'])
            $improvementType = $this->getType($signatory_arr[0]['project_id']);
        $improvementTypes = '';
        foreach($improvementType as $row)
            $improvementTypes.=$row->pt_name;
        $netchange = $this->netChange($request['pcd_id'],$signatory_arr[0]['project_id']);
        $change_order_items = $this->contractSumIncreased($request['pcd_id'],$signatory_arr[0]['project_id']);
        //print_r($improvementTypes);die;
        if(count($signatory_arr))
          {
              $projectDetail = DB::table('projects')
                    ->leftJoin('project_firm', 'projects.p_lead_agency', '=', 'project_firm.f_id')
                    ->select('p_name','p_term','p_lead_agency','project_firm.*')
                    ->where('p_id', '=', $signatory_arr[0]['project_id'])
                    ->first();
              $contracts = DB::table('project_contract')
                    ->select('con_contract_date')
                    ->where('con_project_id', '=', $signatory_arr[0]['project_id'])
                    ->orderBy('project_contract.con_id','ASC')
                    ->first();
              if(!$contracts){
                  foreach($request['pcd_id'] as $pcd_id){
                        $cor = DB::table('project_change_order_request_detail')
                        ->select('pcd_parent_cor')
                        ->where('pcd_id', '=', $pcd_id)
                        ->first();
                        DB::table('project_change_order_request')
                        ->where('pco_id', '=', $cor->pcd_parent_cor)
                        ->delete();
                        DB::table('project_change_order_request_detail')
                        ->where('pcd_id', '=', $pcd_id)
                        ->delete();
                    }
                    $result = array('code'=>400,"data"=>array("description"=>"Please add project contract first.",'docusign'=>1,
                                        "notice_status"=>null,"contactor_name"=>null,"contact_amount"=>null,"award_date"=>null,"notice_path"=>null,"project_id"=>null));
                    return response()->json($result, 400);
                }
              if($contracts)
                $con_contract_date = date('m-d-Y',strtotime($contracts->con_contract_date));
              else
                $con_contract_date = '';
              $contractor = DB::table('project_notice_award')
                    ->leftJoin('project_firm', 'project_notice_award.pna_contactor_name', '=', 'project_firm.f_id')
                    ->select('project_notice_award.pna_contactor_name','project_firm.*')
                    ->where('project_notice_award.pna_project_id', '=', $signatory_arr[0]['project_id'])
                    ->orderBy('project_notice_award.pna_id','ASC')
                    ->first();
              if(!$contractor){
                  foreach($request['pcd_id'] as $pcd_id){
                        $cor = DB::table('project_change_order_request_detail')
                        ->select('pcd_parent_cor')
                        ->where('pcd_id', '=', $pcd_id)
                        ->first();
                        DB::table('project_change_order_request')
                        ->where('pco_id', '=', $cor->pcd_parent_cor)
                        ->delete();
                        DB::table('project_change_order_request_detail')
                        ->where('pcd_id', '=', $pcd_id)
                        ->delete();
                    }
                    $result = array('code'=>400,"data"=>array("description"=>"Please add Notice to Award first.",'docusign'=>1,
                                        "notice_status"=>null,"contactor_name"=>null,"contact_amount"=>null,"award_date"=>null,"notice_path"=>null,"project_id"=>null));
                    return response()->json($result, 400);
                }
              //$projectDetail = $project[]
                //print_r($contracts);die;
            $test = array();
            $i=0;
            $data = array();
            $formulaTabs = array();
            $numberTabs = array();
            $j=1;
            $item_total =0;
            foreach($change_order_items as $key=>$item){
                $test[++$i]['tabLabel'] = 'item'.($j);
                $test[$i]['value'] = $j;
                $test[++$i]['tabLabel'] = 'item_description'.($j);
                $test[$i]['value'] = $item->pcd_description;
                if($item->pcd_price)
                {
                    $numberTabs[$i]['tabLabel'] = 'itemqty'.($j);
                    $numberTabs[$i]['value'] = 1;
                    $numberTabs[++$i]['tabLabel'] = 'item_unit_cost'.($j);
                    $numberTabs[$i]['value'] = number_format($item->pcd_price, 2, '.', ',');
                    $formulaTabs[]=array (
                                            "tabLabel" => "total".$j,
                                            "formula" => $item->pcd_price);
                    $item_total+=$item->pcd_price;
                }else{
                    $numberTabs[++$i]['tabLabel'] = 'itemqty'.($j);
                    $numberTabs[$i]['value'] = $item->pcd_unit_number;
                    $numberTabs[++$i]['tabLabel'] = 'item_unit_cost'.($j);
                    $numberTabs[$i]['value'] = number_format($item->pcd_unit_price, 2, '.', ',');
                    $formulaTabs[]=array (
                                            "tabLabel" => "total".$j,
                                            "formula" => $item->pcd_unit_price*$item->pcd_unit_number);
                    $item_total+=$item->pcd_unit_price*$item->pcd_unit_number;
                }
                $j++;
            }
            //print_r($test);
              $i=0;
              //$key = array_search('construction_manager', array_column($signatory_arr, 'signatory_role'));
              $key = array_keys(array_combine(array_keys($signatory_arr), array_column($signatory_arr, 'signatory_role')),'construction_manager');
              //print_r($key);
              foreach($signatory_arr as $i=>$row){
                  if($row['jurisdiction']!=""){
                      if($key)
                        $templateId = "dda2bd94-6399-4e6c-ad26-ac2a381737ff";
                      else
                        $templateId = "66ca5c1a-2e42-4660-ae96-9a1a90b98644";  
                  }else{
                      if($key)
                        $templateId = "b572c993-1d13-4997-aab3-114bb59d358b";
                      else
                        $templateId = "089d01b3-3b40-4966-8cbd-db73956dc6c1";  
                  }
                  //echo $templateId;
                  $data[$i]["tabs"]["numberTabs"]=array(array (
                                                      "tabLabel" => "original_contract_sum",
                                                      "value" => number_format($contract_amount[0]->total_amount, 2, '.', ',')),
                                                    array (
                                                      "tabLabel" => "net_change",
                                                      "value" => number_format($netchange, 2, '.', ',')));
                $temp=array();
                $temp = array_merge($data[$i]["tabs"]["numberTabs"],$numberTabs);
                $data[$i]["tabs"]["numberTabs"] = $temp;
                  $data[$i]["tabs"]["formulaTabs"]=array(
                                                    array (
                                                      "tabLabel" => "item_total",
                                                      "formula" => $item_total),
                                                    array (
                                                      "tabLabel" => "contract_sum",
                                                      "formula" => $contract_amount[0]->total_amount+$netchange),
                                                    array (
                                                      "tabLabel" => "contract_sum_increased",
                                                      "formula" => $item_total),
                                                    array (
                                                      "tabLabel" => "new_contract_sum",
                                                      "formula" => $contract_amount[0]->total_amount+$netchange+$item_total));
                $temp=array();
                $temp = array_merge($data[$i]["tabs"]["formulaTabs"],$formulaTabs);
                $data[$i]["tabs"]["formulaTabs"] = $temp;
                  
                // echo '<pre>';print_r($test);die;
                  if(filter_var($row['signatory_email'], FILTER_VALIDATE_EMAIL))
                  {
                      $data[$i]["email"] = $row['signatory_email'];
                      $data[$i]["name"] = $row['signatory_name'];
                      $data[$i]["roleName"] = $row['signatory_role'];
                      $data[$i]["tabs"]["textTabs"] =
                                              array(
                                                  array(
                                                      "tabLabel" => "jurisdiction",
                                                      "value" => $row['jurisdiction']),
                                                    array (
                                                      "tabLabel" => "change_order_number",
                                                      "value" => $row['change_order_number']),
                                                    array (
                                                      "tabLabel" => "project_name",
                                                      "value" => $row['project_name']),
                                                    array (
                                                      "tabLabel" => "change_order_date",
                                                      "value" => date('m-d-Y')),
                                                    array (
                                                      "tabLabel" => "project_name",
                                                      "value" => $row['project_name']),
                                                    array (
                                                      "tabLabel" => "improvement_types",
                                                      "value" => $improvementTypes),
                                                    array (
                                                      "tabLabel" => "agreement_date",
                                                      "value" => $con_contract_date),
                                                    array (
                                                      "tabLabel" => "contractor_name",
                                                      "value" => $contractor->f_name),
                                                    array (
                                                      "tabLabel" => "contractor_address",
                                                      "value" => $contractor->f_address));
                    
                     
                                      
                  }else{
                    foreach($request['pcd_id'] as $pcd_id){
                        $cor = DB::table('project_change_order_request_detail')
                        ->select('pcd_parent_cor')
                        ->where('pcd_id', '=', $pcd_id)
                        ->first();
                        DB::table('project_change_order_request')
                        ->where('pco_id', '=', $cor->pcd_parent_cor)
                        ->delete();
                        DB::table('project_change_order_request_detail')
                        ->where('pcd_id', '=', $pcd_id)
                        ->delete();
                    }
                      $result = array('code'=>400,"data"=>array("description"=>"Signatory email is not valid.",'docusign'=>1,
                                          "notice_status"=>null,"contactor_name"=>null,"contact_amount"=>null,"award_date"=>null,"notice_path"=>null,"project_id"=>null));
                      return response()->json($result, 400);
                  }
                  $temp=array();
                  $temp = array_merge($data[$i]["tabs"]["textTabs"],$test);
                  $data[$i]["tabs"]["textTabs"] = $temp;
                  //echo '<pre>';print_r($test);die;
                  
              }
              //array_merge($data,$test);
              //echo '<pre>';print_r($data);
              if(count($data))
              {
                  $email = env('DOCUSIGN_EMAIL');
                  $password = env('DOCUSIGN_PASSWORD');
                  $integratorKey = env('DOCUSIGN_INTEGRATOR_KEY');
                  
                  $url = env('DOCUSIGN_URL');
                  $header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";
                  $curl = curl_init($url);
                  curl_setopt($curl, CURLOPT_HEADER, false);
                  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                  curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-DocuSign-Authentication: $header"));
                  $json_response = curl_exec($curl);
                  $statuscode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                  if ( $statuscode != 200 ) {
                        foreach($request['pcd_id'] as $pcd_id){
                            $cor = DB::table('project_change_order_request_detail')
                            ->select('pcd_parent_cor')
                            ->where('pcd_id', '=', $pcd_id)
                            ->first();
                            DB::table('project_change_order_request')
                            ->where('pco_id', '=', $cor->pcd_parent_cor)
                            ->delete();
                            DB::table('project_change_order_request_detail')
                            ->where('pcd_id', '=', $pcd_id)
                            ->delete();
                        }
                          $result = array('code'=>400,"data"=>array("description"=>"Error calling DocuSign, status is: " . $statuscode,'docusign'=>1,
                                              "notice_status"=>null,"contactor_name"=>null,"contact_amount"=>null,"award_date"=>null,"notice_path"=>null,"project_id"=>null));
                        return response()->json($result, 400);
                  }
                  $response = json_decode($json_response, true);
                  $accountId = $response["loginAccounts"][0]["accountId"];
                  $baseUrl = $response["loginAccounts"][0]["baseUrl"];
                  curl_close($curl);

                  $data = array("accountId" => $accountId, 
                      "emailSubject" => "Signature request for a Change Order",
                      "emailBlurb" => "This is a signature request for a Change Order",
                      "templateId" => $templateId, 
                      "templateRoles" => $data,
                      "status" => "sent");
                  $data_string = json_encode($data); 

                  // Send to the /envelopes end point, which is relative to the baseUrl received above. 
                  $curl = curl_init($baseUrl . "/envelopes" );
                  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                  curl_setopt($curl, CURLOPT_POST, TRUE);
                  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);                                                                  
                  curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
                          'Content-Type: application/json', 
                          'Content-Length: ' . strlen($data_string),
                          "X-DocuSign-Authentication: $header" )                                                                       
                  );
                  $json_response = curl_exec($curl); // Do it!
                  $statuscode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                  if ( $statuscode != 201 ) {
                            $response = json_decode($json_response, true);
                            foreach($request['pcd_id'] as $pcd_id){
                                $cor = DB::table('project_change_order_request_detail')
                                ->select('pcd_parent_cor')
                                ->where('pcd_id', '=', $pcd_id)
                                ->first();
                                DB::table('project_change_order_request')
                                ->where('pco_id', '=', $cor->pcd_parent_cor)
                                ->delete();
                                DB::table('project_change_order_request_detail')
                                ->where('pcd_id', '=', $pcd_id)
                                ->delete();
                            }
                            $result = array('code'=>400,"data"=>array("description"=>$response['message'],'docusign'=>1,
                                                  "notice_status"=>null,"contactor_name"=>null,"contact_amount"=>null,"award_date"=>null,"notice_path"=>null,"project_id"=>null));
                            return response()->json($result, 400);
                          
//                          $result = array('code'=>400,"data"=>array("description"=>$response['message'],'docusign'=>1,
//                                          "notice_status"=>null,"contactor_name"=>null,"contact_amount"=>null,"award_date"=>null,"notice_path"=>null,"project_id"=>null));
//                          return response()->json($result, 400);
                  }
                  $response = json_decode($json_response, true);
                  //print_r($data);
                  $envelope_id = $response["envelopeId"];
              }
    
            
          
          }else{
            $result = array('code'=>400, "description"=>"No records found");
            return response()->json($result, 400);
          }
        foreach ($request['pcd_id'] as $pcd_id)
        {
            $query = DB::table('project_change_order_request_detail')
            ->where('pcd_id', '=', $pcd_id)
             ->update(['envelope_id' => $envelope_id]);
            if(count($query) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {
              $result = array('description'=>"Add change order request successfully",'code'=>200);
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
   Get All Unreviewed change orders and expire if X day value cross
  --------------------------------------------------------------------------
  */
  public function update_change_order_review_status(Request $request)
  {
    try
    {
        //echo '<pre>';
        $projects = DB::table('projects')
        ->select()
        ->where('p_status', '=', 'active')
        ->get();
        foreach($projects as $project)
        {
            //$days = config('app.request_review_status_change');
            $query = DB::table('project_change_order_request_detail')
                    ->leftJoin('users', 'project_change_order_request_detail.pcd_user_id', '=', 'users.id')
                    ->select('project_change_order_request_detail.*','users.*')
                    ->where('pcd_project_id', '=', $project->p_id)
                    //->where('pcd_status', '=', 'pending')
                    //->orwhere('pcd_status', '=', 'past_due')
                    ->where(function($query){
                        $query->where('pcd_status', '=', 'pending')
                        ->orwhere('pcd_status', '=', 'past_due');
                    })
                    ->get();
            //echo '<pre>';print_r($query);die;
            $days = $project->change_order_due_date;
            $user =  (array) $query;
            if(count($query) < 1)
            {
              $result = array('code'=>404,"description"=>"No Records Found");
              //return response()->json($result, 404);
            }
            else
            {
              foreach ($query as $key => $review) {//print_r($review);die;
                $reg_time = $review->pcd_timestamp;
                $reg_date = strtotime($reg_time);
                $date = date_create($reg_time);
                date_add($date, date_interval_create_from_date_string(($days-1).'days'));
                if($project->change_order_days_type==1){
                    $plus_time = date_format($date, 'Y-m-d H:i:s');
                }else{
                    $plus_time = date ( 'Y-m-d H:i:s' , strtotime ( $reg_time.'+'.($days-1).' weekdays' ) );
                }
                $plus_date = strtotime($plus_time);
                $current_date = time();
                  if($current_date >= $plus_date){
                      $review1 = DB::table('project_change_order_request_detail')
                        ->where('pcd_id', '=', $review->pcd_id)
                        ->update(['pcd_status' => 'past_due']);
                    $notification_key     = 'past_due';
                    $check_project_user_notification = app('App\Http\Controllers\Projects\PermissionController')->check_project_user_notification($project->p_id,$review->pcd_user_id,$notification_key);
                    if(count($check_project_user_notification) < 1){
                        continue;
                    }else{
                        $project_id           = $project->p_id;
                        $notification_title   = 'Change order request # '.$review->pcd_number.' is overdue in Project: ' .$project->p_name;
                        $url                  = App::make('url')->to('/');
                        $link                 = "/dashboard/".$project->p_id."/change_order_request";
                        $date                 = date("M d, Y h:i a");
                        $email_description    = 'Change order request # '.$review->pcd_number.' is overdue in Project: <strong>'.$project->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
                        $user_detail = array(
                            'id'              => $review->id,
                            'name'            => $review->username,
                            'email'           => $review->email,
                            'link'            => $link,
                            'date'            => $date,
                            'project_name'    => $project->p_name,
                            'title'           => $notification_title,
                            'description'     => $email_description
                        );
                        $user_single = (object) $user_detail;
                        Mail::send('emails.send_notification',['user' => $user_single], function ($message) use ($user_single) {
                            $message->from('no-reply@sw.ai', 'StratusCM');
                            $message->to($user_single->email, $user_single->name)->subject($user_single->title);
                        });
                        //$result = array('description'=>'Update Status successfully','code'=>200);
                    }
                    //return response()->json($result, 200);
                  }
                  else {
                    $result = array('code'=>404, "description"=>"No Records Found");
                    //return response()->json($result, 404);
                  }
                }
            }
        }
        $result = array('description'=>'Change order status updated successfully','code'=>200);
        return response()->json($result, 200);
    }
        catch(Exception $e)
        {
          return response()->json(['error' => 'Something is wrong'], 500);
        }
  }
  
    public function check_reviewer_permission(Request $request,$project_id,$item_id,$type,$designation)
    {
        //DB::enableQueryLog();
        
        $email = Auth::user()->email;
        $query = DB::table('project_reviewer')
                ->select()
                ->where('email', '=', $email)
                ->where('project_id', '=', $project_id)
                ->where('type', '=', $type)
                ->where('doc_id', '=', $item_id)
                ->where('designation', '=', $designation)
                 ->first();
        //dd(DB::getQueryLog());
        if(count($query) < 1)
        {
          $result = array('code'=>400, "description"=>"No records found");
          return response()->json($result, 400);
        }
        else
        {
          $result = array('description'=>"true",'code'=>200);
          return response()->json($result, 200);
        }
    }    
}