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
use App\Document; // your model
use App\ProjectBidDocument; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post; 
use App\Repositories\Util\AclPolicy;


class BiddocumentController extends Controller {

  /*
  --------------------------------------------------------------------------
   Add BID DOCUMENTS
  --------------------------------------------------------------------------
  */
  public function add_bid_documents(Request $request)
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

        $type_of_improvement            = $request['type_of_improvement'];
        $lead_agency                    = $request['lead_agency'];
        $bid_advertisement_date         = $request['bid_advertisement_date'];
        $add_applicable                 = $request['add_applicable'];
        $invite_date                    = $request['invite_date'];
        $invite_applicable              = $request['invite_applicable'];
        $date_of_opening                = $request['date_of_opening'];

        //$date_of_addendum               = $request['date_of_addendum'];
        //$addendum_applicable            = $request['addendum_applicable'];
        //$upload_addendum_path           = $request['upload_addendum_path'];

        $addvertisement_of_bid_path     = $request['addvertisement_of_bid_path'];
        $notice_invite_bid_path         = $request['notice_invite_bid_path'];
        $detail_result_path             = $request['detail_result_path'];
        $low_bidder_name                = $request['low_bidder_name'];
        $sucessful_bidder_proposal_path = $request['sucessful_bidder_proposal_path'];
        $project_id                     = $request['project_id'];
        $user_id                        = Auth::user()->id;
        $status                         = 'active';

      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'bid_document_add';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            "lead_agency"                     => $lead_agency,
            "low_bidder_name"                 => $low_bidder_name,
            "bid_advertisement_date"          => $bid_advertisement_date,
            "invite_date"                     => $invite_date,
            "date_of_opening"                 => $date_of_opening,
            "sucessful_bidder_proposal_path"  => $sucessful_bidder_proposal_path,
            "addvertisement_of_bid_path"      => $addvertisement_of_bid_path,
            "notice_invite_bid_path"          => $notice_invite_bid_path,
            "detail_result_path"              => $detail_result_path,
            "project_id"                      => $project_id,
            "user_id"                         => $user_id,
            "status"                          => $status
        );

        $rules = [
            'lead_agency'                     => 'required|numeric',
            'low_bidder_name'                 => 'required|numeric',
            'date_of_opening'                 => 'required',
            'sucessful_bidder_proposal_path'  => 'required'];
        if($add_applicable=="no")
        {
            $rules['addvertisement_of_bid_path']= 'required';
            $rules['bid_advertisement_date']= 'required';
            $rules['invite_date']= 'required';
            $rules['notice_invite_bid_path']    = 'required';
        }
            $rules['detail_result_path']              = 'required';
            $rules['project_id']                      ='required|numeric';
            $rules['user_id']                         = 'required|numeric';
            $rules['status']                          = 'required';
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            //$query = DB::table('project_bid_documents')
            // ->insert(['bd_type_of_improvement' => $type_of_improvement, 'bd_lead_agency' => $lead_agency, 'bd_bid_advertisement_date' => $bid_advertisement_date, 'bd_add_applicable' => $add_applicable, 'bd_invite_date' => $invite_date, 'bd_invite_applicable' => $invite_applicable, 'bd_date_of_opening' => $date_of_opening, 'bd_date_of_addendum' => $date_of_addendum, 'bd_addendum_applicable' => $addendum_applicable, 'bd_upload_addendum_path' => $upload_addendum_path, 'bd_addvertisement_of_bid_path' => $addvertisement_of_bid_path, 'bd_notice_invite_bid_path' => $notice_invite_bid_path, 'bd_detail_result_path' => $detail_result_path, 'bd_low_bidder_name' => $low_bidder_name, 'bd_sucessful_bidder_proposal_path' => $sucessful_bidder_proposal_path, 'bd_project_id' => $project_id, 'bd_user_id' => $user_id, 'bd_status' => $status]);

            $query = DB::table('projects')
            ->select()
            ->where('p_id', '=', $project_id)
            ->first();

            if($type_of_improvement == 'null'){
              $type_of_improvement = $query->p_type;
            }
            else {
              $type_of_improvement = $type_of_improvement;
            }
          
             $bid_document = ProjectBidDocument::create(['bd_type_of_improvement' => $type_of_improvement, 'bd_lead_agency' => $lead_agency, 'bd_bid_advertisement_date' => $bid_advertisement_date, 'bd_add_applicable' => $add_applicable, 'bd_invite_date' => $invite_date, 'bd_invite_applicable' => $invite_applicable, 'bd_date_of_opening' => $date_of_opening, 'bd_addvertisement_of_bid_path' => $addvertisement_of_bid_path, 'bd_notice_invite_bid_path' => $notice_invite_bid_path, 'bd_detail_result_path' => $detail_result_path, 'bd_low_bidder_name' => $low_bidder_name, 'bd_sucessful_bidder_proposal_path' => $sucessful_bidder_proposal_path, 'bd_project_id' => $project_id, 'bd_user_id' => $user_id, 'bd_status' => $status]);

            $bid_document_id = $bid_document->id;
         //  print_r($bid_document_id);
         //   exit;

            if(count($bid_document) < 1)
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
//                $permission_key       = 'bid_document_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Add new Bid Documents in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/bid_documents/".$bid_document->id;
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'New Bid Documents added in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
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

              $result = array('description'=>$bid_document_id,'code'=>200);
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
   Update Bid Documents passing bd_id
  --------------------------------------------------------------------------
  */
  public function update_bid_documents(Request $request, $bd_id)
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
        // $type_of_improvement                  = $request['type_of_improvement'];
        // $lead_agency                          = $request['lead_agency'];
        // $bid_advertisement_date               = $request['bid_advertisement_date'];
        // $add_applicable                       = $request['add_applicable'];
        // $invite_date                          = $request['invite_date'];
        // $invite_applicable                    = $request['invite_applicable'];
        // $date_of_opening                      = $request['date_of_opening'];
        // $date_of_addendum                     = $request['date_of_addendum'];
        // $addendum_applicable                  = $request['addendum_applicable'];
        // $upload_addendum_path                 = $request['upload_addendum_path'];
        // $addvertisement_of_bid_path           = $request['addvertisement_of_bid_path'];
        // $notice_invite_bid_path               = $request['notice_invite_bid_path'];
        // $detail_result_path                   = $request['detail_result_path'];
        // $low_bidder_name                      = $request['low_bidder_name'];
        // $sucessful_bidder_proposal_path       = $request['sucessful_bidder_proposal_path'];
        $project_id                           = $request['project_id'];
        $user_id                              = Auth::user()->id;
        $status                               = $request['status'];
        
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'bid_document_update';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            // "lead_agency"                     => $lead_agency,
            // "bid_advertisement_date"          => $bid_advertisement_date,
            // "low_bidder_name"                 => $low_bidder_name,
            // "sucessful_bidder_proposal_path"  => $sucessful_bidder_proposal_path,
            "project_id"                      => $project_id,
            "user_id"                         => $user_id,
            "status"                          => $status
        );

        $rules = [
            // 'lead_agency'                     => 'required|numeric',
            // 'bid_advertisement_date'          => 'required',
            // 'low_bidder_name'                 => 'required|numeric',
            // 'sucessful_bidder_proposal_path'  => 'required|numeric',
            'project_id'                      => 'required|numeric',
            'user_id'                         => 'required|numeric',
            'status'                          => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_bid_documents')
            ->where('bd_id', '=', $bd_id)
            // ->update(['bd_type_of_improvement' => $type_of_improvement, 'bd_lead_agency' => $lead_agency, 'bd_bid_advertisement_date' => $bid_advertisement_date, 'bd_add_applicable' => $add_applicable, 'bd_invite_date' => $invite_date, 'bd_invite_applicable' => $invite_applicable, 'bd_date_of_opening' => $date_of_opening, 'bd_date_of_addendum' => $date_of_addendum, 'bd_addendum_applicable' => $addendum_applicable, 'bd_upload_addendum_path' => $upload_addendum_path, 'bd_addvertisement_of_bid_path' => $addvertisement_of_bid_path, 'bd_notice_invite_bid_path' => $notice_invite_bid_path, 'bd_detail_result_path' => $detail_result_path, 'bd_low_bidder_name' => $low_bidder_name, 'bd_sucessful_bidder_proposal_path' => $sucessful_bidder_proposal_path, 'bd_project_id' => $project_id, 'bd_user_id' => $user_id, 'bd_status' => $status]);
            ->update(['bd_project_id' => $project_id, 'bd_user_id' => $user_id, 'bd_status' => $status]);
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
//                $permission_key       = 'bid_document_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Update status Bid Documents # '.$bd_id.'  in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/bid_documents/".$bd_id;
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Update status Bid Documents # '.$bd_id.' in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
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
   Get single bid documents by passing db_id
  --------------------------------------------------------------------------
  */
  public function get_bid_documents_single(Request $request, $bd_id)
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
        $query = DB::table('project_bid_documents')
  ->leftJoin('project_firm as lead_agency', 'project_bid_documents.bd_lead_agency', '=', 'lead_agency.f_id')
  ->leftJoin('projects as type_of_improvement', 'project_bid_documents.bd_type_of_improvement', '=', 'type_of_improvement.p_id')
  // ->leftJoin('documents as upload_addendum_path', 'project_bid_documents.bd_upload_addendum_path', '=', 'upload_addendum_path.doc_id')
  ->leftJoin('documents as addvertisement_bid_path', 'project_bid_documents.bd_addvertisement_of_bid_path', '=', 'addvertisement_bid_path.doc_id')
  ->leftJoin('documents as detail_result_path', 'project_bid_documents.bd_detail_result_path', '=', 'detail_result_path.doc_id')
  ->leftJoin('documents as notice_invite_bid_path', 'project_bid_documents.bd_notice_invite_bid_path', '=', 'notice_invite_bid_path.doc_id')
  ->leftJoin('project_firm as low_bidder_name', 'project_bid_documents.bd_low_bidder_name', '=', 'low_bidder_name.f_id')
  ->leftJoin('documents as sucessful_bidder_proposal_path', 'project_bid_documents.bd_sucessful_bidder_proposal_path', '=', 'sucessful_bidder_proposal_path.doc_id')
  ->leftJoin('projects', 'project_bid_documents.bd_project_id', '=', 'projects.p_id')

  ->leftJoin('users', 'project_bid_documents.bd_user_id', '=', 'users.id')
  ->select('project_bid_documents.*', 'project_bid_documents.bd_id as bd_id', 'project_bid_documents.bd_status as bd_status',
    'lead_agency.f_name as lead_agency', 
    'type_of_improvement.p_description as type_of_improvement', 
    // 'upload_addendum_path.doc_path as upload_addendum_path', 
    'addvertisement_bid_path.doc_path as addvertisement_bid_path', 
    'detail_result_path.doc_path as detail_result_path', 
    'notice_invite_bid_path.doc_path as notice_invite_bid_path', 
    'low_bidder_name.f_name as low_bidder_name',
    'sucessful_bidder_proposal_path.doc_path as sucessful_bidder_proposal_path', 
    'projects.*',
    'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('bd_id', '=', $bd_id)
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
   Get all Bid Documents by passing Project ID
  ----------------------------------------------------------------------------------
  */
  public function get_bid_documents_project(Request $request, $project_id)
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
        $permission_key = 'bid_document_view_all';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
          $query = DB::table('project_bid_documents')
  ->leftJoin('project_firm as lead_agency', 'project_bid_documents.bd_lead_agency', '=', 'lead_agency.f_id')
  ->leftJoin('projects as type_of_improvement', 'project_bid_documents.bd_type_of_improvement', '=', 'type_of_improvement.p_id')
  //->leftJoin('documents as upload_addendum_path', 'project_bid_documents.bd_upload_addendum_path', '=', 'upload_addendum_path.doc_id')
  ->leftJoin('documents as addvertisement_bid_path', 'project_bid_documents.bd_addvertisement_of_bid_path', '=', 'addvertisement_bid_path.doc_id')
  ->leftJoin('documents as detail_result_path', 'project_bid_documents.bd_detail_result_path', '=', 'detail_result_path.doc_id')
  ->leftJoin('documents as notice_invite_bid_path', 'project_bid_documents.bd_notice_invite_bid_path', '=', 'notice_invite_bid_path.doc_id')
  ->leftJoin('project_firm as low_bidder_name', 'project_bid_documents.bd_low_bidder_name', '=', 'low_bidder_name.f_id')
  ->leftJoin('documents as sucessful_bidder_proposal_path', 'project_bid_documents.bd_sucessful_bidder_proposal_path', '=', 'sucessful_bidder_proposal_path.doc_id')
  ->leftJoin('projects', 'project_bid_documents.bd_project_id', '=', 'projects.p_id')

  ->leftJoin('users', 'project_bid_documents.bd_user_id', '=', 'users.id')
  ->select('project_bid_documents.*',
    'lead_agency.f_name as lead_agency', 
    'type_of_improvement.p_description as type_of_improvement', 
   // 'upload_addendum_path.doc_path as upload_addendum_path', 
    'addvertisement_bid_path.doc_path as addvertisement_bid_path', 
    'detail_result_path.doc_path as detail_result_path', 
    'notice_invite_bid_path.doc_path as notice_invite_bid_path', 
    'low_bidder_name.f_name as low_bidder_name',
    'sucessful_bidder_proposal_path.doc_path as sucessful_bidder_proposal_path', 
    'projects.*',
    'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
          ->where('bd_project_id', '=', $project_id)
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
  --------------------------------------------------------------------------
   Add Addendum
  --------------------------------------------------------------------------
  */
  public function add_addendum(Request $request)
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
        $bid_document_id                = $request['bid_document_id'];
        $bid_addendum_date              = $request['bid_addendum_date'];
        $bid_doc_path                   = $request['bid_doc_path'];
        $bid_addendum_number            = $request['bid_addendum_number'];
        $bid_addendum_project_id        = $request['bid_addendum_project_id'];
        $user_id                        = Auth::user()->id;

        $information = array(
            "bid_document_id"           => $bid_document_id,
            "bid_addendum_date"         => $bid_addendum_date,
            "bid_doc_path"              => $bid_doc_path,
            "bid_addendum_number"       => $bid_addendum_number,
            "bid_addendum_project_id"   => $bid_addendum_project_id,
            "user_id"                   => $user_id,
        );

        $rules = [
            'bid_document_id'           => 'required|numeric',
            'bid_addendum_date'         => 'required',
            'bid_doc_path'              => 'required|numeric',
            'bid_addendum_number'       => 'required|numeric',
            'bid_addendum_project_id'   => 'required|numeric',
            'user_id'                   => 'required|numeric',
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_bid_documents_upload')
            ->insert(['pbd_bid_doc_id' => $bid_document_id, 'pbd_issue_date' => $bid_addendum_date, 'pbd_path' => $bid_doc_path, 'pbd_addendum' => $bid_addendum_number, 'pbd_project_id' => $bid_addendum_project_id, 'pbd_user_id' => $user_id]);
        
            if(count($query) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {
              $result = array('description'=>'add successfully addendum','code'=>200);
              return response()->json($result, 200);
            }
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
   Get all addendum by passing db_id
  --------------------------------------------------------------------------
  */
  public function get_addendum_bid_document(Request $request, $bd_id)
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
        $query = DB::table('project_bid_documents_upload')
  ->leftJoin('documents', 'project_bid_documents_upload.pbd_path', '=', 'documents.doc_id')
  ->leftJoin('projects', 'project_bid_documents_upload.pbd_project_id', '=', 'projects.p_id')

  ->leftJoin('users', 'project_bid_documents_upload.pbd_user_id', '=', 'users.id')
  ->select('project_bid_documents_upload.*', 'projects.*', 'documents.*',
    'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('pbd_bid_doc_id', '=', $bd_id)
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


}