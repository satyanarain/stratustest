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
use App\ProjectPaymentApplication; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post; 
use App\Repositories\Util\AclPolicy;


class PaymentApplicationController extends Controller {
  
  /*
    ----------------------------------------------------------------------------------
     Get Payment Quantity Verification by padding project ID
    ----------------------------------------------------------------------------------
    */
    public function get_payment_application_report(Request $request, $project_id)
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
          $permission_key = 'payment_application_view_all';
          $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
          if(count($check_single_user_permission) < 1){
            $result = array('code'=>403, "description"=>"Access Denies");
            return response()->json($result, 403);
          }
          else {
            $query = DB::table('project_payment_application')
            ->leftJoin('projects', 'project_payment_application.ppa_project_id', '=', 'projects.p_id')
            ->leftJoin('documents', 'project_payment_application.ppa_invoice_doc_id', '=', 'documents.doc_id')
            ->select('project_payment_application.*', 'projects.p_id','projects.p_name','documents.doc_path')
            ->where('ppa_project_id', '=', $project_id)
            ->orderBy('project_payment_application.ppa_id','ASC')
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
     Get Payment Quantity Verification Report by padding Report_id
    ----------------------------------------------------------------------------------
    */
    public function get_payment_application_detail_report(Request $request, $report_id)
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
            $query = DB::table('project_payment_application_detail')
            ->leftJoin('project_bid_items', 'project_payment_application_detail.ppd_item_id', '=', 'project_bid_items.pbi_id')
            ->leftJoin('project_payment_application', 'project_payment_application.ppa_id', '=', 'project_payment_application_detail.ppd_report_id')
            ->leftJoin('documents', 'project_payment_application.ppa_invoice_doc_id', '=', 'documents.doc_id')
            ->select('project_payment_application_detail.*', 'project_bid_items.*','documents.doc_path','project_payment_application.*')
            ->where('ppd_report_id', '=', $report_id)
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
     Get Payment Quantity Verification Report Name by padding Report_id
    ----------------------------------------------------------------------------------
    */
    public function get_payment_application_report_name(Request $request, $report_id)
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
            $query = DB::table('project_payment_application')
            ->select()
            ->where('ppa_id', '=', $report_id)
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
    public function get_payment_application_complete_report(Request $request, $project_id)
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
          $permission_key = 'payment_application_view_all';
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

    /*
  --------------------------------------------------------------------------
   Update payment application
  --------------------------------------------------------------------------
  */
  public function update_payment_application(Request $request, $report_id)
  {
    try
    {
        $project_id   = $request['project_id'];
        $user_id      = Auth::user()->id;
        $ppa_invoice_date  = $request['ppa_invoice_date'];
        $ppa_invoice_no  = $request['ppa_invoice_no'];
        $ppa_invoice_doc_id  = $request['ppa_invoice_doc_id'];
        
        // Check User Permission Parameter 
        $user_id = Auth::user()->id;
        $permission_key = 'payment_application_update';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
      else {
          $query = DB::table('project_payment_application')
          ->where('ppa_id', '=', $report_id)
          ->update(['paid'=>$request['paid'],'ppa_invoice_date'=>$ppa_invoice_date,'ppa_invoice_no'=>$ppa_invoice_no,'ppa_invoice_doc_id'=>$ppa_invoice_doc_id]);
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
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }
}