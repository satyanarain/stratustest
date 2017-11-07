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


class ContractitemController extends Controller {

     /*
  --------------------------------------------------------------------------
   ADD BID ITEMS
  --------------------------------------------------------------------------
  */
  public function add_bid_items(Request $request)
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
        $item_description       = $request['item_description'];
        $item_unit              = $request['item_unit'];
        $item_qty               = $request['item_qty'];
        $item_unit_other        = $request['item_unit_other'];
        $item_unit_price        = $request['item_unit_price'];
        $project_id             = $request['project_id'];
        $user_id                = Auth::user()->id;
        $status                 = 'active';
    // Check User Permission Parameter 
    $user_id = Auth::user()->id;
    $permission_key = 'contract_item_add';
    $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
    if(count($check_single_user_permission) < 1){
      $result = array('code'=>403, "description"=>"Access Denies");
      return response()->json($result, 403);
    }
    else {
        $information = array(
            "item_description"    => $item_description,
            "item_unit"           => $item_unit,
            "item_qty"            => $item_qty,
            "item_unit_other"     => $item_unit_other,
            "item_unit_price"     => $item_unit_price,
            "project_id"          => $project_id,
            "user_id"             => $user_id,
            "status"              => $status
        );

        $rules = [
            'item_description'      => 'required',
            'item_unit'             => 'required',
            'item_qty'              => 'required',
            'item_unit_price'       => 'required',
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
            $item_total_price = $item_qty * $item_unit_price;
            $query = DB::table('project_bid_items')
            ->insert(['pbi_item_description' => $item_description, 'pbi_item_unit' => $item_unit, 'pbi_item_unit_other' => $item_unit_other, 'pbi_item_qty' => $item_qty, 'pbi_item_unit_price' => $item_unit_price, 'pbi_item_total_price' =>$item_total_price, 'pbi_project_id' => $project_id, 'pbi_user_id' => $user_id, 'pbi_status' => $status]);

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
                $permission_key       = 'contract_item_view_all';
                // Notification Parameter
                $project_id           = $project_id;
                //$notification_title   = 'Add new contract item # in Project: ' .$check_project_user->p_name;
                $notification_title   = 'New contract item # added in Project.';
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/contract_item";
                $date                 = date("M d, Y h:i a");
                $email_description    = 'Add new contract item # in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

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

              $result = array('description'=>"Contract item added successfully",'code'=>200);
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
   Update BID ITEM by passing pbi_id
  --------------------------------------------------------------------------
  */
  public function update_bid_items(Request $request, $pbi_id)
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
        $item_description       = $request['item_description'];
        $item_unit              = $request['item_unit'];
        $item_unit_other        = $request['item_unit_other'];
        $item_qty               = $request['item_qty'];
        $item_unit_price        = $request['item_unit_price'];
        $project_id             = $request['project_id'];
        $user_id                = Auth::user()->id;
        $status                 = $request['status'];
    // Check User Permission Parameter 
    $user_id = Auth::user()->id;
    $permission_key = 'contract_item_update';
    $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
    if(count($check_single_user_permission) < 1){
      $result = array('code'=>403, "description"=>"Access Denies");
      return response()->json($result, 403);
    }
    else { 
        $information = array(
            "item_description"    => $item_description,
            "item_unit"           => $item_unit,
            "item_unit_other"     => $item_unit_other,
            "item_qty"            => $item_qty,
            "item_unit_price"     => $item_unit_price,
            "project_id"          => $project_id,
            "user_id"             => $user_id,
            "status"              => $status
        );

        $rules = [
            'item_description'      => 'required',
            'item_unit'             => 'required',
            'item_qty'              => 'required',
            'item_unit_price'       => 'required',
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
            $item_total_price = $item_qty * $item_unit_price;
            $query = DB::table('project_bid_items')
            ->where('pbi_id', '=', $pbi_id)
            ->update(['pbi_item_description' => $item_description, 'pbi_item_unit' => $item_unit, 'pbi_item_unit_other' => $item_unit_other, 'pbi_item_qty' => $item_qty, 'pbi_item_unit_price' => $item_unit_price, 'pbi_item_total_price' =>$item_total_price, 'pbi_project_id' => $project_id, 'pbi_user_id' => $user_id, 'pbi_status' => $status]);
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
//                $permission_key       = 'contract_item_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Update contract item status in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/contract_item/";
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Update contract item status # '.$pbi_id.' in Project : <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
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
   Get single Bid Items by passing pbi_id
  --------------------------------------------------------------------------
  */
  public function get_bid_items_single(Request $request, $pbi_id)
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
        $query = DB::table('project_bid_items')
->leftJoin('projects', 'project_bid_items.pbi_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_bid_items.pbi_user_id', '=', 'users.id')
        ->select('project_bid_items.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('pbi_id', '=', $pbi_id)
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
   Get all Bid Items by passing project_id
  ----------------------------------------------------------------------------------
  */
  public function get_bid_items_project(Request $request, $project_id)
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
      $permission_key = 'contract_item_view_all';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else { 
          $query = DB::table('project_bid_items')
->leftJoin('project_settings', 'project_bid_items.pbi_project_id', '=', 'project_settings.pset_project_id')
->leftJoin('currency', 'project_settings.pset_meta_value', '=', 'currency.cur_id')
->leftJoin('projects', 'project_bid_items.pbi_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_bid_items.pbi_user_id', '=', 'users.id')
        ->select('currency.cur_symbol as currency_symbol', 'project_bid_items.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('pbi_project_id', '=', $project_id)
          ->groupBy('project_bid_items.pbi_id')
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
   Get all Bid Items by passing project_id
  ----------------------------------------------------------------------------------
  */
  public function get_total_amount_project(Request $request, $project_id)
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
          $query = DB::table('project_bid_items')
          ->select(DB::raw('SUM(pbi_item_total_price) as total_amount, COUNT(pbi_id) as total_item'))
          ->where('pbi_project_id', '=', $project_id)
          ->where('pbi_status', '=', 'active')
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
        // }
      }
      catch(Exception $e)
      {
        return response()->json(['error' => 'Something is wrong'], 500);
      }
  }


  /*
  --------------------------------------------------------------------------
   ADD BID ITEMS QUANTITY
  --------------------------------------------------------------------------
  */
  public function add_bid_items_qty(Request $request, $project_id)
  {
    try
    {
        $item_qty               = $request['item_qty'];
        $project_id             = $request['project_id'];
        $user_id                = Auth::user()->id;
        $status                 = 'active';
    // Check User Permission Parameter 
    $user_id = Auth::user()->id;
    $permission_key = 'contract_item_view_all';
    $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
    if(count($check_single_user_permission) < 1){
      $result = array('code'=>403, "description"=>"Access Denies");
      return response()->json($result, 403);
    }
    else {
        $information = array(
            "item_qty"            => $item_qty,
            "project_id"          => $project_id,
            "user_id"             => $user_id,
            "status"              => $status
        );

        $rules = [
            'item_qty'              => 'required',
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
            $query = DB::table('project_bid_items_qty')
            ->select('piq_project_id')
            ->where('piq_project_id', '=', $project_id)
            ->first();
            // print_r($query);
            if(count($query) < 1){
              $query_new = DB::table('project_bid_items_qty')
              ->insert(['piq_qty' => $item_qty, 'piq_project_id' => $project_id, 'piq_user_id' => $user_id]);
              if(count($query_new) < 1)
              {
                $result = array('code'=>400, "description"=>"No records found");
                return response()->json($result, 400);
              }
              else
              {
                $result = array('description'=>"Contract item quantity added successfully",'code'=>200);
                return response()->json($result, 200);
              }
            }
            else{
              $query_new = DB::table('project_bid_items_qty')
              ->where('piq_project_id', '=', $project_id)
              ->update(['piq_qty' => $item_qty, 'piq_user_id' => $user_id]);
              if(count($query_new) < 1)
              {
                $result = array('code'=>400, "description"=>"No records found");
                return response()->json($result, 400);
              }
              else
              {
                $result = array('description'=>"Contract item quantity updated successfully",'code'=>200);
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
   GET BID ITEMS QUANTITY
  --------------------------------------------------------------------------
  */
  public function get_bid_items_qty(Request $request, $project_id)
  {
    try
    {
        $project_id             = $project_id;
        $user_id                = Auth::user()->id;
        $status                 = 'active';

        $information = array(
            "project_id"          => $project_id,
            "user_id"             => $user_id,
            "status"              => $status
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
          $query = DB::table('project_bid_items_qty')
          ->select('piq_qty')
          ->where('piq_project_id', '=', $project_id)
          ->first();

          if(count($query) < 1){
            $result = array('code'=>400, "description"=>"0");
            return response()->json($result, 400);
          }
          else{
            $result = array('description'=>$query,'code'=>200);
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