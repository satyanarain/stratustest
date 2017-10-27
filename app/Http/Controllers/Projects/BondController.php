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


class BondController extends Controller {

  /*
  --------------------------------------------------------------------------
   Add Contract Bond
  --------------------------------------------------------------------------
  */
  public function add_bond(Request $request)
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
        $firm_name                      = $request['firm_name'];
        $performance_bond_currency      = $request['performance_bond_currency'];
        $performance_bond_amount        = $request['performance_bond_amount'];
        $performance_bond_date          = $request['performance_bond_date'];
        $performance_bond_number        = $request['performance_bond_number'];
        $performance_bond_sign          = $request['performance_bond_sign'];
        $performance_bond_path          = $request['performance_bond_path'];
        $payment_bond_currency          = $request['payment_bond_currency'];
        $payment_bond_amount            = $request['payment_bond_amount'];
        $payment_bond_date              = $request['payment_bond_date'];
        $payment_bond_number            = $request['payment_bond_number'];
        $payment_bond_sign              = $request['payment_bond_sign'];
        $payment_bond_path              = $request['payment_bond_path'];
        $maintenance_bond_currency      = $request['maintenance_bond_currency'];
        $maintenance_bond_amount        = $request['maintenance_bond_amount'];
        $maintenance_bond_date          = $request['maintenance_bond_date'];
        $maintenance_bond_number        = $request['maintenance_bond_number'];
        $maintenance_bond_sign          = $request['maintenance_bond_sign'];
        $maintenance_bond_path          = $request['maintenance_bond_path'];
        $project_id                     = $request['project_id'];
        $user_id                        = Auth::user()->id;
        $status                         = 'active';
        // Check User Permission Parameter 
        $user_id = Auth::user()->id;
        $permission_key = 'bond_add';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
        $information = array(
            "firm_name"             => $firm_name,
            "project_id"            => $project_id,
            "user_id"               => $user_id,
            "status"                => $status
        );

        $rules = [
            'firm_name'             => 'required|numeric',
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
            $query = DB::table('project_bond')
            ->insert(['pb_firm_name' => $firm_name, 'pb_performance_bond_currency' => $performance_bond_currency, 'pb_performance_bond_amount' => $performance_bond_amount, 'pb_performance_bond_date' => $performance_bond_date, 'pb_performance_bond_number' => $performance_bond_number, 'pb_performance_bond_sign' => $performance_bond_sign, 'pb_performance_bond_path' => $performance_bond_path, 'pb_payment_bond_currency' => $payment_bond_currency, 'pb_payment_bond_amount' => $payment_bond_amount, 'pb_payment_bond_date' => $payment_bond_date, 'pb_payment_bond_number' => $payment_bond_number, 'pb_payment_bond_sign' => $payment_bond_sign,  'pb_payment_bond_path' => $payment_bond_path,  'pb_maintenance_bond_currency' => $maintenance_bond_currency, 'pb_maintenance_bond_amount' => $maintenance_bond_amount, 'pb_maintenance_bond_date' => $maintenance_bond_date, 'pb_maintenance_bond_number' => $maintenance_bond_number, 'pb_maintenance_bond_sign' => $maintenance_bond_sign, 'pb_maintenance_bond_path' => $maintenance_bond_path,'pb_project_id' => $project_id, 'pb_user_id' => $user_id, 'pb_status' => $status]);

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
                $permission_key       = 'bond_view_all';
                // Notification Parameter
                $project_id           = $project_id;
                $notification_title   = 'Add new bond in Project: ' .$check_project_user->p_name;
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/bond";
                $date                 = date("M d, Y h:i a");
                $email_description    = 'Add new bond in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

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

              $result = array('description'=>"Add bond successfully",'code'=>200);
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
   Update Bond by passing pb_id
  --------------------------------------------------------------------------
  */
  public function update_bond(Request $request, $pb_id)
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
        // $performance_bond_currency      = $request['performance_bond_currency'];
        // $performance_bond_amount        = $request['performance_bond_amount'];
        // $performance_bond_date          = $request['performance_bond_date'];
        // $performance_bond_number        = $request['performance_bond_number'];
        // $performance_bond_sign          = $request['performance_bond_sign'];
        // $performance_bond_path          = $request['performance_bond_path'];
        // $payment_bond_currency          = $request['payment_bond_currency'];
        // $payment_bond_amount            = $request['payment_bond_amount'];
        // $payment_bond_date              = $request['payment_bond_date'];
        // $payment_bond_number            = $request['payment_bond_number'];
        // $payment_bond_sign              = $request['payment_bond_sign'];
        // $payment_bond_path              = $request['payment_bond_path'];
        // $maintenance_bond_currency      = $request['maintenance_bond_currency'];
        // $maintenance_bond_amount        = $request['maintenance_bond_amount'];
        // $maintenance_bond_date          = $request['maintenance_bond_date'];
        // $maintenance_bond_number        = $request['maintenance_bond_number'];
        // $maintenance_bond_sign          = $request['maintenance_bond_sign'];
        // $maintenance_bond_path          = $request['maintenance_bond_path'];
        $project_id                     = $request['project_id'];
        $user_id                        = Auth::user()->id;
        $status                         = $request['status'];
        // Check User Permission Parameter 
        $user_id = Auth::user()->id;
        $permission_key = 'bond_update';
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
            $query = DB::table('project_bond')
            ->where('pb_id', '=', $pb_id)
            // ->update(['pb_performance_bond_currency' => $performance_bond_currency, 'pb_performance_bond_amount' => $performance_bond_amount, 'pb_performance_bond_date' => $performance_bond_date, 'pb_performance_bond_number' => $performance_bond_number, 'pb_performance_bond_sign' => $performance_bond_sign, 'pb_performance_bond_path' => $performance_bond_path, 'pb_payment_bond_currency' => $payment_bond_currency, 'pb_payment_bond_amount' => $payment_bond_amount, 'pb_payment_bond_date' => $payment_bond_date, 'pb_payment_bond_number' => $payment_bond_number, 'pb_payment_bond_sign' => $payment_bond_sign,  'pb_payment_bond_path' => $payment_bond_path,  'pb_maintenance_bond_currency' => $maintenance_bond_currency, 'pb_maintenance_bond_amount' => $maintenance_bond_amount, 'pb_maintenance_bond_date' => $maintenance_bond_date, 'pb_maintenance_bond_number' => $maintenance_bond_number, 'pb_maintenance_bond_sign' => $maintenance_bond_sign, 'pb_maintenance_bond_path' => $maintenance_bond_path,'pb_project_id' => $project_id, 'pb_user_id' => $user_id, 'pb_status' => $status]);
            ->update(['pb_project_id' => $project_id, 'pb_user_id' => $user_id, 'pb_status' => $status]);
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
                $permission_key       = 'bond_view_all';
                // Notification Parameter
                $project_id           = $project_id;
                $notification_title   = 'Update bond status in Project: ' .$check_project_user->p_name;
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/bond/";
                $date                 = date("M d, Y h:i a");
                $email_description    = 'Update status bond # '.$pb_id.' in Project : <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

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
   Get single Bond by passing pb_id
  --------------------------------------------------------------------------
  */
  public function get_bond_single(Request $request, $pb_id)
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
        $query = DB::table('project_bond')
->leftJoin('project_firm', 'project_bond.pb_firm_name', '=', 'project_firm.f_id')
->leftJoin('currency as performance_bond_currency', 'project_bond.pb_performance_bond_currency', '=', 'performance_bond_currency.cur_id')
->leftJoin('currency as payment_bond_currency', 'project_bond.pb_payment_bond_currency', '=', 'payment_bond_currency.cur_id')
->leftJoin('currency as maintenance_bond_currency', 'project_bond.pb_maintenance_bond_currency', '=', 'maintenance_bond_currency.cur_id')
->leftJoin('documents as performance_bond_path', 'project_bond.pb_performance_bond_path', '=', 'performance_bond_path.doc_id')
->leftJoin('documents as payment_bond_path', 'project_bond.pb_payment_bond_path', '=', 'payment_bond_path.doc_id')
->leftJoin('documents as maintenance_bond_path', 'project_bond.pb_maintenance_bond_path', '=', 'maintenance_bond_path.doc_id')
->leftJoin('projects', 'project_bond.pb_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_bond.pb_user_id', '=', 'users.id')
        ->select('project_firm.f_name as agency_name', 'project_bond.pb_id', 
          'performance_bond_currency.cur_symbol as performance_bond_currency', 
          'project_bond.pb_performance_bond_amount as performance_bond_amount', 
          'project_bond.pb_performance_bond_date as performance_bond_date', 
          'project_bond.pb_performance_bond_number as performance_bond_number', 
          'project_bond.pb_performance_bond_sign as performance_bond_sign', 
          'performance_bond_path.doc_path as performance_bond_path',
          'payment_bond_currency.cur_symbol as payment_bond_currency', 
          'project_bond.pb_payment_bond_amount as payment_bond_amount', 
          'project_bond.pb_payment_bond_date as payment_bond_date', 
          'project_bond.pb_payment_bond_number as payment_bond_number', 
          'project_bond.pb_payment_bond_sign as payment_bond_sign', 
          'payment_bond_path.doc_path as payment_bond_path', 
          'maintenance_bond_currency.cur_symbol as maintenance_bond_currency', 
          'project_bond.pb_maintenance_bond_amount as maintenance_bond_amount', 
          'project_bond.pb_maintenance_bond_date as maintenance_bond_date', 
          'project_bond.pb_maintenance_bond_number as maintenance_bond_number', 
          'project_bond.pb_maintenance_bond_sign as maintenance_bond_sign', 
          'maintenance_bond_path.doc_path as maintenance_bond_path', 
          'projects.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role',
          'project_bond.pb_status as status', 
          'project_bond.pb_timestamp as timestamp')
        ->where('pb_id', '=', $pb_id)
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
   Get all Bond by passing Project ID
  ----------------------------------------------------------------------------------
  */
  public function get_bond_project(Request $request, $project_id)
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
        $permission_key = 'bond_view_all';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
         $query = DB::table('project_bond')
->leftJoin('project_firm', 'project_bond.pb_firm_name', '=', 'project_firm.f_id')
->leftJoin('currency as performance_bond_currency', 'project_bond.pb_performance_bond_currency', '=', 'performance_bond_currency.cur_id')
->leftJoin('currency as payment_bond_currency', 'project_bond.pb_payment_bond_currency', '=', 'payment_bond_currency.cur_id')
->leftJoin('currency as maintenance_bond_currency', 'project_bond.pb_maintenance_bond_currency', '=', 'maintenance_bond_currency.cur_id')
->leftJoin('documents as performance_bond_path', 'project_bond.pb_performance_bond_path', '=', 'performance_bond_path.doc_id')
->leftJoin('documents as payment_bond_path', 'project_bond.pb_payment_bond_path', '=', 'payment_bond_path.doc_id')
->leftJoin('documents as maintenance_bond_path', 'project_bond.pb_maintenance_bond_path', '=', 'maintenance_bond_path.doc_id')
->leftJoin('projects', 'project_bond.pb_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_bond.pb_user_id', '=', 'users.id')
        ->select('project_firm.f_name as agency_name', 'project_bond.pb_id',
          'performance_bond_currency.cur_symbol as performance_bond_currency', 
          'project_bond.pb_performance_bond_amount as performance_bond_amount', 
          'project_bond.pb_performance_bond_date as performance_bond_date', 
          'project_bond.pb_performance_bond_number as performance_bond_number', 
          'project_bond.pb_performance_bond_sign as performance_bond_sign', 
          'performance_bond_path.doc_path as performance_bond_path',
          'payment_bond_currency.cur_symbol as payment_bond_currency', 
          'project_bond.pb_payment_bond_amount as payment_bond_amount', 
          'project_bond.pb_payment_bond_date as payment_bond_date', 
          'project_bond.pb_payment_bond_number as payment_bond_number', 
          'project_bond.pb_payment_bond_sign as payment_bond_sign', 
          'payment_bond_path.doc_path as payment_bond_path', 
          'maintenance_bond_currency.cur_symbol as maintenance_bond_currency', 
          'project_bond.pb_maintenance_bond_amount as maintenance_bond_amount', 
          'project_bond.pb_maintenance_bond_date as maintenance_bond_date', 
          'project_bond.pb_maintenance_bond_number as maintenance_bond_number', 
          'project_bond.pb_maintenance_bond_sign as maintenance_bond_sign', 
          'maintenance_bond_path.doc_path as maintenance_bond_path', 
          'projects.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role',
          'project_bond.pb_status as status', 
          'project_bond.pb_timestamp as timestamp')
          ->where('pb_project_id', '=', $project_id)
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