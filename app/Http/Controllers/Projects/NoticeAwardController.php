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
use App\ProjectNoticeofAward; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post; 
use App\Repositories\Util\AclPolicy;


class NoticeAwardController extends Controller {

  /*
  --------------------------------------------------------------------------
   Add Notice of Award
  --------------------------------------------------------------------------
  */
  public function add_notice_award(Request $request)
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
        $notice_status      = $request['notice_status'];
        $contactor_name     = $request['contactor_name'];
        $improvement_type   = $request['improvement_type'];
        $contact_amount     = $request['contact_amount'];
        $award_date         = $request['award_date'];
        $notice_path        = $request['notice_path'];
        // $notice_sign        = $request['notice_sign'];
        $project_id         = $request['project_id'];
        $user_id            = Auth::user()->id;
        $status             = 'active';
        $signatory_arr      = $request['signatory_arr'];
        $contractor_bond_required = $request['contractor_bond_required'];
        $pna_envelope_id    = '';
        $pna_docusign_status = 'pending';
    // Check User Permission Parameter 
    $user_id = Auth::user()->id;
    $permission_key = 'notice_award_add';
    $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
    if(count($check_single_user_permission) < 1){
      $result = array('code'=>403, "description"=>"Access Denies");
      return response()->json($result, 403);
    }
    else {
        if($notice_status == 'new'){
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
                                                array(
                                                    array(
                                                        "tabLabel" => "noa_company_name",
                                                        "value" => $row['noa_company_name']),
                                                        array (
                                                        "tabLabel" => "noa_company_address",
                                                        "value" => $row['noa_company_address']),
                                                        array (
                                                        "tabLabel" => "noa_project_name",
                                                        "value" => $row['noa_project_name']),
                                                        array (
                                                        "tabLabel" => "noa_improvement_type",
                                                        "value" => $row['noa_improvement_type']),
                                                        array (
                                                        "tabLabel" => "noa_bid_advertisement_date",
                                                        "value" => $row['noa_bid_advertisement_date'].'.'),
                                                        array (
                                                        "tabLabel" => "noa_bid_amount",
                                                        "value" => '$'.$row['noa_bid_amount']),
                                                        array (
                                                        "tabLabel" => "noa_date",
                                                        "value" => date('Y-m-d'))
                                                        );
//                        $data[$i]['email'] = $row['signatory_email'];
//                        $data[$i]['name'] = $row['signatory_name'];
//                        $data[$i]['recipientId'] = $i+1;
//                        $data[$i]['tabs']['textTabs'] = array(
//                                            array(
//                                                "anchorString"=> "By:",
//                                                "anchorXOffset"=>30*($i+1),
//                                                //"anchorYOffset" => "200",
//                                                "tabLabel"=>"By",
//                                                "text"=>"By",
//                                                "pageNumber"=>"1",
//                                                "name"=>"By",
//                                                "required"=>"TRUE",
//                                                "anchorIgnoreIfNotPresent"=>TRUE,
//                                            ),
//                                            array(
//                                                "anchorString"=> "Title:",
//                                                "anchorXOffset"=>30*($i+1),
//                                                //"anchorYOffset" => "200",
//                                                "tabLabel"=>"Title",
//                                                "text"=>"Title",
//                                                "pageNumber"=>"1",
//                                                "name"=>"Title",
//                                                "required"=>"TRUE",
//                                                "anchorIgnoreIfNotPresent"=>TRUE,
//                                            ));
//                        $data[$i]['tabs']['dateSignedTabs'] =   array(
//                                    array(
//                                        "anchorString"=>null,
//                                        "documentId" => "1",
//                                        "pageNumber" => "1",
//                                        "tabLabel"=> "Date Signed",
//                                        "name"=> "Date Signed",
//                                        "xPosition"=> 100*($i+1),
//                                        "yPosition"=> 510*($i+1),
//
//                                    )
//                                );
//                        $data[$i]['tabs']["signHereTabs"] = array(
//                                    array(
//                                            "xPosition" => 100*($i+1),
//                                            "yPosition" => 700*($i+1),
//                                            "documentId" => "1",
//                                            "pageNumber" => "1"
//                                    )
//                            );
                    }else{
                        $result = array('code'=>400,"data"=>array("description"=>"Signatory email is not valid.",'docusign'=>1,
                                            "notice_status"=>null,"contactor_name"=>null,"contact_amount"=>null,"award_date"=>null,"notice_path"=>null,"project_id"=>null));
                        return response()->json($result, 400);
                    }
                }
                if(count($data))
                {
//                    $docs = DB::table('documents')
//                        ->select('documents.*')
//                        ->where('doc_id', '=', $notice_path)
//                        ->first();
//                    //print_r($docs);die; 
//                    $documentFileName = env('APP_URL').$docs->doc_path;
                    $documentName = 'Notice Of Award';
                    $email = env('DOCUSIGN_EMAIL');
                    $password = env('DOCUSIGN_PASSWORD');
                    $integratorKey = env('DOCUSIGN_INTEGRATOR_KEY');
                    if($contractor_bond_required=="yes")
                        $templateId = "9d7e5608-9014-4a7d-894b-8010746a2910";
                    else
                        $templateId = "fe6491e4-04fa-40b7-8c0f-ec618f1a5a20";
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
//                    $data = 
//                            array (
//                                    "emailSubject" => "DocuSign API - Please sign " . $documentName,
//                                    "documents" => array( 
//                                            array("documentId" => "1", "name" => $documentName)
//                                    ),
//                                    "recipients" => array( 
//                                            "signers" => $data
//                                    ),
//                                    "status" => "sent"
//                    );
                    $data = array("accountId" => $accountId, 
		"emailSubject" => "Signature request for a Notice of Award",
		"emailBlurb" => "This is a signature request for a Notice of Award",
		"templateId" => $templateId, 
		"templateRoles" => $data,
                "brandId" => "120df7c0-bf59-4eb2-94fc-dd5b4aaf1d28",
		"status" => "sent");
                    $data_string = json_encode($data); 
                    //$file_contents = file_get_contents($documentFileName);
                    // Create a multi-part request. First the form data, then the file content
//                    $requestBody = 
//                             "\r\n"
//                            ."\r\n"
//                            ."--myboundary\r\n"
//                            ."Content-Type: application/json\r\n"
//                            ."Content-Disposition: form-data\r\n"
//                            ."\r\n"
//                            ."$data_string\r\n"
//                            ."--myboundary\r\n"
//                            ."Content-Type:application/pdf\r\n"
//                            ."Content-Disposition: file; filename=\"$documentName\"; documentid=1 \r\n"
//                            ."\r\n"
//                            ."$file_contents\r\n"
//                            ."--myboundary--\r\n"
//                            ."\r\n";
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
                    $pna_envelope_id = $response["envelopeId"];
                }
            }
            
//            print_r( [
//                    'ok' => true,
//                    'envelopeId' => $envelopeId,
//                    'accountId' => $accountId,
//                    'baseUrl' => $baseUrl
//            ]);
        
        
        
            //echo '<pre>';print_r($data);die;
          $information = array(
            "notice_status"   => $notice_status,
            "improvement_type"=> $improvement_type,
            "contactor_name"  => $contactor_name,
            "contact_amount"  => $contact_amount,
            "award_date"      => $award_date,
            "project_id"      => $project_id,
            "user_id"         => $user_id,
            "status"          => $status
          );

          $rules = [
            'notice_status'   => 'required',
            'improvement_type'=> 'required',
            'contactor_name'  => 'numeric',
            'contact_amount'  => 'required',
            'award_date'      => 'required',
            'project_id'      => 'required|numeric',
            'user_id'         => 'required|numeric',
            'status'          => 'required'
          ];
        }
        else {
          $information = array(
            "notice_path"     => $notice_path,
            "improvement_type"=> $improvement_type,
            "project_id"      => $project_id,
            "user_id"         => $user_id,
            "status"          => $status
          );

          $rules = [
            'notice_path'     => 'required',
            'improvement_type'=> 'required',
            'project_id'      => 'required|numeric',
            'user_id'         => 'required|numeric',
            'status'          => 'required'
          ];
        }

        // $information = array(
        //     "notice_status"   => $notice_status,
        //     // "contactor_name"  => $contactor_name,
        //     "project_id"      => $project_id,
        //     "user_id"         => $user_id,
        //     "status"          => $status
        // );

        // $rules = [
        //     'notice_status'   => 'required',
        //     // 'contactor_name'  => 'numeric',
        //     'project_id'      => 'required|numeric',
        //     'user_id'         => 'required|numeric',
        //     'status'          => 'required'
        // ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            // $query = DB::table('project_notice_award')
            // ->insert(['pna_notice_status' => $notice_status, 'pna_improvement_type' => $improvement_type, 'pna_contactor_name' => $contactor_name, 'pna_contact_amount' => $contact_amount, 'pna_award_date' => $award_date, 'pna_notice_path' => $notice_path, 'pna_project_id' => $project_id, 'pna_user_id' => $user_id, 'pna_status' => $status]);
            $notice_award = ProjectNoticeofAward::create(['pna_docusign_status'=>$pna_docusign_status,'pna_envelope_id'=>$pna_envelope_id,'pna_notice_status' => $notice_status, 'pna_improvement_type' => $improvement_type, 'pna_contactor_name' => $contactor_name, 'pna_contact_amount' => $contact_amount, 'pna_award_date' => $award_date, 'pna_notice_path' => $notice_path, 'pna_project_id' => $project_id, 'pna_user_id' => $user_id, 'pna_status' => $status]);

            $notice_award_id = $notice_award->id;

            if(count($notice_award) < 1)
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
                $permission_key       = 'notice_award_view_all';
                // Notification Parameter
                $project_id           = $project_id;
                $notification_title   = 'New notice of award added in Project: ' .$check_project_user->p_name;
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/notice_award";
                $date                 = date("M d, Y h:i a");
                $email_description    = 'A new notice of award has been added in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
                if(count($check_single_user_permission) < 1){
                  continue;
                }
                else {
                    $notification_key     = 'notice_of_award_upload';
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

              $result = array('description'=>$notice_award_id,'code'=>200);
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
   Update Notice of Award by passing sw_id
  --------------------------------------------------------------------------
  */
  public function update_notice_award(Request $request, $pna_id)
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
        // $notice_status      = $request['notice_status'];
        // $contactor_name     = $request['contactor_name'];
        // $currency           = $request['currency'];
        $notice_sign_owner        = $request['notice_sign_owner'];
        $notice_review_owner      = $request['notice_review_owner'];
        $notice_sign_contractor   = $request['notice_sign_contractor'];
        $notice_review_contractor = $request['notice_review_contractor'];
        $project_id               = $request['project_id'];
        $contactor_name     = $request['contactor_name'];
        $improvement_type   = $request['improvement_type'];
        $contact_amount     = $request['contact_amount'];
        $award_date         = $request['award_date'];
        $user_id                  = Auth::user()->id;
        $status                   = $request['status'];
    // Check User Permission Parameter 
    $user_id = Auth::user()->id;
    $permission_key = 'notice_award_update';
    $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
    if(count($check_single_user_permission) < 1){
      $result = array('code'=>403, "description"=>"Access Denies");
      return response()->json($result, 403);
    }
    else {    
        $information = array(
            // "notice_status"   => $notice_status,
            // "contactor_name"  => $contactor_name,
            "project_id"      => $project_id,
            "user_id"         => $user_id,
            "status"          => $status
        );

        $rules = [
            // 'notice_status'   => 'required',
            // 'contactor_name'  => 'numeric',
            'project_id'      => 'required|numeric',
            'user_id'         => 'required|numeric',
            'status'          => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {   
            $user_detail = DB::table('project_notice_award')
            ->select()
            ->where('pna_id', '=', $pna_id)
            ->first();
            if(($notice_sign_owner == "") ? $notice_sign_owner = $user_detail->pna_notice_sign_owner : $notice_sign_owner = $notice_sign_owner);
            if(($notice_review_owner == "") ? $notice_review_owner = $user_detail->pna_notice_review_owner : $notice_review_owner = $notice_review_owner);
            if(($notice_sign_contractor == "") ? $notice_sign_contractor = $user_detail->pna_notice_sign_contractor : $notice_sign_contractor = $notice_sign_contractor);
            if(($notice_review_contractor == "") ? $notice_review_contractor = $user_detail->pna_notice_review_contractor : $notice_review_contractor = $notice_review_contractor);

            $query = DB::table('project_notice_award')
            ->where('pna_id', '=', $pna_id)
            // ->update(['pna_notice_status' => $notice_status, 'pna_contactor_name' => $contactor_name, 'pna_currency' => $currency, 'pna_contact_amount' => $contact_amount, 'pna_award_date' => $award_date, 'pna_notice_path' => $notice_path, 'pna_notice_sign' => $notice_sign, 'pna_project_id' => $project_id, 'pna_user_id' => $user_id, 'pna_status' => $status]);
            ->update(['pna_improvement_type' => $improvement_type,'pna_contact_amount' => $contact_amount,'pna_contactor_name' => $contactor_name,'pna_award_date' => $award_date,'pna_project_id' => $project_id, 'pna_notice_sign_owner' => $notice_sign_owner, 'pna_notice_review_owner' => $notice_review_owner, 'pna_notice_sign_contractor' => $notice_sign_contractor, 'pna_notice_review_contractor' => $notice_review_contractor, 'pna_user_id' => $user_id, 'pna_status' => $status]);
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
                $permission_key       = 'notice_award_view_all';
                // Notification Parameter
                $project_id           = $project_id;
                $notification_title   = 'Notice of award updated in Project: ' .$check_project_user->p_name;
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/notice_award/";
                $date                 = date("M d, Y h:i a");
                $email_description    = 'Notice of award # '.$pna_id.' has been updated in Project : <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

                $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
                if(count($check_single_user_permission) < 1){
                  continue;
                }
                else {
                    $notification_key     = 'notice_of_award_upload';
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
   Get single Notice of Award by passing pna_id
  --------------------------------------------------------------------------
  */
  public function get_notice_award_single(Request $request, $pna_id)
  {
    try
    {
      $user = array(
        'userid'    => Auth::user()->id,
        'role'      => Auth::user()->role
      );
      // $user = (object) $user;
      // $post = new Resource_Post(); // You create a new resource Post instance
      // if (Gate::forUser($user)->denies('allow_admin', [$post,false])) { 
      //   $result = array('code'=>403, "description"=>"Access denies");
      //   return response()->json($result, 403);
      // } 
      // else {
        $query = DB::table('project_notice_award')
        ->leftJoin('project_firm', 'project_notice_award.pna_contactor_name', '=', 'project_firm.f_id')
        ->leftJoin('project_type_improvement', 'project_notice_award.pna_improvement_type', '=', 'project_type_improvement.pt_id')
        ->leftJoin('documents', 'project_notice_award.pna_notice_path', '=', 'documents.doc_id')
        ->leftJoin('documents as owner_sign', 'project_notice_award.pna_notice_sign_owner', '=', 'owner_sign.doc_id')
        ->leftJoin('documents as contractor_sign', 'project_notice_award.pna_notice_sign_contractor', '=', 'contractor_sign.doc_id')
        ->leftJoin('projects', 'project_notice_award.pna_project_id', '=', 'projects.p_id')
        ->leftJoin('users', 'project_notice_award.pna_user_id', '=', 'users.id')
        ->select('project_notice_award.*', 'project_firm.*', 'documents.*', 'owner_sign.doc_path as owner_sign_doc_path', 'contractor_sign.doc_path as contractor_sign_doc_path', 'projects.*', 'project_type_improvement.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('pna_id', '=', $pna_id)
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
   Get all Notice of Award by passing Project ID
  ----------------------------------------------------------------------------------
  */
  public function get_notice_award_project(Request $request, $project_id)
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
    $permission_key = 'notice_award_view_all';
    $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
    if(count($check_single_user_permission) < 1){
      $result = array('code'=>403, "description"=>"Access Denies");
      return response()->json($result, 403);
    }
    else { 
         $query = DB::table('project_notice_award')
          ->leftJoin('project_firm', 'project_notice_award.pna_contactor_name', '=', 'project_firm.f_id')
          ->leftJoin('project_type_improvement', 'project_notice_award.pna_improvement_type', '=', 'project_type_improvement.pt_id')
          ->leftJoin('project_settings', 'project_notice_award.pna_project_id', '=', 'project_settings.pset_project_id')
          ->leftJoin('currency', 'project_settings.pset_meta_value', '=', 'currency.cur_id')
          ->leftJoin('documents', 'project_notice_award.pna_notice_path', '=', 'documents.doc_id')
          ->leftJoin('projects', 'project_notice_award.pna_project_id', '=', 'projects.p_id')
          ->leftJoin('users', 'project_notice_award.pna_user_id', '=', 'users.id')
          ->select('currency.cur_symbol as currency_symbol', 'project_notice_award.*', 'project_firm.*', 'documents.*', 'projects.*', 'project_type_improvement.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('pna_project_id', '=', $project_id)
          ->groupBy('project_notice_award.pna_id')
          ->orderBy('project_notice_award.pna_id','ASC')
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
   Get default contractor by passing project id
  ----------------------------------------------------------------------------------
  */
  public function get_default_contractor_project(Request $request, $project_id)
  {
      try
      {
        $query = DB::table('project_notice_award')
          ->leftJoin('project_firm', 'project_notice_award.pna_contactor_name', '=', 'project_firm.f_id')
//          ->leftJoin('project_type_improvement', 'project_notice_award.pna_improvement_type', '=', 'project_type_improvement.pt_id')
//          ->leftJoin('project_settings', 'project_notice_award.pna_project_id', '=', 'project_settings.pset_project_id')
//          ->leftJoin('currency', 'project_settings.pset_meta_value', '=', 'currency.cur_id')
//          ->leftJoin('documents', 'project_notice_award.pna_notice_path', '=', 'documents.doc_id')
//          ->leftJoin('projects', 'project_notice_award.pna_project_id', '=', 'projects.p_id')
//          ->leftJoin('users', 'project_notice_award.pna_user_id', '=', 'users.id')
          ->select('project_firm.f_name as agency_name','project_notice_award.*')
          ->where('project_notice_award.pna_project_id', '=', $project_id)
          ->where('project_notice_award.pna_status', '=', 'active')
          ->groupBy('project_notice_award.pna_id')
          ->orderBy('project_notice_award.pna_id','ASC')
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
      catch(Exception $e)
      {
        return response()->json(['error' => 'Something is wrong'], 500);
      }
  }
}