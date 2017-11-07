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


class PreliminaryNoticeController extends Controller {


    /*
    --------------------------------------------------------------------------
     Add Plans
    --------------------------------------------------------------------------
    */
    public function add_preliminary_notice(Request $request)
    {
        try
        {
            // $user = array(
            //     'id'        => Auth::user()->id,
            //     'role'      => Auth::user()->role
            // );
            // $user = (object) $user;
            // $post = new Resource_Post(); // You create a new resource Post instance
            // if (Gate::forUser($user)->denies('allow_admin', [$post,false])) {
            //     $result = array('code'=>403, "description"=>"Access denies");
            //     return response()->json($result, 403);
            // }
            // else {
                $ppn_project_id       = $request['ppn_project_id'];
                $preliminary_notice_from       = $request['preliminary_notice_from'];
                $under_contract_with   = $request['under_contract_with'];
                $amount  = $request['amount'];
                $direct_contractor = $request['direct_contractor'];
                $date_of_notice_signed = $request['date_of_notice_signed'];
                $post_marked_date = $request['post_marked_date'];
                $preliminary_notice_path = $request['preliminary_notice_path'];
                $status     = 'active';
                $user_id    = Auth::user()->id;
            // Check User Permission Parameter 
            $user_id = Auth::user()->id;
            $permission_key = 'preliminary_add';
            $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($ppn_project_id, $user_id, $permission_key);
            if(count($check_single_user_permission) < 1){
              $result = array('code'=>403, "description"=>"Access Denies");
              return response()->json($result, 403);
            }
            else {
                $information = array(
                    "ppn_project_id"=> $ppn_project_id,
                    "preliminary_notice_from"        => $preliminary_notice_from,
                    "under_contract_with"    => $under_contract_with,
                    "amount"   => $amount,
                    "direct_contractor"  => $direct_contractor,
                    "date_of_notice_signed"  => $date_of_notice_signed,
                    "post_marked_date"=>$post_marked_date,
                    "ppn_user_id"=>$user_id,
                    "status"=>$status,
                    "preliminary_notice_path"=>$preliminary_notice_path

                );
                $rules = [
                    'ppn_project_id'=> 'required',
                    'preliminary_notice_from'=> 'required',
                    'under_contract_with'    => 'required',
                    'amount'   => 'required',
                    'direct_contractor'  => 'required',
                    'date_of_notice_signed'     => 'required',
                    'post_marked_date'=>'required',
                    'ppn_user_id'=>'required',
                    'status'=> 'required',
                    'preliminary_notice_path'=>'required'
                ];
                $validator = Validator::make($information, $rules);
                if ($validator->fails())
                {
                    return $result = response()->json(["data" => $validator->messages()],400);
                }
                else
                {
                    $query = DB::table('project_preliminary_notice')->insertGetId($information);
                    
                // Start Check User Permission and send notification and email  
                // Get Project Users
                    $project_id = $ppn_project_id;
              $check_project_users = app('App\Http\Controllers\Projects\PermissionController')->check_project_user($project_id);

              // Check User Project Permission  
              foreach ($check_project_users as $check_project_user) {
                // Check User Permission Parameter 
//                $user_id              = $check_project_user->id;
//                $permission_key       = 'preliminary_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Add new preliminary notice in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/preliminary_notice/".$query;
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Add new preliminary notice in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
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

                    $result = array('description'=>"Added successfully",'code'=>200);
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
     Update Plans by passing preliminary_id
    --------------------------------------------------------------------------
    */
    public function update_preliminary(Request $request, $preliminary_id)
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
            //     $result = array('code'=>403, "description"=>"Access denies");
            //     return response()->json($result, 403);
            // }
            // else {
                // $date       = $request['date'];
                // $name       = $request['name'];
                // $approval   = $request['approval'];
                $status     = $request['status'];
                $project_id = $request['project_id'];
                $user_id    = Auth::user()->id;
            // Check User Permission Parameter 
            $user_id = Auth::user()->id;
            $permission_key = 'preliminary_update';
            $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
            if(count($check_single_user_permission) < 1){
              $result = array('code'=>403, "description"=>"Access Denies");
              return response()->json($result, 403);
            }
            else {
                $information = array(
                    // "date"        => $date,
                    // "name"        => $name,
                    // "approval"    => $approval,
                    // "file_path"   => $file_path,
                    "status"      => $status,
                    "project_id"  => $project_id,
                    "user_id"     => $user_id,
                );

                $rules = [
                    // 'date'        => 'required',
                    // 'name'        => 'required',
                    // 'approval'    => 'required',
                    // 'file_path'   => 'required',
                    'status'      => 'required',
                    'project_id'  => 'required|numeric',
                    'user_id'     => 'required|numeric'
                ];
                $validator = Validator::make($information, $rules);
                if ($validator->fails())
                {
                    return $result = response()->json(["data" => $validator->messages()],400);
                }
                else
                {
                    $query = DB::table('project_preliminary_notice')
                        ->where('ppn_id', '=', $preliminary_id)
                        ->update(['status' => $status, 'ppn_project_id' => $project_id, 'ppn_user_id' => $user_id]);
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
//                        $user_id              = $check_project_user->id;
//                        $permission_key       = 'preliminary_view_all';
//                        // Notification Parameter
//                        $project_id           = $project_id;
//                        $notification_title   = 'Update preliminary notice in Project: ' .$check_project_user->p_name;
//                        $url                  = App::make('url')->to('/');
//                        $link                 = "/dashboard/".$project_id."/preliminary_notice/".$preliminary_id;
//                        $date                 = date("M d, Y h:i a");
//                        $email_description    = 'Update preliminary notice in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
//
//                        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
//                        if(count($check_single_user_permission) < 1){
//                          continue;
//                        }
//                        else {
//                          // Send Notification to users
//                          $project_notification_query = app('App\Http\Controllers\Projects\NotificationController')->add_notification($notification_title, $link, $project_id, $check_single_user_permission[0]->pup_user_id);
//                       
//                          $user_detail = array(
//                            'id'              => $check_project_user->id,
//                            'name'            => $check_project_user->username,
//                            'email'           => $check_project_user->email,
//                            'link'            => $link,
//                            'date'            => $date,
//                            'project_name'    => $check_project_user->p_name,
//                            'title'           => $notification_title,
//                            'description'     => $email_description
//                          );
//                          $user_single = (object) $user_detail;
//                          Mail::send('emails.send_notification',['user' => $user_single], function ($message) use ($user_single) {
//                              $message->from('no-reply@sw.ai', 'StratusCM');
//                              $message->to($user_single->email, $user_single->name)->subject($user_single->title);
//                          });
//                        }

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
     Get single Plans by passing preliminary_id
    --------------------------------------------------------------------------
    */
    public function get_preliminary_single(Request $request, $preliminary_id)
    {
        try
        {
            // // $user = array(
            //     'userid'    => Auth::user()->id,
            //     'role'      => Auth::user()->role
            // );
            // $user = (object) $user;
            // $post = new Resource_Post(); // You create a new resource Post instance
            // if (Gate::forUser($user)->denies('allow_admin', [$post,false])) {
            //     $result = array('code'=>403, "description"=>"Access denies");
            //     return response()->json($result, 403);
            // }
            // else {
                $query = DB::table('project_preliminary_notice')
                ->leftJoin('documents', 'project_preliminary_notice.preliminary_notice_path', '=', 'documents.doc_id')
                ->leftJoin('projects', 'project_preliminary_notice.ppn_project_id', '=', 'projects.p_id')
                ->leftJoin('project_firm as preliminary_notice_firm', 'project_preliminary_notice.preliminary_notice_from', '=', 'preliminary_notice_firm.f_id')
                ->leftJoin('project_firm as undercontractwith', 'project_preliminary_notice.under_contract_with', '=', 'undercontractwith.f_id')
                ->leftJoin('project_firm as direct_contractor', 'project_preliminary_notice.direct_contractor', '=', 'direct_contractor.f_id')
                ->leftJoin('users', 'project_preliminary_notice.ppn_user_id', '=', 'users.id')
                ->select('preliminary_notice_firm.f_name as preliminary_notice_firm',
                    'undercontractwith.f_name as undercontractwith', 
                    'direct_contractor.f_name as direct_contractor_name', 
                'project_preliminary_notice.*', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
                ->where('ppn_id', '=', $preliminary_id)
                ->first();
                if(count($query) < 1)
                {
                    $result = array('code'=>404, "description"=>"No Records Found");
                    return response()->json($result, 404);
                }
                else
                {
                    
                    $liens = DB::table('project_preliminary_lien_release')
                    ->leftJoin('documents', 'project_preliminary_lien_release.lien_release_path', '=', 'documents.doc_id')
                    ->select('project_preliminary_lien_release.*', 'documents.*')
                    ->where('pplr_preliminary_id', '=', $preliminary_id)
                    ->orderBy('pplr_id', 'desc')->get();
                    foreach($liens as $lien)
                        $query->liens[]=$lien;
                    //print_r($query);die;
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
     Get Plans by padding project ID
    ----------------------------------------------------------------------------------
    */
    public function get_preliminary_notices(Request $request, $project_id)
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
            //     $result = array('code'=>403, "description"=>"Access denies");
            //     return response()->json($result, 403);
            // }
            // else {
            // Check User Permission Parameter 
            $user_id = Auth::user()->id;
            $permission_key = 'preliminary_view_all';
            $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
            if(count($check_single_user_permission) < 1){
              $result = array('code'=>403, "description"=>"Access Denies");
              return response()->json($result, 403);
            }
            else {
                $query = DB::table('project_preliminary_notice')
                ->leftJoin('project_settings', 'project_preliminary_notice.ppn_project_id', '=', 'project_settings.pset_project_id')
                ->leftJoin('currency', 'project_settings.pset_meta_value', '=', 'currency.cur_id')
                ->leftJoin('documents', 'project_preliminary_notice.preliminary_notice_path', '=', 'documents.doc_id')
                ->leftJoin('projects', 'project_preliminary_notice.ppn_project_id', '=', 'projects.p_id')
                ->leftJoin('project_firm as preliminary_notice_firm', 'project_preliminary_notice.preliminary_notice_from', '=', 'preliminary_notice_firm.f_id')
                ->leftJoin('project_firm as undercontractwith', 'project_preliminary_notice.under_contract_with', '=', 'undercontractwith.f_id')
                ->leftJoin('project_firm as direct_contractor', 'project_preliminary_notice.direct_contractor', '=', 'direct_contractor.f_id')
                ->leftJoin('users', 'project_preliminary_notice.ppn_user_id', '=', 'users.id')
                ->select('currency.cur_symbol as currency_symbol', 'preliminary_notice_firm.f_name as preliminary_notice_firm',
                    'undercontractwith.f_name as undercontractwith', 
                    'direct_contractor.f_name as direct_contractor_name', 
                    'project_preliminary_notice.*', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
                ->where('ppn_project_id', '=', $project_id)
                ->groupBy('project_preliminary_notice.ppn_id')
                ->get();
                if(count($query) < 1)
                  {
                    $result = array('code'=>404, "description"=>"No Records Found");
                    return response()->json($result, 404);
                  }
                  else
                  {
                    
                    foreach($query as $key=>$res)
                    {
                        $query1 = DB::table('project_preliminary_lien_release')
                        ->select('project_preliminary_lien_release.*')
                        ->where('pplr_preliminary_id', '=', $res->ppn_id)
                        ->where('pplr_type', '=', 'full')        
                        ->first();
                        if(count($query1))
                        {
                            $query[$key]->release_uploaded = 'Yes';
                            $query[$key]->unconditional_uploaded = 'Yes';
                        }else{
                            $query2 = DB::table('project_preliminary_lien_release')
                            ->select('project_preliminary_lien_release.*')
                            ->where('pplr_preliminary_id', '=', $res->ppn_id)
                            ->where('pplr_type', '=', 'partial')        
                            ->first();
                            if(count($query2))
                            {
                                $query[$key]->release_uploaded = 'Yes';
                                $query[$key]->unconditional_uploaded = 'No';
                            }else{
                                $query[$key]->release_uploaded = 'No';
                                $query[$key]->unconditional_uploaded = 'No';
                            }
                        }
                        
                    }
                    $result = array('data'=>$query,'code'=>200);
                    //print_r($result);die;
                    return response()->json($result, 200);
                  }
                // if(count($query) < 1)
                // {
                //     $result = array('code'=>404, "description"=>"No Records Found");
                //     return response()->json($result, 404);
                // }
                // else
                // {
                //     foreach($query as $d)
                //     {
                //         $d->preliminary_notice_from = $this->get_firm_name($d->preliminary_notice_from);
                //         $d->under_contract_with = $this->get_firm_name($d->under_contract_with);
                //         $d->direct_contractor = $this->get_firm_name($d->direct_contractor);
                //     }
                //     $result = array('data'=>$query,'code'=>200);
                //     return response()->json($result, 200);
                // }
            }
        }
        catch(Exception $e)
        {
            return response()->json(['error' => 'Something is wrong'], 500);
        }
    }




    public  function  get_firm_name($firm_id)
    {
        $query = DB::table('project_firm')
            ->leftJoin('company_type', 'project_firm.f_type', '=', 'company_type.ct_id')
            ->select('project_firm.f_name')
            ->where('f_id', '=', $firm_id)
            ->first();
        if(!empty($query)){
            $firm_name = $query->f_name;
        }
        else{
            $firm_name =null;
        }
        return  $firm_name;

    }
    
    /*
    ----------------------------------------------------------------------------------
     Get Lien Releases by passing project ID
    ----------------------------------------------------------------------------------
    */
    public function get_lien_releases(Request $request, $project_id)
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
            //     $result = array('code'=>403, "description"=>"Access denies");
            //     return response()->json($result, 403);
            // }
            // else {
            // Check User Permission Parameter 
            $user_id = Auth::user()->id;
            $permission_key = 'preliminary_view_all';
            $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
            if(count($check_single_user_permission) < 1){
              $result = array('code'=>403, "description"=>"Access Denies");
              return response()->json($result, 403);
            }
            else {
                $query = DB::table('project_preliminary_notice')
                ->leftJoin('project_settings', 'project_preliminary_notice.ppn_project_id', '=', 'project_settings.pset_project_id')
                ->leftJoin('currency', 'project_settings.pset_meta_value', '=', 'currency.cur_id')
                ->leftJoin('documents', 'project_preliminary_notice.preliminary_notice_path', '=', 'documents.doc_id')
                ->leftJoin('projects', 'project_preliminary_notice.ppn_project_id', '=', 'projects.p_id')
                ->leftJoin('project_firm as preliminary_notice_firm', 'project_preliminary_notice.preliminary_notice_from', '=', 'preliminary_notice_firm.f_id')
                ->leftJoin('project_firm as undercontractwith', 'project_preliminary_notice.under_contract_with', '=', 'undercontractwith.f_id')
                ->leftJoin('project_firm as direct_contractor', 'project_preliminary_notice.direct_contractor', '=', 'direct_contractor.f_id')
                ->leftJoin('users', 'project_preliminary_notice.ppn_user_id', '=', 'users.id')
                ->select('currency.cur_symbol as currency_symbol', 'preliminary_notice_firm.f_name as preliminary_notice_firm',
                    'undercontractwith.f_name as undercontractwith', 
                    'direct_contractor.f_name as direct_contractor_name', 
                    'project_preliminary_notice.*', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
                ->where('ppn_project_id', '=', $project_id)
                ->groupBy('project_preliminary_notice.ppn_id')
                ->get();
                if(count($query) < 1)
                  {
                    $result = array('code'=>404, "description"=>"No Records Found");
                    return response()->json($result, 404);
                  }
                  else
                  {
                    foreach($query as $key=>$res)
                    {
                        $query1 = DB::table('project_preliminary_lien_release')
                        ->select('project_preliminary_lien_release.*')
                        ->where('pplr_preliminary_id', '=', $res->ppn_id)
                        ->where('pplr_type', '=', 'full')        
                        ->first();
                        if(count($query1))
                        {
                            $query[$key]->prelim_status = 'Full';
                        }else{
                            $query2 = DB::table('project_preliminary_lien_release')
                            ->select('project_preliminary_lien_release.*')
                            ->where('pplr_preliminary_id', '=', $res->ppn_id)
                            ->where('pplr_type', '=', 'partial')        
                            ->first();
                            if(count($query2))
                            {
                                $query[$key]->prelim_status = 'Partial';
                            }else{
                                $query[$key]->prelim_status = '';
                            }
                        }
                        
                    }
                    //print_r($query);die;
                    $result = array('data'=>$query,'code'=>200);
                    return response()->json($result, 200);
                  }
                // if(count($query) < 1)
                // {
                //     $result = array('code'=>404, "description"=>"No Records Found");
                //     return response()->json($result, 404);
                // }
                // else
                // {
                //     foreach($query as $d)
                //     {
                //         $d->preliminary_notice_from = $this->get_firm_name($d->preliminary_notice_from);
                //         $d->under_contract_with = $this->get_firm_name($d->under_contract_with);
                //         $d->direct_contractor = $this->get_firm_name($d->direct_contractor);
                //     }
                //     $result = array('data'=>$query,'code'=>200);
                //     return response()->json($result, 200);
                // }
            }
        }
        catch(Exception $e)
        {
            return response()->json(['error' => 'Something is wrong'], 500);
        }
    }
    
    /*
    --------------------------------------------------------------------------
     Update Notice status by passing preliminary_id
    --------------------------------------------------------------------------
    */
    public function add_lienrelease(Request $request, $preliminary_id)
    {
        try
        {
            
            $pplr_project_id       = $request['pplr_project_id'];
            $pplr_preliminary_id = $request['pplr_preliminary_id'];
            $date_of_billed_through       = $request['date_of_billed_through'];
            $lien_release_note   = $request['lien_release_note'];
            $lien_release_path  = $request['lien_release_path'];
            $pplr_type = $request['pplr_type'];
            // Check User Permission Parameter 
            $user_id = Auth::user()->id;
            $permission_key = 'preliminary_add';
            $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($pplr_project_id, $user_id, $permission_key);
            if(count($check_single_user_permission) < 1){
              $result = array('code'=>403, "description"=>"Access Denies");
              return response()->json($result, 403);
            }
            else {
                $information = array(
                    "pplr_project_id"=> $pplr_project_id,
                    "pplr_preliminary_id"=> $pplr_preliminary_id,
                    "date_of_billed_through"=> $date_of_billed_through,
                    "lien_release_note"=> $lien_release_note,
                    "lien_release_path"=> $lien_release_path,
                    "pplr_type"=> $pplr_type,
                    "pplr_user_id"=>$user_id
                );
                $rules = [
                    'pplr_project_id'=> 'required',
                    'pplr_preliminary_id'=> 'required',
                    'date_of_billed_through'    => 'required',
                    'lien_release_note'   => 'required',
                    'lien_release_path'  => 'required',
                    'pplr_type'     => 'required',
                    'pplr_user_id'=>'required'
                ];
                $validator = Validator::make($information, $rules);
                if ($validator->fails())
                {
                    return $result = response()->json(["data" => $validator->messages()],400);
                }
                else
                {
                    $query = DB::table('project_preliminary_lien_release')->insertGetId($information);
                    $result = array('description'=>"Added successfully",'code'=>200);
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