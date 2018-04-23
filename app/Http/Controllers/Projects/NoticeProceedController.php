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


class NoticeProceedController extends Controller {

  /*
  --------------------------------------------------------------------------
   ADD NOTICE PROCEED
  --------------------------------------------------------------------------
  */
  public function add_notice_proceed(Request $request)
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
        $check_award_type     = $request['check_award_type'];
        $contractor_name      = $request['contractor_name'];
        $date                 = $request['date'];
        $start_date           = $request['start_date'];
        $duration             = $request['duration'];
        $cal_day              = $request['cal_day'];
        // $liquidated_currency  = $request['liquidated_currency'];
        $liquidated_amount    = $request['liquidated_amount'];
        $path                 = $request['path'];
        // $sing                 = $request['sing'];
        $project_id           = $request['project_id'];
        $user_id              = Auth::user()->id;
        $status               = 'active';
        $signatory_arr      = $request['signatory_arr'];
        $pna_envelope_id    = '';
        $pna_docusign_status = 'pending';
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'notice_proceed_add';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            "contractor_name"     => $contractor_name,
            "date"                => $date,
            "start_date"          => $start_date,
            "duration"            => $duration,
            "liquidated_amount"   => $liquidated_amount,
            "path"                => $path,
            "project_id"          => $project_id,
            "user_id"             => $user_id,
            "status"              => $status
        );

        if($check_award_type == 'new'){
            if(count($signatory_arr))
            {
                $data = array();
                foreach($signatory_arr as $i=>$row){
                    if(filter_var($row['signatory_email'], FILTER_VALIDATE_EMAIL))
                    {
                        $data[$i]["email"] = $row['signatory_email'];
                        $data[$i]["name"] = $row['signatory_name'];
                        $data[$i]["roleName"] = $row['signatory_role'];
                        $data[$i]["tabs"]["textTabs"] =
                                                array(array(
                                                        "tabLabel" => "pdf_gen_contractor_name",
                                                        "value" => $row['pdf_gen_contractor_name']),
                                                        array (
                                                        "tabLabel" => "pdf_gen_contractor_address",
                                                        "value" => $row['pdf_gen_contractor_address']),
                                                        array (
                                                        "tabLabel" => "pdf_gen_project_name",
                                                        "value" => $row['pdf_gen_project_name']),
                                                        array (
                                                        "tabLabel" => "pdf_gen_project_type",
                                                        "value" => $row['pdf_gen_project_type']),
                                                        array (
                                                        "tabLabel" => "pdf_gen_contractor_name_1",
                                                        "value" => $row['pdf_gen_contractor_name_1']),
                                                        array (
                                                        "tabLabel" => "pdf_gen_start_date",
                                                        "value" => $row['pdf_gen_start_date']),
                                                        array (
                                                        "tabLabel" => "pdf_gen_ntp_date",
                                                        "value" => date('m-d-Y')),
                                                        array (
                                                        "tabLabel" => "pdf_gen_working_days",
                                                        "value" => $row['pdf_gen_working_days']),
                                                        array (
                                                        "tabLabel" => "pdf_gen_working_days_1",
                                                        "value" => $row['pdf_gen_working_days_1']),
                                                        array (
                                                        "tabLabel" => "pdf_gen_amount",
                                                        "value" => $row['pdf_gen_amount']));

                    }else{
                        $result = array('code'=>400,"data"=>array("description"=>"Signatory email is not valid.",'docusign'=>1,
                                            "notice_status"=>null,"contactor_name"=>null,"contact_amount"=>null,"award_date"=>null,"notice_path"=>null,"project_id"=>null));
                        return response()->json($result, 400);
                    }
                }
                if(count($data))
                {
                    
                    $email = env('DOCUSIGN_EMAIL');
                    $password = env('DOCUSIGN_PASSWORD');
                    $integratorKey = env('DOCUSIGN_INTEGRATOR_KEY');
                    $templateId = "dc63edb8-d736-4882-92c5-b880c163dcb1";
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
                        "emailSubject" => "Signature for the Notice to Proceed",
                        "emailBlurb" => "This is a signature request for a Notice to Proceed",
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
                    //print_r($data);
                    $pna_envelope_id = $response["envelopeId"];
                }
            }
            
          $rules = [
            'contractor_name'     => 'required',
            // 'date'                => 'required',
            // 'start_date'          => 'required',
            'path'                => 'required',
            'project_id'          => 'required|numeric',
            'user_id'             => 'required|numeric',
            'status'              => 'required'
          ];  
        }
        else {
          $rules = [
            'contractor_name'     => 'required',
            // 'date'                => 'required',
            // 'start_date'          => 'required',
            'path'                => 'required',
            'project_id'          => 'required|numeric',
            'user_id'             => 'required|numeric',
            'status'              => 'required'
          ];
        }
        
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            if($check_award_type == 'new'){
              $query = DB::table('project_notice_proceed')
              ->insert(['pnp_docusign_status'=>$pna_docusign_status,'pnp_envelope_id'=>$pna_envelope_id,'pnp_contractor_name' => $contractor_name, 'pnp_type' => $check_award_type, 'pnp_date' => $date, 'pnp_start_date' => $start_date, 'pnp_duration' => $duration, 'pnp_cal_day' => $cal_day, 'pnp_liquidated_amount' => $liquidated_amount, 'pnp_path' => $path, 'pnp_project_id' => $project_id, 'pnp_user_id' => $user_id, 'pnp_status' => $status]);

            }
            else {
              $query = DB::table('project_notice_proceed')
              ->insert(['pnp_contractor_name' => $contractor_name, 'pnp_type' => $check_award_type, 'pnp_path' => $path, 'pnp_project_id' => $project_id, 'pnp_user_id' => $user_id, 'pnp_status' => $status]);
            }

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
//                $permission_key       = 'notice_proceed_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Add new notice of proceed in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/notice_proceed";
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Add new notice of proceed in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
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
 
              $result = array('description'=>"Add notice of prceed successfully",'code'=>200);
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
   Update Notice Proceed by passing con_id
  --------------------------------------------------------------------------
  */
  public function update_notice_proceed(Request $request, $pnp_id)
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
        // $contractor_name      = $request['contractor_name'];
        // $date                 = $request['date'];
        // $start_date           = $request['start_date'];
        // $duration             = $request['duration'];
        // $cal_day              = $request['cal_day'];
        // $liquidated_currency  = $request['liquidated_currency'];
        // $liquidated_amount    = $request['liquidated_amount'];
        // $path                 = $request['path'];
        // $sing                 = $request['sing'];
        $notice_sign_owner        = $request['notice_sign_owner'];
        $notice_review_owner      = $request['notice_review_owner'];
        $notice_sign_contractor   = $request['notice_sign_contractor'];
        $notice_review_contractor = $request['notice_review_contractor'];
        $project_id           = $request['project_id'];
        $user_id              = Auth::user()->id;
        $status               = $request['status'];
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'notice_proceed_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            // "contractor_name"     => $contractor_name,
            // "date"                => $date,
            // "start_date"          => $start_date,
            "project_id"          => $project_id,
            "user_id"             => $user_id,
            "status"              => $status
        );

        $rules = [
            // 'contractor_name'     => 'required',
            // 'date'                => 'required',
            // 'start_date'          => 'required',
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
            $user_detail = DB::table('project_notice_proceed')
            ->select()
            ->where('pnp_id', '=', $pnp_id)
            ->first();
            if(($notice_sign_owner == "") ? $notice_sign_owner = $user_detail->pnp_notice_sign_owner : $notice_sign_owner = $notice_sign_owner);
            if(($notice_review_owner == "") ? $notice_review_owner = $user_detail->pnp_notice_review_owner : $notice_review_owner = $notice_review_owner);
            if(($notice_sign_contractor == "") ? $notice_sign_contractor = $user_detail->pnp_notice_sign_contractor : $notice_sign_contractor = $notice_sign_contractor);
            if(($notice_review_contractor == "") ? $notice_review_contractor = $user_detail->pnp_notice_review_contractor : $notice_review_contractor = $notice_review_contractor);

            $query = DB::table('project_notice_proceed')
            ->where('pnp_id', '=', $pnp_id)
            // ->update(['pnp_contractor_name' => $contractor_name, 'pnp_date' => $date, 'pnp_start_date' => $start_date, 'pnp_duration' => $duration, 'pnp_cal_day' => $cal_day, 'pnp_liquidated_currency' => $liquidated_currency, 'pnp_liquidated_amount' => $liquidated_amount, 'pnp_path' => $path, 'pnp_sing' => $sing, 'pnp_project_id' => $project_id, 'pnp_user_id' => $user_id, 'pnp_status' => $status]);
            ->update(['pnp_project_id' => $project_id, 'pnp_notice_sign_owner' => $notice_sign_owner, 'pnp_notice_review_owner' => $notice_review_owner, 'pnp_notice_sign_contractor' => $notice_sign_contractor, 'pnp_notice_review_contractor' => $notice_review_contractor, 'pnp_user_id' => $user_id, 'pnp_status' => $status]);
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
//                $permission_key       = 'notice_proceed_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Update notice of proceed # '.$pnp_id.' in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/notice_proceed";
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Update notice of proceed # '.$pnp_id.' in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
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
   Get single Notice Proceed by passing pnp_id
  --------------------------------------------------------------------------
  */
  public function get_notice_proceed_single(Request $request, $pnp_id)
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
        $query = DB::table('project_notice_proceed')
->leftJoin('project_firm as contractor_name', 'project_notice_proceed.pnp_contractor_name', '=', 'contractor_name.f_id')
->leftJoin('currency as liquidated_currency', 'project_notice_proceed.pnp_liquidated_currency', '=', 'liquidated_currency.cur_id')
->leftJoin('documents as path', 'project_notice_proceed.pnp_path', '=', 'path.doc_id')
->leftJoin('documents as owner_sign', 'project_notice_proceed.pnp_notice_sign_owner', '=', 'owner_sign.doc_id')
->leftJoin('documents as contractor_sign', 'project_notice_proceed.pnp_notice_sign_contractor', '=', 'contractor_sign.doc_id')
->leftJoin('projects', 'project_notice_proceed.pnp_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_notice_proceed.pnp_user_id', '=', 'users.id')
        ->select('contractor_name.f_name as contractor_name',
          'liquidated_currency.cur_symbol as liquidated_currency', 
          'path.doc_path as notice_proceed_path', 'project_notice_proceed.*', 'projects.*', 'owner_sign.doc_path as owner_sign_doc_path', 'contractor_sign.doc_path as contractor_sign_doc_path', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('pnp_id', '=', $pnp_id)
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
   Get all Notice of Proceed by passing Project ID
  ----------------------------------------------------------------------------------
  */
  public function get_notice_proceed_project(Request $request, $project_id)
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
        $permission_key = 'notice_proceed_view_all';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
          $query = DB::table('project_notice_proceed')
->leftJoin('project_settings', 'project_notice_proceed.pnp_project_id', '=', 'project_settings.pset_project_id')
->leftJoin('currency', 'project_settings.pset_meta_value', '=', 'currency.cur_id')
->leftJoin('project_firm as contractor_name', 'project_notice_proceed.pnp_contractor_name', '=', 'contractor_name.f_id')
->leftJoin('currency as liquidated_currency', 'project_notice_proceed.pnp_liquidated_currency', '=', 'liquidated_currency.cur_id')
->leftJoin('documents as path', 'project_notice_proceed.pnp_path', '=', 'path.doc_id')
->leftJoin('projects', 'project_notice_proceed.pnp_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_notice_proceed.pnp_user_id', '=', 'users.id')
        ->select('currency.cur_symbol as currency_symbol', 'contractor_name.f_name as contractor_name',
          'liquidated_currency.cur_symbol as liquidated_currency', 
          'path.doc_path as notice_proceed_path',  
          'project_notice_proceed.*', 'projects.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('pnp_project_id', '=', $project_id)
          ->groupBy('project_notice_proceed.pnp_id')
          ->orderBy('project_notice_proceed.pnp_id','ASC')
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
   Get all Notice of Proceed by passing Project ID
  ----------------------------------------------------------------------------------
  */
  public function get_notice_proceed_project_single(Request $request, $project_id)
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
        $permission_key = 'notice_proceed_view_all';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
          $query = DB::table('project_notice_proceed')
->leftJoin('project_firm as contractor_name', 'project_notice_proceed.pnp_contractor_name', '=', 'contractor_name.f_id')
->leftJoin('currency as liquidated_currency', 'project_notice_proceed.pnp_liquidated_currency', '=', 'liquidated_currency.cur_id')
->leftJoin('documents as path', 'project_notice_proceed.pnp_path', '=', 'path.doc_id')
->leftJoin('projects', 'project_notice_proceed.pnp_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_notice_proceed.pnp_user_id', '=', 'users.id')
        ->select('contractor_name.f_name as contractor_name',
          'liquidated_currency.cur_symbol as liquidated_currency', 
          'path.doc_path as notice_proceed_path',  
          'project_notice_proceed.*', 'projects.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('pnp_project_id', '=', $project_id)
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
      }
      catch(Exception $e)
      {
        return response()->json(['error' => 'Something is wrong'], 500);
      }
  }
}