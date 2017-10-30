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


class NoticeofProceedController extends Controller {

  /*
  --------------------------------------------------------------------------
   ADD NOTICE PROCEED
  --------------------------------------------------------------------------
  */
  public function add_notice_proceed(Request $request)
  {
    try
    {
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
        $sign_owner_id        = $request['sign_owner_id'];
        $sign_contractor_id   = $request['sign_contractor_id'];
        $user_id              = Auth::user()->id;
        $status               = 'active';

        $information = array(
            "contractor_name"     => $contractor_name,
            "date"                => $date,
            "start_date"          => $start_date,
            "duration"            => $duration,
            "liquidated_amount"   => $liquidated_amount,
            "path"                => $path,
            "project_id"          => $project_id,
            "sign_owner_id"       => $sign_owner_id,
            "sign_contractor_id"  => $sign_contractor_id,
            "user_id"             => $user_id,
            "status"              => $status
        );

        if($check_award_type == 'new'){
          $rules = [
            'contractor_name'     => 'required',
            // 'date'                => 'required',
            // 'start_date'          => 'required',
            'path'                => 'required',
            'project_id'          => 'required|numeric',
            'user_id'             => 'required|numeric',
            'sign_owner_id'       => 'required|numeric',
            'sign_contractor_id'  => 'required|numeric',
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
            'sign_owner_id'       => 'required|numeric',
            'sign_contractor_id'  => 'required|numeric',
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

          $get_document = DB::table('documents')
          ->select()
          ->where('doc_id', '=', $path)
          ->first();

          // print_r($get_document->doc_path);
          // echo '<br/>';
          $doc_path = str_replace("/upload","upload",$get_document->doc_path);

          $first_user = DB::table('users')
          ->select()
          ->where('id', '=', $sign_owner_id)
          ->first();
          $first_role   = $first_user->role;
          $first_email  = $first_user->email;
          $first_name   = $first_user->first_name." ".$first_user->last_name;
          $first_id     = $first_user->id;

          $second_user = DB::table('users')
          ->select()
          ->where('id', '=', $sign_contractor_id)
          ->first();
          $second_role   = $second_user->role;
          $second_email  = $second_user->email;
          $second_name   = $second_user->first_name." ".$second_user->last_name;
          $second_id     = $second_user->id;

// Document Upload Docusign
$email              = "faizan@sw.ai";      // your account email
$password           = "delldell";   // your account password
$integratorKey      = "80c4ca94-3c28-4158-bb85-66d7bccece00"; 
$recipientName      = "Faizan";
$templateId         = "882de972-4654-4d9a-b279-969a3ff7d51f";
$templateRoleName   = "Stratus";
$clientUserId       = "12345"; 
$documentName       = "Notice of Proceed";
$documentFileName   = $doc_path; // $get_document->doc_path;

// construct the authentication header:
$header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";
/////////////////////////////////////////////////////////////////////////////////////////////////
// STEP 1 - Login (retrieves baseUrl and accountId)
/////////////////////////////////////////////////////////////////////////////////////////////////
$url = "https://demo.docusign.net/restapi/v2/login_information";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-DocuSign-Authentication: $header"));
$json_response = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
if ( $status != 200 ) {
  echo "error calling webservice, status is:" . $status;
  exit(-1);
}
$response = json_decode($json_response, true);
$accountId = $response["loginAccounts"][0]["accountId"];
$baseUrl = $response["loginAccounts"][0]["baseUrl"];
curl_close($curl);
//--- display results   
// echo "accountId = " . $accountId . "\nbaseUrl = " . $baseUrl . "\n";
/////////////////////////////////////////////////////////////////////////////////////////////////
// STEP 2 - Create an envelope with an Embedded recipient (uses the clientUserId property)
/////////////////////////////////////////////////////////////////////////////////////////////////
$data = array(
  "accountId"     => $accountId, 
  "emailSubject"  => "Stratus - Please sign " . $documentName,
  "documents"     => array( 
    array("documentId" => "1", "name" => $documentName)
    ),
  "templateId"    => $templateId, 
  "templateRoles" => array(
          array(  
            "roleName"      => $first_role, 
            "email"         => $first_email, 
            "name"          => $first_name, 
            "clientUserId"  => $first_id),
          array(
            "roleName"      => $second_role, 
            "email"         => $second_email, 
            "name"          => $second_name, 
            "clientUserId"  => $second_id)
    ),
  "status"        => "created");                                                                    
$data_string = json_encode($data);
$file_contents = file_get_contents($documentFileName);
// Create a multi-part request. First the form data, then the file content
$requestBody = 
 "\r\n"
."\r\n"
."--myboundary\r\n"
."Content-Type: application/json\r\n"
."Content-Disposition: form-data\r\n"
."\r\n"
."$data_string\r\n"
."--myboundary\r\n"
."Content-Type:application/pdf\r\n"
."Content-Disposition: file; filename=\"$documentName\"; documentid=1 \r\n"
."\r\n"
."$file_contents\r\n"
."--myboundary--\r\n"
."\r\n";
// Send to the /envelopes end point, which is relative to the baseUrl received above.   
$curl = curl_init($baseUrl . "/envelopes" );
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);                                                                  
curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                    
  'Content-Type: multipart/form-data;boundary=myboundary',
  'Content-Length: ' . strlen($requestBody),
  "X-DocuSign-Authentication: $header" )                                                           
);
$json_response = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
if ( $status != 201 ) {
  echo "error calling webservice, status is:" . $status . "\nerror text is --> ";
  print_r($json_response); echo "\n";
  exit(-1);
}
$response = json_decode($json_response, true);
$envelopeId = $response["envelopeId"];
curl_close($curl);
//--- display results
// echo "Envelope created! Envelope ID: " . $envelopeId . "\n"; 

/////////////////////////////////////////////////////////////////////////////////////////////////
// STEP 3 - Get the Embedded Sending View (aka the "tag-and-send" view)
/////////////////////////////////////////////////////////////////////////////////////////////////
$data = array("returnUrl" => "http://sw.ai/staging/stratus/redirect_docusign");                                                                    
$data_string = json_encode($data);                                                                                   
$curl = curl_init($baseUrl . "/envelopes/$envelopeId/views/sender" );
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
  'Content-Type: application/json',                                                                                
  'Content-Length: ' . strlen($data_string),
  "X-DocuSign-Authentication: $header" )                                                                       
);
$json_response = curl_exec($curl);
$response = json_decode($json_response, true);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
if ( $status != 201 ) {
  echo "error calling webservice, status is:" . $status . "\nerror text is --> ";
  print_r($json_response); echo "\n";
  exit(-1);
}
$url = $response["url"];
//--- display results
// echo "Embedded URL is: \n\n" . $url . "\n\nNavigate to this URL to start the tag-and-send view of the envelope\n";
            if($check_award_type == 'new'){
              $query = DB::table('project_notice_of_proceed')
              ->insert(['pnp_contractor_name' => $contractor_name, 'pnp_type' => 'new', 'pnp_date' => $date, 'pnp_start_date' => $start_date, 'pnp_duration' => $duration, 'pnp_cal_day' => $cal_day, 'pnp_liquidated_amount' => $liquidated_amount, 'pnp_path' => $path, 'pnp_notice_sign_owner_id' => $sign_owner_id, 'pnp_notice_sign_contractor_id' => $sign_contractor_id, 'pnp_envelope_id' => $envelopeId, 'pnp_project_id' => $project_id, 'pnp_user_id' => $user_id, 'pnp_status' => 'active']);
            }
            else {
              $query = DB::table('project_notice_of_proceed')
              ->insert(['pnp_contractor_name' => $contractor_name, 'pnp_type' => 'exist', 'pnp_path' => $path, 'pnp_notice_sign_owner_id' => $sign_owner_id, 'pnp_notice_sign_contractor_id' => $sign_contractor_id, 'pnp_envelope_id' => $envelopeId, 'pnp_project_id' => $project_id, 'pnp_user_id' => $user_id, 'pnp_status' => 'active']);
              // ->insert(['pnp_contractor_name' => '1', 'pnp_type' => 'exist', 'pnp_path' => '1006', 'pnp_notice_sign_owner_id' => '1', 'pnp_notice_sign_contractor_id' => '30', 'pnp_envelope_id' => $envelopeId, 'pnp_project_id' => $project_id, 'pnp_user_id' => $user_id, 'pnp_status' => 'active']);
            }

            if(count($query) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {
              $result = array(
                'accountId'     => $accountId,
                'baseUrl'       => $baseUrl,
                'envelope_id'   => $envelopeId,
                'envelope_url'  => $url,
                'description'   => "Add notice of prceed successfully",
                'code'          => 200
              );
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
            $user_detail = DB::table('project_notice_of_proceed')
            ->select()
            ->where('pnp_id', '=', $pnp_id)
            ->first();
            if(($notice_sign_owner == "") ? $notice_sign_owner = $user_detail->pnp_notice_sign_owner : $notice_sign_owner = $notice_sign_owner);
            if(($notice_review_owner == "") ? $notice_review_owner = $user_detail->pnp_notice_review_owner : $notice_review_owner = $notice_review_owner);
            if(($notice_sign_contractor == "") ? $notice_sign_contractor = $user_detail->pnp_notice_sign_contractor : $notice_sign_contractor = $notice_sign_contractor);
            if(($notice_review_contractor == "") ? $notice_review_contractor = $user_detail->pnp_notice_review_contractor : $notice_review_contractor = $notice_review_contractor);

            $query = DB::table('project_notice_of_proceed')
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
                $user_id              = $check_project_user->id;
                $permission_key       = 'notice_proceed_view_all';
                // Notification Parameter
                $project_id           = $project_id;
                $notification_title   = 'Update notice of proceed # '.$pnp_id.' in Project: ' .$check_project_user->p_name;
                $url                  = App::make('url')->to('/');
                $link                 = "/dashboard/".$project_id."/notice_proceed";
                $date                 = date("M d, Y h:i a");
                $email_description    = 'Update notice of proceed # '.$pnp_id.' in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';

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
   Get single Notice Proceed by passing pnp_id
  --------------------------------------------------------------------------
  */
  public function get_notice_of_proceed_single(Request $request, $pnp_id)
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
        $query = DB::table('project_notice_of_proceed')
->leftJoin('project_firm as contractor_name', 'project_notice_of_proceed.pnp_contractor_name', '=', 'contractor_name.f_id')
->leftJoin('currency as liquidated_currency', 'project_notice_of_proceed.pnp_liquidated_currency', '=', 'liquidated_currency.cur_id')
->leftJoin('documents as path', 'project_notice_of_proceed.pnp_path', '=', 'path.doc_id')
->leftJoin('documents as owner_sign', 'project_notice_of_proceed.pnp_notice_sign_owner', '=', 'owner_sign.doc_id')
->leftJoin('documents as contractor_sign', 'project_notice_of_proceed.pnp_notice_sign_contractor', '=', 'contractor_sign.doc_id')
->leftJoin('projects', 'project_notice_of_proceed.pnp_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_notice_of_proceed.pnp_user_id', '=', 'users.id')
        ->select('contractor_name.f_name as contractor_name',
          'liquidated_currency.cur_symbol as liquidated_currency', 
          'path.doc_path as notice_proceed_path', 'project_notice_of_proceed.*', 'projects.*', 'owner_sign.doc_path as owner_sign_doc_path', 'contractor_sign.doc_path as contractor_sign_doc_path', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
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
  --------------------------------------------------------------------------
   Get single Notice Proceed by passing envelop_id
  --------------------------------------------------------------------------
  */
  public function get_envelop_status(Request $request, $envelop_id)
  {
    try
    {
      $email = "faizan@sw.ai";
      $password = "delldell";
      $integratorKey = "80c4ca94-3c28-4158-bb85-66d7bccece00";
      // copy the envelopeId from an existing envelope in your account that you want to query:
      $envelopeId = $envelop_id; 
      // construct the authentication header:
      $header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";
      /////////////////////////////////////////////////////////////////////////////////////////////////
      // STEP 1 - Login (retrieves baseUrl and accountId)
      /////////////////////////////////////////////////////////////////////////////////////////////////
      $url = "https://demo.docusign.net/restapi/v2/login_information";
      $curl = curl_init($url);
      curl_setopt($curl, CURLOPT_HEADER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-DocuSign-Authentication: $header"));
      $json_response = curl_exec($curl);
      $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      if ( $status != 200 ) {
        echo "error calling webservice, status is:" . $status;
        exit(-1);
      }
      $response = json_decode($json_response, true);
      $accountId = $response["loginAccounts"][0]["accountId"];
      $baseUrl = $response["loginAccounts"][0]["baseUrl"];
      curl_close($curl);
      //--- display results
      // echo "\naccountId = " . $accountId . "\nbaseUrl = " . $baseUrl . "\n";
      
      /////////////////////////////////////////////////////////////////////////////////////////////////
      // STEP 2 - Get envelope information
      /////////////////////////////////////////////////////////////////////////////////////////////////
      // $data_string = json_encode($data);                                                                                   
      $curl = curl_init($baseUrl . "/envelopes/" . $envelopeId . "/recipients" );
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                           
        "X-DocuSign-Authentication: $header" )                              
      );
      $json_response = curl_exec($curl);
      $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      if ( $status != 200 ) {
        echo "error calling webservice, status is:" . $status . "\nError text --> ";
        print_r($json_response); echo "\n";
        exit(-1);
      }
      return $response = json_decode($json_response, true);
      // return $response = $json_response;
      // $result = array('data'=>$response,'code'=>200);
      // return response()->json($result, 200);
    }
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }


  /*
  --------------------------------------------------------------------------
   Download document by passing envelop_id
  --------------------------------------------------------------------------
  */
  public function get_document_docusign(Request $request, $envelop_id)
  {
    try
    {
      $doc_path   = $request['doc_path'];
      $doc_name   = $request['doc_name'];

      $information = array(
          "doc_path"   => $doc_path,
          "doc_name"   => $doc_name
      );

      $rules = [
          "doc_path"   => 'required',
          "doc_name"   => 'required'
      ];
      $validator = Validator::make($information, $rules);
      if ($validator->fails()) 
      {
          return $result = response()->json(["data" => $validator->messages()],400);
      }
      else
      {
        $email = "faizan@sw.ai";
        $password = "delldell";
        $integratorKey = "80c4ca94-3c28-4158-bb85-66d7bccece00";
        $envelopeId = $envelop_id;
        // construct the authentication header:
        $header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";
        /////////////////////////////////////////////////////////////////////////////////////////////////
        // STEP 1 - Login (retrieves baseUrl and accountId)
        /////////////////////////////////////////////////////////////////////////////////////////////////
        $url = "https://demo.docusign.net/restapi/v2/login_information";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-DocuSign-Authentication: $header"));
        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ( $status != 200 ) {
          echo "error calling webservice, status is:" . $status;
          exit(-1);
        }
        $response = json_decode($json_response, true);
        $accountId = $response["loginAccounts"][0]["accountId"];
        $baseUrl = $response["loginAccounts"][0]["baseUrl"];
        curl_close($curl);
        //--- display results
        // echo "accountId = " . $accountId . "\nbaseUrl = " . $baseUrl . "\n";
        /////////////////////////////////////////////////////////////////////////////////////////////////
        // STEP 2 - Get document information
        /////////////////////////////////////////////////////////////////////////////////////////////////      
        $curl = curl_init($baseUrl . "/envelopes/" . $envelopeId . "/documents" );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
          "X-DocuSign-Authentication: $header" )                                                                       
        );
        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ( $status != 200 ) {
          echo "error calling webservice, status is:" . $status;
          exit(-1);
        }
        $response = json_decode($json_response, true);
        curl_close($curl);
        //--- display results
        // echo "Envelope has following document(s) information...\n";
        // echo '<pre>';
        // print_r($response); echo "\n";
        // echo '</pre>';
        $document_url = $response["envelopeDocuments"][0]["uri"];
        /////////////////////////////////////////////////////////////////////////////////////////////////
        // STEP 3 - Download the envelope's documents
        /////////////////////////////////////////////////////////////////////////////////////////////////
        // foreach( $response["envelopeDocuments"] as $document ) {
          $docUri = $document_url;
          // echo $baseUrl.$document_url;
          $curl = curl_init($baseUrl . $docUri );
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);  
          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  
          curl_setopt($curl, CURLOPT_HTTPHEADER, array(                    
            "X-DocuSign-Authentication: $header" )                              
          );
          $data = curl_exec($curl);

          $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
          if ( $status != 200 ) {
            echo "error calling webservice, status is:" . $status;
            exit(-1);
          }
          file_put_contents($doc_path.$doc_name, $data);
          curl_close($curl);
        // }
        //--- display results
        // echo "Envelope document(s) have been downloaded, check your local directory.\n";
        $result = array('data'=>$document_url,'code'=>200);
        return response()->json($result, 200);
        // return $response = json_decode($document_url, true);
        // return $response = $json_response;
        // $result = array('data'=>$response,'code'=>200);
        // return response()->json($result, 200);
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
  public function get_notice_of_proceed_project(Request $request, $project_id)
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
          $query = DB::table('project_notice_of_proceed')
->leftJoin('project_firm as contractor_name', 'project_notice_of_proceed.pnp_contractor_name', '=', 'contractor_name.f_id')
->leftJoin('currency as liquidated_currency', 'project_notice_of_proceed.pnp_liquidated_currency', '=', 'liquidated_currency.cur_id')
->leftJoin('documents as path', 'project_notice_of_proceed.pnp_path', '=', 'path.doc_id')
->leftJoin('projects', 'project_notice_of_proceed.pnp_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_notice_of_proceed.pnp_user_id', '=', 'users.id')
        ->select('contractor_name.f_name as contractor_name',
          'liquidated_currency.cur_symbol as liquidated_currency', 
          'path.doc_path as notice_proceed_path',  
          'project_notice_of_proceed.*', 'projects.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('pnp_project_id', '=', $project_id)
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
          $query = DB::table('project_notice_of_proceed')
->leftJoin('project_firm as contractor_name', 'project_notice_of_proceed.pnp_contractor_name', '=', 'contractor_name.f_id')
->leftJoin('currency as liquidated_currency', 'project_notice_of_proceed.pnp_liquidated_currency', '=', 'liquidated_currency.cur_id')
->leftJoin('documents as path', 'project_notice_of_proceed.pnp_path', '=', 'path.doc_id')
->leftJoin('projects', 'project_notice_of_proceed.pnp_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_notice_of_proceed.pnp_user_id', '=', 'users.id')
        ->select('contractor_name.f_name as contractor_name',
          'liquidated_currency.cur_symbol as liquidated_currency', 
          'path.doc_path as notice_proceed_path',  
          'project_notice_of_proceed.*', 'projects.*', 
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