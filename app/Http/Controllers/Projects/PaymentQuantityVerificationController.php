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
use App\ProjectPaymentQuantityVerification; //your model
use App\ProjectPaymentApplication; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post; 
use App\Repositories\Util\AclPolicy;

 
class PaymentQuantityVerificationController extends Controller {
  
  /* 
  --------------------------------------------------------------------------
   Payment Quantity Verification CRON REPORT GENERATE
  --------------------------------------------------------------------------
  */
  public function payment_quantity_verification_report_generate(Request $request)
  {
    try
    {
      $query = DB::table('projects')
      ->select()
      ->where('p_status', '=', 'active')
      ->where('p_status', '!=', 'completed')
      ->get();
      if(count($query) < 1)
      {
        $result = array('code'=>404, "description"=>"No Records Found");
        return response()->json($result, 404);
      }
      else
      {
        foreach ($query as $project) {
          // $month_name    = date('F-Y');
          $month_name         = date('Y-m');
          $start_month_date   = date('Y-m').'-01';
          $end_month_date     = date('Y-m').'-31'; 
          // $month_name         = '2017-01';
          // $start_month_date   = '2017-01-01';
          // $end_month_date     = '2017-01-31'; 
          // echo ' - ';
          $project_id    = $project->p_id;
          // echo '<br/>';

          $query = ProjectPaymentQuantityVerification::create(['ppq_month_name' => $month_name, 'ppq_project_id' => $project_id,'approval_status'=>'Pending']);
          $item_inserted_id = $query->id;

          //$query_project = ProjectPaymentApplication::create(['ppa_month_name' => $month_name, 'ppa_project_id' => $project_id]);
          //$payment_application_inserted_id = $query_project->id;

          // GET DAILY QUANTITY TOTAL
          $fetch_daily_quantity = DB::table('project_daily_quantity_completed')
          ->select('*', DB::raw('SUM(pdq_qty_complete_this_day) as total_quantity_use'))
          ->where('pdq_project_id', '=', $project_id)
          ->whereBetween('pdq_timestamp', [$start_month_date, $end_month_date])
          ->groupBy('pdq_item_id')
          ->get();
          if(count($fetch_daily_quantity) < 1)
          {
            // $result = array('code'=>404, "description"=>"No Records Found");
            // return response()->json($result, 404);
          }
          else
          {
            // $result = array('data'=>$fetch_daily_quantity,'code'=>200);
            // return response()->json($result, 200);
            foreach ($fetch_daily_quantity as $daily_quantity) {
              // GET PREVIOUS QUANTITY 
              $item_id = $daily_quantity->pdq_item_id;
              $month_qty = $daily_quantity->total_quantity_use;
              // echo ' Item Id: ';
              // print_r($item_id);
              // echo ' Monthly Qty: ';
              // print_r($month_qty);
              $fetch_previous_quantity = DB::table('project_payment_quantity_verification_detail')
              ->select('pqv_latest_qty')
              ->where('pqv_project_id', '=', $project_id)
              // ->where('pqv_report_id', '=', $item_inserted_id)
              ->where('pqv_item_id', '=', $item_id)
              ->get();
              $check_empty = array_filter($fetch_previous_quantity);
              if(empty($check_empty))
              {
                $previous_quantity = 0;
                // print_r($previous_quantity);
              }
              else
              {
                $previous_quantity = $fetch_previous_quantity[0]->pqv_latest_qty;
                // print_r($previous_quantity);
              }
              // echo ' Previous Qty: ';
              // print_r($previous_quantity);
              $latest_qty = $previous_quantity + $month_qty;
              // echo ' Latest Qty: ';
              // print_r($latest_qty);
            //   $result = array('data'=>$daily_quantity,'code'=>200);
            // return response()->json($result, 200);


              $add_project = DB::table('project_payment_quantity_verification_detail')
              ->insert(['pqv_item_id' => $item_id, 'pqv_previous_qty' => $previous_quantity, 'pqv_month_qty' => $month_qty, 'pqv_latest_qty' => $latest_qty, 'pqv_report_id' => $item_inserted_id, 'pqv_project_id' => $project_id]);

              //$add_project_payment_application = DB::table('project_payment_application_detail')
              //->insert(['ppd_item_id' => $item_id, 'ppd_previous_qty' => $previous_quantity, 'ppd_month_qty' => $month_qty, 'ppd_report_id' => $payment_application_inserted_id, 'ppd_project_id' => $project_id]);

              if(count($add_project) < 1)
              {
                $result = array('code'=>400, "description"=>"No records found");
                return response()->json($result, 400);
              }
              else
              {

                // // Start Payment Quantity Verification Automation Process
                // $query = DB::table('users')
                // ->leftJoin('project_contact', 'users.id', '=', 'project_contact.c_user_id')
                // ->leftJoin('projects', 'project_contact.c_project_id', '=', 'projects.p_id')
                // ->select('projects.*', 'users.id', 'users.email', 'users.username', 'users.first_name', 'users.last_name', 'users.phone_number', 'users.status', 'users.position_title', 'users.role')
                // ->where('project_contact.c_project_id', '=', $project_id)
                // ->whereIn('users.role', ['manager', 'contractor', 'engineer', 'admin', 'owner', 'surveyor'])
                // ->where('users.status', '!=', 2)
                // ->get();

                // $project_id           = $project_id;
                // $description          = "Payment quantity verification monthly report ".$month_name." has been generated";
                // $link                 = "/dashboard/".$project_id."/payment_quantity_verification/".$item_inserted_id;
                // $notification_status  = '1';
                // $sender_user_id       = '1';// Auth::user()->id;

                // foreach ($query as $user) {
                //     // print_r($user);
                //     $query = DB::table('project_notifications')
                //     ->insert(['pn_description' => $description, 'pn_link' => $link, 'pn_status' => $notification_status, 'pn_project_id' => $project_id, 'pn_sender_user_id' => $sender_user_id, 'pn_receiver_user_id' => $user->id]);

                //     $user_detail = array(
                //       'id'              => $user->id,
                //       'name'            => $user->username,
                //       'email'           => $user->email,
                //       'link'            => $link,
                //       'project_name'    => $user->p_name,
                //       'month_name'      => $month_name
                //     );
                //     $user_single = (object) $user_detail;
                //     Mail::send('emails.payment_quantity_verification_report',['user' => $user_single], function ($message) use ($user_single) {
                //         $message->from('no-reply@sw.ai', 'StratusCM');
                //         $message->to($user_single->email, $user_single->name)->subject('Payment quantity verification monthly report has been generated');
                //     });
                // }
                // // End Payment Quantity Verification Automation Process

            // Start Payment Quantity Verification Automation Process
             $check_project_users = app('App\Http\Controllers\Projects\PermissionController')->check_project_user($project_id);

             // Check User Project Permission  
            foreach ($check_project_users as $check_project_user) {
              // Check User Permission Parameter 
              $user_id              = $check_project_user->id;
              $permission_key       = 'payment_quantity_verification_view_all';
              // Notification Parameter
              $project_id           = $project_id;
              $notification_title   = "Payment quantity verification monthly report ".$month_name." has been generated in Project: " .$check_project_user->p_name;
              $url                  = App::make('url')->to('/');
              $link                 = "/dashboard/".$project_id."/payment_quantity_verification/".$item_inserted_id;
              $date                 = date("M d, Y h:i a");
              $email_description    = 'A payment quantity verification monthly report '.$month_name.' has been generated in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

              $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
              if(count($check_single_user_permission) < 1){
                continue;
              }
              else {
                    $notification_key     = 'pay_applications';
                    $check_project_user_notification = app('App\Http\Controllers\Projects\PermissionController')->check_project_user_notification($project_id,$user_id,$notification_key);
                    if(count($check_project_user_notification) < 1){
                      continue;
                    }else{
                        // Send Notification to users
                        $notification_status  = '1';
                        $sender_user_id       = '1';// Auth::user()->id;

                        $query = DB::table('project_notifications')
                        ->insert(['pn_description' => $notification_title, 'pn_link' => $link, 'pn_status' => $notification_status, 'pn_project_id' => $project_id, 'pn_sender_user_id' => $sender_user_id, 'pn_receiver_user_id' => $check_single_user_permission[0]->pup_user_id]);

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
                            $message->from('no-reply@sw.ai', 'Stratus');
                            $message->to($user_single->email, $user_single->name)->subject($user_single->title);
                        });
                    }
              }

            } // End Foreach
            // End Payment Quantity Verification Automation Process


                // // Start Payment Application Automation Process
                // $query = DB::table('users')
                // ->leftJoin('project_contact', 'users.id', '=', 'project_contact.c_user_id')
                // ->leftJoin('projects', 'project_contact.c_project_id', '=', 'projects.p_id')
                // ->select('projects.*', 'users.id', 'users.email', 'users.username', 'users.first_name', 'users.last_name', 'users.phone_number', 'users.status', 'users.position_title', 'users.role')
                // ->where('project_contact.c_project_id', '=', $project_id)
                // ->whereIn('users.role', ['manager', 'contractor', 'engineer', 'admin', 'owner', 'surveyor'])
                // ->where('users.status', '!=', 2)
                // ->get();

                // $project_id           = $project_id;
                // $description          = "Payment application monthly report ".$month_name." has been generated";
                // $link                 = "/dashboard/".$project_id."/payment_application/".$payment_application_inserted_id;
                // $notification_status  = '1';
                // $sender_user_id       = '0';// Auth::user()->id;

                // foreach ($query as $user) {
                //     // print_r($user);
                //     $query = DB::table('project_notifications')
                //     ->insert(['pn_description' => $description, 'pn_link' => $link, 'pn_status' => $notification_status, 'pn_project_id' => $project_id, 'pn_sender_user_id' => $sender_user_id, 'pn_receiver_user_id' => $user->id]);

                //     $user_detail = array(
                //       'id'              => $user->id,
                //       'name'            => $user->username,
                //       'email'           => $user->email,
                //       'link'            => $link,
                //       'project_name'    => $user->p_name,
                //       'month_name'      => $month_name
                //     );
                //     $user_single = (object) $user_detail;
                //     Mail::send('emails.payment_quantity_verification_report',['user' => $user_single], function ($message) use ($user_single) {
                //         $message->from('no-reply@sw.ai', 'Stratus');
                //         $message->to($user_single->email, $user_single->name)->subject('Payment application monthly report has been generated');
                //     });
                // }
                // // End Payment Application Automation Process

            // Start Payment Quantity Verification Automation Process
               $check_project_users = app('App\Http\Controllers\Projects\PermissionController')->check_project_user($project_id);

               // Check User Project Permission  
              foreach ($check_project_users as $check_project_user) {
                // Check User Permission Parameter 
//                $user_id              = $check_project_user->id;
//                $permission_key       = 'payment_application_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = "Payment application monthly report ".$month_name." has been generated in Project: " .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/payment_application/".$payment_application_inserted_id;
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Payment application monthly report '.$month_name.' has been generated in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
//
//                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
//                if(count($check_single_user_permission) < 1){
//                  continue;
//                }
//                else {
//                  // Send Notification to users
//                  $notification_status  = '1';
//                  $sender_user_id       = '1';// Auth::user()->id;
//
//                  $query = DB::table('project_notifications')
//                  ->insert(['pn_description' => $notification_title, 'pn_link' => $link, 'pn_status' => $notification_status, 'pn_project_id' => $project_id, 'pn_sender_user_id' => $sender_user_id, 'pn_receiver_user_id' => $check_single_user_permission[0]->pup_user_id]);
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
//                      $message->from('no-reply@sw.ai', 'Stratus');
//                      $message->to($user_single->email, $user_single->name)->subject($user_single->title);
//                  });
//                }

              } // End Foreach
              // End Payment Quantity Verification Automation Process

                echo 'Add Report Item Quantity: '.$latest_qty.'<br/>';
              }
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
    ----------------------------------------------------------------------------------
     Get All Payment Quantity Verification Report by passing project ID
    ----------------------------------------------------------------------------------
    */
    public function get_quantity_verification_report(Request $request, $project_id)
    {
        try
        {
          // $user = array(
          //     'userid'    => Auth::user()->id,
          //     'role'      => Auth::user()->role
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
          $permission_key = 'payment_quantity_verification_view_all';
          $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
          if(count($check_single_user_permission) < 1){
            $result = array('code'=>403, "description"=>"Access Denies");
            return response()->json($result, 403);
          }
          else {
            $query = DB::table('project_payment_quantity_verification')
            ->leftJoin('projects', 'project_payment_quantity_verification.ppq_project_id', '=', 'projects.p_id')
            ->select('project_payment_quantity_verification.*', 'projects.*')
            ->where('ppq_project_id', '=', $project_id)
            ->orderBy('project_payment_quantity_verification.ppq_id','ASC')
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
     Get Payment Quantity Verification Report Details by passing Report_id
    ----------------------------------------------------------------------------------
    */
    public function get_quantity_verification_detail_report(Request $request, $report_id)
    {
        try
        {
          // $user = array(
          //     'userid'    => Auth::user()->id,
          //     'role'      => Auth::user()->role
          // );
          // $user = (object) $user;
          // $post = new Resource_Post(); // You create a new resource Post instance
          // if (Gate::forUser($user)->denies('allow_admin', [$post,false])) {
          //   $result = array('code'=>403, "description"=>"Access denies");
          //   return response()->json($result, 403);
          // }
          // else {
            $query = DB::table('project_payment_quantity_verification_detail')
            ->leftJoin('project_bid_items', 'project_payment_quantity_verification_detail.pqv_item_id', '=', 'project_bid_items.pbi_id')
            ->select('project_payment_quantity_verification_detail.*', 'project_bid_items.*')
            ->where('pqv_report_id', '=', $report_id)
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
    ----------------------------------------------------------------------------------
     Get Payment Quantity Verification Report Name by passing Report_id
    ----------------------------------------------------------------------------------
    */
    public function get_quantity_verification_report_name(Request $request, $report_id)
    {
        try
        {
          // $user = array(
          //     'userid'    => Auth::user()->id,
          //     'role'      => Auth::user()->role
          // );
          // $user = (object) $user;
          // $post = new Resource_Post(); // You create a new resource Post instance
          // if (Gate::forUser($user)->denies('allow_admin', [$post,false])) {
          //   $result = array('code'=>403, "description"=>"Access denies");
          //   return response()->json($result, 403);
          // }
          // else {
            $query = DB::table('project_payment_quantity_verification')
            ->select()
            ->where('ppq_id', '=', $report_id)
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
    ----------------------------------------------------------------------------------
     Get Payment Quantity Verification Complete Report by padding project_id
    ----------------------------------------------------------------------------------
    */
    public function get_quantity_verification_complete_report(Request $request, $project_id)
    {
        try
        {
          // $user = array(
          //     'userid'    => Auth::user()->id,
          //     'role'      => Auth::user()->role
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
          $permission_key = 'payment_quantity_verification_view_all';
          $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
          if(count($check_single_user_permission) < 1){
            $result = array('code'=>403, "description"=>"Access Denies");
            return response()->json($result, 403);
          }
          else {
            $total_item = DB::table('project_bid_items')
            ->leftJoin('project_daily_quantity_completed', 'project_bid_items.pbi_id', '=', 'project_daily_quantity_completed.pdq_item_id')
            ->select('project_bid_items.*', DB::raw('SUM(project_daily_quantity_completed.pdq_qty_complete_this_day) as total_quantity_use'))
            ->where('project_bid_items.pbi_project_id', '=', $project_id)
            // ->where('project_daily_quantity_completed.pdq_item_id', '=', $single_item->pbi_id)
            ->groupBy('project_daily_quantity_completed.pdq_item_id')
            ->get();

            // print_r($total_item);
            if(count($total_item) < 1)
            {
              $result = array('code'=>404, "description"=>"No Records Found");
              return response()->json($result, 404);
            }
            else
            {
              $result = array('data'=>$total_item,'code'=>200);
              return response()->json($result, 200);
            }
          }
        }
        catch(Exception $e)
        {
            return response()->json(['error' => 'Something is wrong'], 500);
        }
    }
    
    
    public function update_payment_quantity_verification(Request $request, $project_id, $report_id)
    {
        try
        {
            //$pqv_latest_qty         = $request['pqv_latest_qty'];
            $pqv_previous_qty       = $request['pqv_previous_qty'];
            $pqv_month_qty          = $request['pqv_month_qty'];
            $project_id             = $request['project_id'];
            $user_id                = Auth::user()->id;
            //$status               = $request['status'];
            // Check User Permission Parameter 
            $user_id = Auth::user()->id;
            $permission_key = 'meeting_minutes_update';
            $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
            if(count($check_single_user_permission) < 1){
              $result = array('code'=>403, "description"=>"Access Denies");
              return response()->json($result, 403);
            }
            else {
                $information = array(
                    //"pqv_latest_qty"       => $pqv_latest_qty,
                    "pqv_previous_qty"     => $pqv_previous_qty,
                    "project_id"            => $project_id,
                    "user_id"               => $user_id,
                    "pqv_month_qty"         => $pqv_month_qty
                );

                $rules = [
                    //'pqv_latest_qty'        => 'required|numeric',
                    'pqv_previous_qty'      => 'required|numeric',
                    'project_id'            => 'required|numeric',
                    'user_id'               => 'required|numeric',
                    'pqv_month_qty'         => 'required|numeric'
                ];
                $validator = Validator::make($information, $rules);
                if ($validator->fails()) 
                {
                    return $result = response()->json(["data" => $validator->messages()],400);
                }
                else
                {
                    $query = DB::table('project_payment_quantity_verification_detail')
                    ->where('pqv_id', '=', $report_id)
                    // ->update(['pm_contractor_id' => $contractor_id, 'pm_date' => $date, 'pm_description' => $description, 'pm_special' => $special, 'pm_agenda_path' => $agenda_path, 'pm_signin_sheet_path' => $signin_sheet_path, 'pm_meeting_minutes_path' => $meeting_minutes_path, 'pm_project_id' => $project_id, 'pm_user_id' => $user_id, 'pm_status' => $status]);
                    ->update(['pqv_previous_qty' => $pqv_previous_qty, 'pqv_month_qty' => $pqv_month_qty]);
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
    
    public function update_approval_status(Request $request, $project_id, $report_id)
    {
        $approval_status       = $request['approval_status'];
        $user_id                = Auth::user()->id;
        $user_id = Auth::user()->id;
        
        $query = DB::table('project_payment_quantity_verification')
        ->where('ppq_id', '=', $report_id)
        ->update(['approval_status' => $approval_status]);
        if(count($query) < 1)
        {
          $result = array('code'=>400, "description"=>"No records found");
          return response()->json($result, 400);
        }
        else
        {
            $report = DB::table('project_payment_quantity_verification')
            ->select('project_payment_quantity_verification.*')
            ->where('ppq_id', '=', $report_id)
            ->first();
            
            $query_project = ProjectPaymentApplication::create(['ppa_month_name' => $report->ppq_month_name, 'ppa_project_id' => $report->ppq_project_id]);
            $payment_application_inserted_id = $query_project->id;
            
            $pay_apps = DB::table('project_payment_quantity_verification_detail')
            ->select('project_payment_quantity_verification_detail.*')
            ->where('pqv_report_id', '=', $report_id)
            ->get();
            $ppa_amount = 0;
            
            foreach($pay_apps as $pay_app)
            {
                $item = DB::table('project_bid_items')
                    ->select('pbi_item_unit_price')
                    ->where('pbi_id', '=', $pay_app->pqv_item_id)
                    ->first();
                $ppa_amount += $item->pbi_item_unit_price*$pay_app->pqv_month_qty;
                $add_project_payment_application = DB::table('project_payment_application_detail')
              ->insert(['ppd_item_id' => $pay_app->pqv_item_id, 'ppd_previous_qty' => $pay_app->pqv_previous_qty, 'ppd_month_qty' => $pay_app->pqv_month_qty, 'ppd_report_id' => $payment_application_inserted_id, 'ppd_project_id' => $project_id]);
            }
            $query = DB::table('project_payment_application')
                        ->where('ppa_id', '=', $payment_application_inserted_id)
                        ->update(['ppa_amount' => $ppa_amount]);
            $project = DB::table('projects')
                        ->leftJoin('users', 'projects.p_user_id', '=', 'users.id')
                        ->select('projects.*','users.*')
                        ->where('p_id', '=', $project_id)
                        ->first();  
            $notification_title   = 'The pay application is ready for your review and approval in project '.$project->p_name.'. Please complete at your earliest convenience so the owner can process for payment.';
            $url                  = App::make('url')->to('/');
            $link                 = "/dashboard/".$project->p_id."/payment_application_monthly";
            $date                 = date("M d, Y h:i a");
            $email_description    = 'The pay application is ready for your review and approval in project <strong>'.$project->p_name.'</strong>. Please complete at your earliest convenience so the owner can process for payment. <a href="'.$url.$link.'"> Click Here to see </a>';
            $user_detail = array(
                'id'              => $project->id,
                'name'            => $project->username,
                'email'           => $project->email,
                'link'            => $link,
                'date'            => $date,
                'project_name'    => $project->p_name,
                'title'           => $notification_title,
                'description'     => $email_description
            );
            $user_single = (object) $user_detail;
            //print_r($user_single);
            Mail::send('emails.send_notification',['user' => $user_single], function ($message) use ($user_single) {
                $message->from('no-reply@sw.ai', 'StratusCM');
                $message->to($user_single->email, $user_single->name)->subject($user_single->title);
            });
            $result = array('data'=>$query,'code'=>200);
            return response()->json($result, 200);
        }
    }
}