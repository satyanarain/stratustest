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


class ContractController extends Controller {

  /*
  --------------------------------------------------------------------------
   Add Contract
  --------------------------------------------------------------------------
  */
  public function add_contract(Request $request)
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
        $contract_status     = $request['contract_status'];
        $contractor_name     = $request['contractor_name'];
        // $contract_currency   = $request['contract_currency'];
        $contract_amount     = $request['contract_amount'];
        $contract_date       = $request['contract_date'];
        $contract_path       = $request['contract_path'];
        $contract_sign       = $request['contract_sign'];
        $project_id          = $request['project_id'];
        $user_id             = Auth::user()->id;
        $status              = 'active';
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'contract_add';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            "contract_status"     => $contract_status,
            "contract"            => $contract_path,
            "project_id"          => $project_id,
            "user_id"             => $user_id,
            "status"              => $status
        );

        $rules = [
            'contract_status'     => 'required',
            'contract'            => 'required',
            'project_id'          => 'required|numeric',
            'user_id'             => 'required|numeric',
            'status'              => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_contract')
            // ->insert(['con_contract_status' => $contract_status, 'con_contractor_name' => $contractor_name, 'con_contract_currency' => $contract_currency, 'con_contract_amount' => $contract_amount, 'con_contract_date' => $contract_date, 'con_contract_path' => $contract_path, 'con_contract_sign' => $contract_sign, 'con_project_id' => $project_id, 'con_user_id' => $user_id, 'con_status' => $status]);
            ->insert(['con_contract_status' => $contract_status, 'con_contractor_name' => $contractor_name, 'con_contract_amount' => $contract_amount, 'con_contract_date' => $contract_date, 'con_contract_path' => $contract_path, 'con_project_id' => $project_id, 'con_user_id' => $user_id, 'con_status' => $status]);

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
                $permission_key       = 'contract_view_all';
                // Notification Parameter
                $project_id           = $project_id;
                $notification_title   = 'New contract uploaded in Project: ' .$check_project_user->p_name;
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/contract";
                $date                 = date("M d, Y h:i a");
                $email_description    = 'A new contract has been uploaded in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
                if(count($check_single_user_permission) < 1){
                  continue;
                }
                else {
                    $notification_key     = 'contract_upload';
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

              $result = array('description'=>"Contract added successfully",'code'=>200);
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
   Update Contract by passing con_id
  --------------------------------------------------------------------------
  */
  public function update_contract(Request $request, $con_id)
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
        // $contract_status     = $request['contract_status'];
        // $contractor_name     = $request['contractor_name'];
        // $contract_currency   = $request['contract_currency'];
        // $contract_amount     = $request['contract_amount'];
        // $contract_date       = $request['contract_date'];
        // $contract_path       = $request['contract_path'];
        // $contract_sign       = $request['contract_sign'];
        $project_id          = $request['project_id'];
        $user_id             = Auth::user()->id;
        $status              = $request['status'];
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'contract_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            // "contract_status"     => $contract_status,
            "project_id"          => $project_id,
            "user_id"             => $user_id,
            "status"              => $status
        );

        $rules = [
            // 'contract_status'     => 'required',
            'project_id'          => 'required|numeric',
            'user_id'             => 'required|numeric',
            'status'              => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_contract')
            ->where('con_id', '=', $con_id)
            // ->update(['con_contract_status' => $contract_status, 'con_contractor_name' => $contractor_name, 'con_contract_currency' => $contract_currency, 'con_contract_amount' => $contract_amount, 'con_contract_date' => $contract_date, 'con_contract_path' => $contract_path, 'con_contract_sign' => $contract_sign, 'con_project_id' => $project_id, 'con_user_id' => $user_id, 'con_status' => $status]);
            ->update(['con_project_id' => $project_id, 'con_user_id' => $user_id, 'con_status' => $status]);
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
                $permission_key       = 'contract_view_all';
                // Notification Parameter
                $project_id           = $project_id;
                $notification_title   = 'Updated contract # '.$con_id.' in Project: ' .$check_project_user->p_name;
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/contract";
                $date                 = date("M d, Y h:i a");
                $email_description    = 'Contract # '.$con_id.' has been updated in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
                if(count($check_single_user_permission) < 1){
                  continue;
                }
                else {
                    $notification_key     = 'contract_upload';
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
   Get single Contract by passing con_id
  --------------------------------------------------------------------------
  */
  public function get_contract_single(Request $request, $con_id)
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
        $query = DB::table('project_contract')
->leftJoin('currency as contract_currency', 'project_contract.con_contract_currency', '=', 'contract_currency.cur_id')
->leftJoin('documents as contract_path', 'project_contract.con_contract_path', '=', 'contract_path.doc_id')
->leftJoin('projects', 'project_contract.con_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_contract.con_user_id', '=', 'users.id')
        ->select('contract_currency.cur_symbol as contract_currency', 
          'contract_path.doc_path as contract_path', 
          'project_contract.*', 'projects.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('con_id', '=', $con_id)
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
   Get all Contract by passing Project ID
  ----------------------------------------------------------------------------------
  */
  public function get_contract_project(Request $request, $project_id)
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
      $permission_key = 'contract_view_all';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
          $query = DB::table('project_contract')
->leftJoin('project_firm', 'project_contract.con_contractor_name', '=', 'project_firm.f_id')
// ->leftJoin('currency as contract_currency', 'project_contract.con_contract_currency', '=', 'contract_currency.cur_id')
->leftJoin('project_settings', 'project_contract.con_project_id', '=', 'project_settings.pset_project_id')
->leftJoin('currency', 'project_settings.pset_meta_value', '=', 'currency.cur_id')
->leftJoin('documents as contract_path', 'project_contract.con_contract_path', '=', 'contract_path.doc_id')
->leftJoin('projects', 'project_contract.con_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_contract.con_user_id', '=', 'users.id')
        ->select('currency.cur_symbol as currency_symbol', 
          'contract_path.doc_path as contract_path', 
          'project_contract.*', 'project_firm.*', 'projects.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('con_project_id', '=', $project_id)
          ->groupBy('project_contract.con_id')
          ->orderBy('project_contract.con_id','ASC')
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