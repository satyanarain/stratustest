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


class LaborComplianceController extends Controller {
 
    /*
    --------------------------------------------------------------------------
     Add Labor Compliance
    --------------------------------------------------------------------------
    */
    public function add_labor_compliance(Request $request)
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
                $plc_contactor_id       = $request['contactor_id'];
                $plc_140                = $request['140'];
                $plc_140_date           = $request['140_date'];
                $plc_142                = $request['142'];
                $plc_142_date           = $request['142_date'];
                $plc_fringe             = $request['fringe'];
                $plc_fringe_date        = $request['fringe_date'];
                $plc_cac2               = $request['cac2'];
                $plc_cac2_date          = $request['cac2_date'];
                $plc_cpr                = $request['cpr'];
                $plc_cpr_date           = $request['cpr_date'];
                $plc_compliance         = $request['compliance'];
                $plc_compliance_date    = $request['compliance_date'];
                $plc_project_id         = $request['project_id'];
                $status                 = 'active';
                $user_id                = Auth::user()->id;
            // Check User Permission Parameter 
            $user_id = Auth::user()->id;
            $permission_key = 'labor_compliance_add';
            $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($plc_project_id, $user_id, $permission_key);
            if(count($check_single_user_permission) < 1){
              $result = array('code'=>403, "description"=>"Access Denies");
              return response()->json($result, 403);
            }
            else {
                $information = array(
                    "contactor_id"      => $plc_contactor_id,
                    "project_id"        => $plc_project_id,
                    "user_id"           => $user_id,
                    "status"            => $status

                );
                $rules = [
                    'contactor_id'      => 'required',
                    'project_id'        => 'required',
                    'user_id'           => 'required',
                    'status'            => 'required',
                ];
                $validator = Validator::make($information, $rules);
                if ($validator->fails())
                {
                    return $result = response()->json(["data" => $validator->messages()],400);
                }
                else
                {
                    $query = DB::table('project_labor_compliance')
                    ->insertGetId(['plc_contactor_id' => $plc_contactor_id, 'plc_140' => $plc_140, 'plc_140_date' => $plc_140_date, 'plc_142' => $plc_142, 'plc_142_date' => $plc_142_date, 'plc_fringe' => $plc_fringe, 'plc_fringe_date' => $plc_fringe_date, 'plc_cac2' => $plc_cac2, 'plc_cac2_date' => $plc_cac2_date, 'plc_cpr' => $plc_cpr, 'plc_cpr_date' => $plc_cpr_date, 'plc_compliance' => $plc_compliance, 'plc_compliance_date' => $plc_compliance_date, 'plc_project_id' => $plc_project_id, 'plc_user_id' => $user_id, 'plc_status' => $status]);


                    $project_id = $plc_project_id;
                    // Start Check User Permission and send notification and email  
                    // Get Project Users
                    $check_project_users = app('App\Http\Controllers\Projects\PermissionController')->check_project_user($project_id);

                    // Check User Project Permission  
                    foreach ($check_project_users as $check_project_user) {
                    // Check User Permission Parameter 
                    $user_id              = $check_project_user->id;
                    $permission_key       = 'labor_compliance_view_all';
                    // Notification Parameter
                    $project_id           = $project_id;
                    $notification_title   = 'Add new labor compliance in Project: ' .$check_project_user->p_name;
                    $url                  = App::make('url')->to('/');
                    $link                 = "dashboard/".$project_id."/labor_compliance/".$query;
                    $date                 = date("M d, Y h:i a");
                    $email_description    = 'Add new labor compliance in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

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
     Update Plans by passing plc_id
    --------------------------------------------------------------------------
    */
    public function update_labor_compliance(Request $request, $plc_id)
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
            $permission_key = 'labor_compliance_update';
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
                    $query = DB::table('project_labor_compliance')
                        ->where('plc_id', '=', $plc_id)
                        ->update(['plc_status' => $status, 'plc_project_id' => $project_id, 'plc_user_id' => $user_id]);
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
                        $permission_key       = 'labor_compliance_view_all';
                        // Notification Parameter
                        $project_id           = $project_id;
                        $notification_title   = 'Update status labor compliance in Project: ' .$check_project_user->p_name;
                        $url                  = App::make('url')->to('/');
                        $link                 = "dashboard/".$project_id."/labor_compliance/".$plc_id;
                        $date                 = date("M d, Y h:i a");
                        $email_description    = 'Update status labor compliance in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

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
     Update Checkbox by passing plc_id
    --------------------------------------------------------------------------
    */
    public function update_labor_dir(Request $request, $plc_id)
    {
        try
        {
            $plc_id             = $request['plc_id'];
            $user_id            = Auth::user()->id;
            
            $information = array(
                "plc_id"        => $plc_id,
                "user_id"       => $user_id,
            );

            $rules = [
                'plc_id'        => 'required',
                'user_id'       => 'required|numeric'
            ];
            $validator = Validator::make($information, $rules);
            if ($validator->fails())
            {
                return $result = response()->json(["data" => $validator->messages()],400);
            }
            else
            {
                $query = DB::table('project_labor_compliance')
                ->select('plc_dir_upload')
                ->where('plc_id', '=', $plc_id)
                ->first();
                if(count($query) < 1)
                {
                    $result = array('code'=>404, "description"=>"No Records Found");
                    return response()->json($result, 404);
                }
                else
                {
                    // $result = array('data'=>$query,'code'=>200);
                    $plc_dir_upload = $query->plc_dir_upload;
                    if($plc_dir_upload == 'no'){
                        $plc_dir_upload = 'yes';
                    }
                    else {
                        $plc_dir_upload = 'no';
                    }

                    $query = DB::table('project_labor_compliance')
                    ->where('plc_id', '=', $plc_id)
                    ->update(['plc_dir_upload' => $plc_dir_upload]);
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
     Get single Plans by passing plc_id
    --------------------------------------------------------------------------
    */
    public function get_labor_compliance_single(Request $request, $plc_id)
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
                $query = DB::table('project_labor_compliance')
->leftJoin('documents as 140_doc', 'project_labor_compliance.plc_140', '=', '140_doc.doc_id')
->leftJoin('documents as 142_doc', 'project_labor_compliance.plc_142', '=', '142_doc.doc_id')
->leftJoin('documents as fringe_doc', 'project_labor_compliance.plc_fringe', '=', 'fringe_doc.doc_id')
->leftJoin('documents as cac2_doc', 'project_labor_compliance.plc_cac2', '=', 'cac2_doc.doc_id')
->leftJoin('documents as cpr_doc', 'project_labor_compliance.plc_cpr', '=', 'cpr_doc.doc_id')
->leftJoin('documents as compliance', 'project_labor_compliance.plc_compliance', '=', 'compliance.doc_id')
->leftJoin('projects', 'project_labor_compliance.plc_project_id', '=', 'projects.p_id')
->leftJoin('project_firm as contractor_name', 'project_labor_compliance.plc_contactor_id', '=', 'contractor_name.f_id')
->leftJoin('users', 'project_labor_compliance.plc_user_id', '=', 'users.id')
                ->select('contractor_name.f_name', '140_doc.doc_path as doc_140', '142_doc.doc_path as doc_142', 'fringe_doc.doc_path as fringe_doc', 'cac2_doc.doc_path as cac2_doc', 'cpr_doc.doc_path as cpr_doc', 'compliance.doc_path as compliance', 'project_labor_compliance.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
                ->where('plc_id', '=', $plc_id)
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
     Get Plans by padding project ID
    ----------------------------------------------------------------------------------
    */
    public function get_labor_compliance(Request $request, $project_id)
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
            $permission_key = 'labor_compliance_view_all';
            $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
            if(count($check_single_user_permission) < 1){
              $result = array('code'=>403, "description"=>"Access Denies");
              return response()->json($result, 403);
            }
            else {
                $query = DB::table('project_labor_compliance')
->leftJoin('documents as 140_doc', 'project_labor_compliance.plc_140', '=', '140_doc.doc_id')
->leftJoin('documents as 142_doc', 'project_labor_compliance.plc_142', '=', '142_doc.doc_id')
->leftJoin('documents as fringe_doc', 'project_labor_compliance.plc_fringe', '=', 'fringe_doc.doc_id')
->leftJoin('documents as cac2_doc', 'project_labor_compliance.plc_cac2', '=', 'cac2_doc.doc_id')
->leftJoin('documents as cpr_doc', 'project_labor_compliance.plc_cpr', '=', 'cpr_doc.doc_id')
->leftJoin('documents as compliance', 'project_labor_compliance.plc_compliance', '=', 'compliance.doc_id')
->leftJoin('projects', 'project_labor_compliance.plc_project_id', '=', 'projects.p_id')
->leftJoin('project_firm as contractor_name', 'project_labor_compliance.plc_contactor_id', '=', 'contractor_name.f_id')
->leftJoin('users', 'project_labor_compliance.plc_user_id', '=', 'users.id')
                ->select('contractor_name.f_name', '140_doc.doc_path as doc_140', '142_doc.doc_path as doc_142', 'fringe_doc.doc_path as fringe_doc', 'cac2_doc.doc_path as cac2_doc', 'cpr_doc.doc_path as cpr_doc', 'compliance.doc_path as compliance', 'project_labor_compliance.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
                ->where('plc_project_id', '=', $project_id)
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

}