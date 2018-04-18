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


class UnconditionalFinalsController extends Controller {


    /*
    --------------------------------------------------------------------------
     Add Plans
    --------------------------------------------------------------------------
    */
    public function add_unconditional_finals(Request $request)
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
            $date_of_signature   = $request['date_of_signature'];
            $name_of_claimant    = $request['name_of_claimant'];
            $name_of_customer    = $request['name_of_customer'];
            $job_location        = $request['job_location'];
            $owner               = $request['owner'];
            $file_path           = $request['file_path'];
            $project_id          = $request['project_id'];
            $status              = 'active';
            $user_id             = Auth::user()->id;
            $disputed_claim_amount = $request['disputed_claim_amount'];
            $signatory_arr       = $request['signatory_arr'];
            $envelope_id         = '';
            $docusign_status     = 'pending';
            // Check User Permission Parameter 
            $user_id = Auth::user()->id;
            $permission_key = 'unconditional_finals_add';
            $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
            if(count($check_single_user_permission) < 1){
              $result = array('code'=>403, "description"=>"Access Denies");
              return response()->json($result, 403);
            }
            else {
                if(count($signatory_arr))
                {
                $data = array();
                foreach($signatory_arr as $i=>$row){
                    if(filter_var($row['signatory_email'], FILTER_VALIDATE_EMAIL))
                    {
                        $data[$i]["email"] = $row['signatory_email'];
                        $data[$i]["name"] = $row['signatory_name'];
                        $data[$i]["roleName"] = "contractor";
                        $data[$i]["tabs"]["textTabs"] =
                                                array(array(
                                                        "tabLabel" => "name_of_claimant",
                                                        "value" => $row['name_of_claimant']),
                                                        array (
                                                        "tabLabel" => "name_of_customer",
                                                        "value" => $row['name_of_customer']),
                                                        array (
                                                        "tabLabel" => "job_location",
                                                        "value" => $row['job_location']),
                                                        array (
                                                        "tabLabel" => "owner",
                                                        "value" => $row['owner']),
                                                        array (
                                                        "tabLabel" => "disputed_claim_amount",
                                                        "value" => $row['disputed_claim_amount']));

                    }else{
                        $result = array('code'=>400,"data"=>array("description"=>"Signatory email is not valid.",'docusign'=>1,
                                            "notice_status"=>null,"contactor_name"=>null,"contact_amount"=>null,"award_date"=>null,"notice_path"=>null,"project_id"=>null));
                        return response()->json($result, 400);
                    }
                }
                if(count($data))
                {
                    $documentName = 'Unconditional Finals';
                    $email = env('DOCUSIGN_EMAIL');
                    $password = env('DOCUSIGN_PASSWORD');
                    $integratorKey = env('DOCUSIGN_INTEGRATOR_KEY');
                    $templateId = "d656b6f8-9443-47fc-af2c-c9570206ec54";
                    $url = env('DOCUSIGN_URL');
                    $header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_HEADER, false);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-DocuSign-Authentication: $header"));
                    $json_response = curl_exec($curl);
                    $statuscode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    if ( $statuscode != 200 ) {
                        $result = array('code'=>400,"data"=>array("description"=>"Error calling DocuSign, status is: " . $status,'docusign'=>1,
                                            "notice_status"=>null,"contactor_name"=>null,"contact_amount"=>null,"award_date"=>null,"notice_path"=>null,"project_id"=>null));
                        return response()->json($result, 400);
                    }
                    $response = json_decode($json_response, true);
                    $accountId = $response["loginAccounts"][0]["accountId"];
                    $baseUrl = $response["loginAccounts"][0]["baseUrl"];
                    curl_close($curl);

                    $data = array("accountId" => $accountId, 
                        "emailSubject" => "Signature request for Unconditional Final",
                        "emailBlurb" => "signature for the Unconditional Final",
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
                            $result = array('code'=>400,"data"=>array("description"=>$response['message'],'docusign'=>1,
                                            "notice_status"=>null,"contactor_name"=>null,"contact_amount"=>null,"award_date"=>null,"notice_path"=>null,"project_id"=>null));
                            return response()->json($result, 400);
                    }
                    $response = json_decode($json_response, true);
                    $envelope_id = $response["envelopeId"];
                }
            }
                $information = array(
                    "puf_date_of_signature"     => $date_of_signature,
                    "puf_name_of_claimant"      => $name_of_claimant,
                    "puf_name_of_customer"      => $name_of_customer,
                    "puf_job_location"          => $job_location,
                    "puf_owner"                 => $owner,
                    "puf_file_path"             => $file_path,
                    "puf_project_id"            => $project_id,
                    "puf_status"                => $status,
                    "puf_user_id"               => $user_id,
                    "disputed_claim_amount"     => $disputed_claim_amount,
                    "docusign_status"           => $docusign_status,
                    "envelope_id"               => $envelope_id,
                );
                $rules = [
                    'puf_date_of_signature'     => 'required',
                    'puf_name_of_claimant'      => 'required',
                    'puf_name_of_customer'      => 'required',
                    'puf_job_location'          => 'required',
                    'puf_owner'                 => 'required',
                    'puf_file_path'             => 'required',
                    'puf_project_id'            => 'required',
                    'puf_status'                => 'required',
                    'puf_user_id'               => 'required'
                ];
                $validator = Validator::make($information, $rules);
                if ($validator->fails())
                {
                    return $result = response()->json(["data" => $validator->messages()],400);
                }
                else
                {
                    $query = DB::table('project_unconditional_finals')->insertGetId($information);
                    
                // Start Check User Permission and send notification and email  
                // Get Project Users
                $check_project_users = app('App\Http\Controllers\Projects\PermissionController')->check_project_user($project_id);

              // Check User Project Permission  
              foreach ($check_project_users as $check_project_user) {
                // Check User Permission Parameter 
                $user_id              = $check_project_user->id;
                $permission_key       = 'unconditional_finals_view_all';
                // Notification Parameter
                $project_id           = $project_id;
                //$notification_title   = 'Add new unconditional finals in Project: ' .$check_project_user->p_name;
                $notification_title   = 'New unconditional final added in Project: ' .$check_project_user->p_name;
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/unconditional_finals/".$query;
                $date                 = date("M d, Y h:i a");
                $email_description    = 'A new unconditional final has been added in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
                if(count($check_single_user_permission) < 1){
                  continue;
                }
                else {
                    $notification_key     = 'unconditional_finals';
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
     Update unconditional_finals by passing puf_id
    --------------------------------------------------------------------------
    */
    public function update_unconditional_finals(Request $request, $puf_id)
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
            $permission_key = 'unconditional_finals_update';
            $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
            if(count($check_single_user_permission) < 1){
              $result = array('code'=>403, "description"=>"Access Denies");
              return response()->json($result, 403);
            }
            else {
                $information = array(
                    "status"      => $status,
                    "project_id"  => $project_id,
                    "user_id"     => $user_id,
                );

                $rules = [
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
                    $query = DB::table('project_unconditional_finals')
                    ->where('puf_id', '=', $puf_id)
                    ->update(['puf_status' => $status, 'puf_project_id' => $project_id, 'puf_user_id' => $user_id]);
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
                        $permission_key       = 'preliminary_view_all';
                        // Notification Parameter
                        $project_id           = $project_id;
                        $notification_title   = 'Unconditional final updated in Project: ' .$check_project_user->p_name;
                        $url                  = App::make('url')->to('/');
                        $link                 = "/dashboard/".$project_id."/unconditional_finals/".$puf_id;
                        $date                 = date("M d, Y h:i a");
                        $email_description    = 'A unconditional final has been updated in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

                        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
                        if(count($check_single_user_permission) < 1){
                          continue;
                        }
                        else {
                            $notification_key     = 'unconditional_finals';
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
     Get single Plans by passing preliminary_id
    --------------------------------------------------------------------------
    */
    public function get_unconditional_finals_single(Request $request, $puf_id)
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
                $query = DB::table('project_unconditional_finals')
                ->leftJoin('documents', 'project_unconditional_finals.puf_file_path', '=', 'documents.doc_id')
                ->leftJoin('projects', 'project_unconditional_finals.puf_project_id', '=', 'projects.p_id')
                ->leftJoin('project_firm as name_of_claimant', 'project_unconditional_finals.puf_name_of_claimant', '=', 'name_of_claimant.f_id')
                ->leftJoin('project_firm as name_of_customer', 'project_unconditional_finals.puf_name_of_customer', '=', 'name_of_customer.f_id')
                ->leftJoin('project_firm as owner', 'project_unconditional_finals.puf_owner', '=', 'owner.f_id')
                ->leftJoin('users', 'project_unconditional_finals.puf_user_id', '=', 'users.id')
                ->select('name_of_claimant.f_name as name_of_claimant',
                    'name_of_customer.f_name as name_of_customer', 
                    'owner.f_name as owner_name', 'documents.doc_path', 
                'project_unconditional_finals.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
                ->where('puf_id', '=', $puf_id)
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
    public function get_unconditional_finals(Request $request, $project_id)
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
            $permission_key = 'unconditional_finals_view_all';
            $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
            if(count($check_single_user_permission) < 1){
              $result = array('code'=>403, "description"=>"Access Denies");
              return response()->json($result, 403);
            }
            else {
                $query = DB::table('project_unconditional_finals')
                ->leftJoin('documents', 'project_unconditional_finals.puf_file_path', '=', 'documents.doc_id')
                ->leftJoin('projects', 'project_unconditional_finals.puf_project_id', '=', 'projects.p_id')
                ->leftJoin('project_firm as name_of_claimant', 'project_unconditional_finals.puf_name_of_claimant', '=', 'name_of_claimant.f_id')
                ->leftJoin('project_firm as name_of_customer', 'project_unconditional_finals.puf_name_of_customer', '=', 'name_of_customer.f_id')
                ->leftJoin('project_firm as owner', 'project_unconditional_finals.puf_owner', '=', 'owner.f_id')
                ->leftJoin('users', 'project_unconditional_finals.puf_user_id', '=', 'users.id')
                ->select('name_of_claimant.f_name as name_of_claimant',
                    'name_of_customer.f_name as name_of_customer', 
                    'owner.f_name as owner_name', 'documents.doc_path', 
                'project_unconditional_finals.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
                ->where('puf_project_id', '=', $project_id)
                ->orderBy('project_unconditional_finals.puf_id','ASC')
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