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
use App\ProjectCertificate; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post; 
use App\Repositories\Util\AclPolicy;


class CertificateController extends Controller {

  /*
  --------------------------------------------------------------------------
   Add Certificate
  --------------------------------------------------------------------------
  */
  public function add_certificate(Request $request)
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
      
        $company_name                 = $request['company_name'];
        $liability_currency           = $request['liability_currency'];
        $liability_limit              = $request['liability_limit'];
        $liability_exp                = $request['liability_exp'];
        $liability_required_min       = $request['liability_required_min'];
        $liability_cert_path          = $request['liability_cert_path'];
        $work_comp_currency           = $request['work_comp_currency'];
        $work_comp_limit              = $request['work_comp_limit'];
        $work_comp_exp                = $request['work_comp_exp'];
        $works_comp_include_above     = $request['works_comp_include_above'];
        $works_comp_required_min      = $request['works_comp_required_min'];
        $works_comp_not_include       = $request['works_comp_not_include'];
        $work_comp_cert_path          = $request['work_comp_cert_path'];
        $auto_liability_currency      = $request['auto_liability_currency'];
        $auto_liability_limit         = $request['auto_liability_limit'];
        $auto_liability_exp           = $request['auto_liability_exp'];
        $auto_include_above           = $request['auto_include_above'];
        $auto_liability_required_min  = $request['auto_liability_required_min'];
        $auto_liability_not_include   = $request['auto_liability_not_include'];
        $auto_liability_cert_path     = $request['auto_liability_cert_path'];

        $umbrella_currency            = $request['umbrella_currency'];
        $umbrella_limit               = $request['umbrella_limit'];
        $umbrella_exp                 = $request['umbrella_exp'];
        $umbrella_include_above       = $request['umbrella_include_above'];
        $umbrella_required_min        = $request['umbrella_required_min'];
        $umbrella_not_include         = $request['umbrella_not_include'];
        $umbrella_cert_path           = $request['umbrella_cert_path'];
        $upload_doc_id_certificate    = $request['upload_doc_id_certificate'];
        $project_id                   = $request['project_id'];
        $user_id                      = Auth::user()->id;
        $status                       = 'active';
        // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'certificate_add';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $information = array(
            "company_name"              => $company_name,
            "liability_exp"             => $liability_exp,
            "liability_required_min"    => $liability_required_min,
            "liability_limit"           => $liability_limit,
            "liability_cert_path"       => $liability_cert_path,
            "work_comp_limit"           => $work_comp_limit,
            "work_comp_exp"             => $work_comp_exp,
            "work_comp_cert_path"       => $work_comp_cert_path,
            "auto_liability_limit"      => $auto_liability_limit,
            "auto_liability_exp"        => $auto_liability_exp,
            "auto_liability_cert_path"  => $auto_liability_cert_path,
            "umbrella_limit"            => $umbrella_limit,
            "umbrella_exp"              => $umbrella_exp,
            "umbrella_cert_path"        => $umbrella_cert_path,
            "project_id"                => $project_id,
            "user_id"                   => $user_id,
            "status"                    => $status
        );

        if(empty($umbrella_limit)){
          $rules = [
            'company_name'              => 'numeric',
            'liability_exp'             => 'required',
            // 'liability_required_min'    => 'required',
            'liability_limit'           => 'required|numeric',
            //'liability_cert_path'       => 'required|numeric',
            'work_comp_limit'           => 'required|numeric',
            'work_comp_exp'             => 'required',
            //'work_comp_cert_path'       => 'required|numeric',
            'auto_liability_limit'      => 'required|numeric',
            'auto_liability_exp'        => 'required',
            //'auto_liability_cert_path'  => 'required|numeric',
            'project_id'                => 'required|numeric',
            'user_id'                   => 'required|numeric',
            'status'                    => 'required'
          ];
        }
        else {
          $rules = [
            'company_name'              => 'numeric',
            'liability_exp'             => 'required',
            // 'liability_required_min'    => 'required',
            'liability_limit'           => 'required|numeric',
            //'liability_cert_path'       => 'required|numeric',
            'work_comp_limit'           => 'required|numeric',
            'work_comp_exp'             => 'required',
            //'work_comp_cert_path'       => 'required|numeric',
            'auto_liability_limit'      => 'required|numeric',
            'auto_liability_exp'        => 'required',
            //'auto_liability_cert_path'  => 'required|numeric',
            'umbrella_limit'            => 'required|numeric',
            'umbrella_exp'              => 'required',
            'umbrella_cert_path'        => 'required|numeric',
            'project_id'                => 'required|numeric',
            'user_id'                   => 'required|numeric',
            'status'                    => 'required'
          ];
        }
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            // $query = DB::table('project_certificate')
            $project_certificate = ProjectCertificate::create(['ci_company_name' => $company_name, 'ci_liability_currency' => $liability_currency, 'ci_liability_limit' => $liability_limit, 'ci_liability_exp' => $liability_exp, 'ci_liability_required_min' => $liability_required_min, 'ci_liability_cert_path' => $liability_cert_path, 'ci_work_comp_currency' => $work_comp_currency, 'ci_work_comp_limit' => $work_comp_limit, 'ci_work_comp_exp' => $work_comp_exp, 'ci_works_comp_include_above' => $works_comp_include_above, 'ci_works_comp_required_min' => $works_comp_required_min, 'ci_works_comp_not_include' => $works_comp_not_include, 'ci_work_comp_cert_path' => $work_comp_cert_path, 'ci_auto_liability_currency' => $auto_liability_currency,  'ci_auto_liability_limit' => $auto_liability_limit,  'ci_auto_liability_exp' => $auto_liability_exp, 'ci_auto_include_above' => $auto_include_above, 'ci_auto_liability_required_min' => $auto_liability_required_min, 'ci_auto_liability_not_include' => $auto_liability_not_include, 'ci_auto_liability_cert_path' => $auto_liability_cert_path, 'ci_umbrella_liability_currency' => $umbrella_currency,  'ci_umbrella_liability_limit' => $umbrella_limit,  'ci_umbrella_liability_exp' => $umbrella_exp, 'ci_umbrella_liability_above' => $umbrella_include_above, 'ci_umbrella_liability_required_min' => $umbrella_required_min, 'ci_umbrella_liability_not_include' => $umbrella_not_include, 'ci_umbrella_liability_cert_path' => $umbrella_cert_path, 'ci_doc_id_certificate' => $upload_doc_id_certificate, 'ci_project_id' => $project_id, 'ci_user_id' => $user_id, 'ci_status' => $status]);

            $project_certificate_id = $project_certificate->id;

            if(count($project_certificate) < 1)
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
//                $permission_key       = 'certificate_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Add new Certificate of Insurance in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/certificate/".$project_certificate->id;
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Add new Certificate of Insurance in Project: <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
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

              $result = array('description'=>$project_certificate_id,'code'=>200);
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
   Update Certificate by passing ci_id
  --------------------------------------------------------------------------
  */
  public function update_certificate(Request $request, $ci_id)
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
        // $liability_currency           = $request['liability_currency'];
        // $liability_limit              = $request['liability_limit'];
        // $liability_exp                = $request['liability_exp'];
        // $liability_required_min       = $request['liability_required_min'];
        // $liability_cert_path          = $request['liability_cert_path'];
        // $work_comp_currency           = $request['work_comp_currency'];
        // $work_comp_limit              = $request['work_comp_limit'];
        // $work_comp_exp                = $request['work_comp_exp'];
        // $works_comp_required_min      = $request['works_comp_required_min'];
        // $work_comp_cert_path          = $request['work_comp_cert_path'];
        // $auto_liability_currency      = $request['auto_liability_currency'];
        // $auto_liability_limit         = $request['auto_liability_limit'];
        // $auto_liability_exp           = $request['auto_liability_exp'];
        // $auto_liability_required_min  = $request['auto_liability_required_min'];
        // $auto_liability_cert_path     = $request['auto_liability_cert_path'];
        $project_id                   = $request['project_id'];
        $user_id                      = Auth::user()->id;
          $liability_currency           = $request['liability_currency'];
        $liability_limit              = $request['liability_limit'];
        $liability_exp                = $request['liability_exp'];
        $liability_required_min       = $request['liability_required_min'];
        $liability_cert_path          = $request['liability_cert_path'];
        $work_comp_currency           = $request['work_comp_currency'];
        $work_comp_limit              = $request['work_comp_limit'];
        $work_comp_exp                = $request['work_comp_exp'];
        $works_comp_include_above     = $request['works_comp_include_above'];
        $works_comp_required_min      = $request['works_comp_required_min'];
        $works_comp_not_include       = $request['works_comp_not_include'];
        $work_comp_cert_path          = $request['work_comp_cert_path'];
        $auto_liability_currency      = $request['auto_liability_currency'];
        $auto_liability_limit         = $request['auto_liability_limit'];
        $auto_liability_exp           = $request['auto_liability_exp'];
        $auto_include_above           = $request['auto_include_above'];
        $auto_liability_required_min  = $request['auto_liability_required_min'];
        $auto_liability_not_include   = $request['auto_liability_not_include'];
        $auto_liability_cert_path     = $request['auto_liability_cert_path'];

        $umbrella_currency            = $request['umbrella_currency'];
        $umbrella_limit               = $request['umbrella_limit'];
        $umbrella_exp                 = $request['umbrella_exp'];
        $umbrella_include_above       = $request['umbrella_include_above'];
        $umbrella_required_min        = $request['umbrella_required_min'];
        $umbrella_not_include         = $request['umbrella_not_include'];
        $umbrella_cert_path           = $request['umbrella_cert_path'];
        $upload_doc_id_certificate    = $request['upload_doc_id_certificate'];
        $status                       = $request['status'];
    // Check User Permission Parameter 
    $user_id = Auth::user()->id;
    $permission_key = 'certificate_update';
    $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
    if(count($check_single_user_permission) < 1){
      $result = array('code'=>403, "description"=>"Access Denies");
      return response()->json($result, 403);
    }
    else {
        $information = array(
            // "liability_limit"       => $liability_limit,
            // "liability_exp"         => $liability_exp,
            // "liability_cert_path"   => $liability_cert_path,
            // "work_comp_limit"       => $work_comp_limit,
            // "auto_liability_limit"  => $auto_liability_limit
            "liability_exp"             => $liability_exp,
            "liability_required_min"    => $liability_required_min,
            "liability_limit"           => $liability_limit,
            "liability_cert_path"       => $liability_cert_path,
            "work_comp_limit"           => $work_comp_limit,
            "work_comp_exp"             => $work_comp_exp,
            "work_comp_cert_path"       => $work_comp_cert_path,
            "auto_liability_limit"      => $auto_liability_limit,
            "auto_liability_exp"        => $auto_liability_exp,
            "auto_liability_cert_path"  => $auto_liability_cert_path,
            "umbrella_limit"            => $umbrella_limit,
            "umbrella_exp"              => $umbrella_exp,
            "umbrella_cert_path"        => $umbrella_cert_path,
            "project_id"                => $project_id,
            "user_id"                   => $user_id,
            "status"                    => $status
        );

           if(empty($umbrella_limit)){
          $rules = [
            'company_name'              => 'numeric',
            'liability_exp'             => 'required',
            // 'liability_required_min'    => 'required',
            'liability_limit'           => 'required|numeric',
            //'liability_cert_path'       => 'required|numeric',
            'work_comp_limit'           => 'required|numeric',
            'work_comp_exp'             => 'required',
            //'work_comp_cert_path'       => 'required|numeric',
            'auto_liability_limit'      => 'required|numeric',
            'auto_liability_exp'        => 'required',
            //'auto_liability_cert_path'  => 'required|numeric',
            'project_id'                => 'required|numeric',
            'user_id'                   => 'required|numeric',
            'status'                    => 'required'
          ];
        }
        else {
          $rules = [
            'company_name'              => 'numeric',
            'liability_exp'             => 'required',
            // 'liability_required_min'    => 'required',
            'liability_limit'           => 'required|numeric',
            //'liability_cert_path'       => 'required|numeric',
            'work_comp_limit'           => 'required|numeric',
            'work_comp_exp'             => 'required',
            //'work_comp_cert_path'       => 'required|numeric',
            'auto_liability_limit'      => 'required|numeric',
            'auto_liability_exp'        => 'required',
            //'auto_liability_cert_path'  => 'required|numeric',
            'umbrella_limit'            => 'required|numeric',
            'umbrella_exp'              => 'required',
            'umbrella_cert_path'        => 'required|numeric',
            'project_id'                => 'required|numeric',
            'user_id'                   => 'required|numeric',
            'status'                    => 'required'
          ];
        }
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_certificate')
            ->where('ci_id', '=', $ci_id)
            // ->update(['ci_liability_currency' => $liability_currency, 'ci_liability_limit' => $liability_limit, 'ci_liability_exp' => $liability_exp, 'ci_liability_required_min' => $liability_required_min, 'ci_liability_cert_path' => $liability_cert_path, 'ci_work_comp_currency' => $work_comp_currency, 'ci_work_comp_limit' => $work_comp_limit, 'ci_work_comp_exp' => $work_comp_exp, 'ci_works_comp_required_min' => $works_comp_required_min, 'ci_work_comp_cert_path' => $work_comp_cert_path, 'ci_auto_liability_currency' => $auto_liability_currency,  'ci_auto_liability_limit' => $auto_liability_limit, 'ci_auto_liability_exp' => $auto_liability_exp, 'ci_auto_liability_required_min' => $auto_liability_required_min, 'ci_auto_liability_cert_path' => $auto_liability_cert_path, 'ci_project_id' => $project_id, 'ci_user_id' => $user_id, 'ci_status' => $status]);
            ->update([ 'ci_liability_currency' => $liability_currency, 'ci_liability_limit' => $liability_limit, 'ci_liability_exp' => $liability_exp, 'ci_liability_required_min' => $liability_required_min, 'ci_liability_cert_path' => $liability_cert_path, 'ci_work_comp_currency' => $work_comp_currency, 'ci_work_comp_limit' => $work_comp_limit, 'ci_work_comp_exp' => $work_comp_exp, 'ci_works_comp_include_above' => $works_comp_include_above, 'ci_works_comp_required_min' => $works_comp_required_min, 'ci_works_comp_not_include' => $works_comp_not_include, 'ci_work_comp_cert_path' => $work_comp_cert_path, 'ci_auto_liability_currency' => $auto_liability_currency,  'ci_auto_liability_limit' => $auto_liability_limit,  'ci_auto_liability_exp' => $auto_liability_exp, 'ci_auto_include_above' => $auto_include_above, 'ci_auto_liability_required_min' => $auto_liability_required_min, 'ci_auto_liability_not_include' => $auto_liability_not_include, 'ci_auto_liability_cert_path' => $auto_liability_cert_path, 'ci_umbrella_liability_currency' => $umbrella_currency,  'ci_umbrella_liability_limit' => $umbrella_limit,  'ci_umbrella_liability_exp' => $umbrella_exp,'ci_umbrella_liability_cert_path' => $umbrella_cert_path, 'ci_doc_id_certificate' => $upload_doc_id_certificate, 'ci_project_id' => $project_id, 'ci_user_id' => $user_id, 'ci_status' => $status]);
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
//                $permission_key       = 'certificate_view_all';
//                // Notification Parameter
//                $project_id           = $project_id;
//                $notification_title   = 'Update status Certificate of Insurance in Project: ' .$check_project_user->p_name;
//                $url                  = App::make('url')->to('/');
//                $link                 = "/dashboard/".$project_id."/certificate/".$ci_id;
//                $date                 = date("M d, Y h:i a");
//                $email_description    = 'Update status Certificate of Insurance # '.$ci_id.' in Project : <strong>'.$check_project_user->p_name.'</strong> <a href="'.$url.$link.'"> Click Here to see </a>';
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
   Update Certificate PDF ID by passing ci_id
  --------------------------------------------------------------------------
  */
  public function update_certificate_pdf(Request $request, $ci_id)
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
        $doc_id_certificate           = $request['doc_id_certificate'];
        $user_id                      = Auth::user()->id;
        $ci_id                        = $ci_id;

        $information = array(
            "doc_id_certificate"        => $doc_id_certificate,
            "user_id"                   => $user_id,
            "ci_id"                     => $ci_id
        );

        $rules = [
            'doc_id_certificate'      => 'required|numeric',
            'user_id'                 => 'required|numeric',
            'ci_id'                   => 'required|numeric'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_certificate')
            ->where('ci_id', '=', $ci_id)
            ->update(['ci_doc_id_certificate' => $doc_id_certificate, 'ci_user_id' => $user_id]);
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
      // }
    }
    catch(Exception $e)
    {
      return response()->json(['error' => 'Something is wrong'], 500);
    }
  }

  /*
  --------------------------------------------------------------------------
   Get single Certificate by passing ci_id
  --------------------------------------------------------------------------
  */
  public function get_certificate_single(Request $request, $ci_id)
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
        $query = DB::table('project_certificate')
->leftJoin('project_firm', 'project_certificate.ci_company_name', '=', 'project_firm.f_id')
->leftJoin('currency as liability_currency', 'project_certificate.ci_liability_currency', '=', 'liability_currency.cur_id')
->leftJoin('documents as liability_cert_path', 'project_certificate.ci_liability_cert_path', '=', 'liability_cert_path.doc_id')
->leftJoin('currency as work_comp_currency', 'project_certificate.ci_work_comp_currency', '=', 'work_comp_currency.cur_id')
->leftJoin('documents as work_comp_cert_path', 'project_certificate.ci_work_comp_cert_path', '=', 'work_comp_cert_path.doc_id')
->leftJoin('currency as auto_liability_currency', 'project_certificate.ci_auto_liability_currency', '=', 'auto_liability_currency.cur_id')
->leftJoin('documents as auto_liability_cert_path', 'project_certificate.ci_auto_liability_cert_path', '=', 'auto_liability_cert_path.doc_id')
->leftJoin('currency as umbrella_liability_currency', 'project_certificate.ci_umbrella_liability_currency', '=', 'umbrella_liability_currency.cur_id')
->leftJoin('documents as umbrella_liability', 'project_certificate.ci_umbrella_liability_cert_path', '=', 'umbrella_liability.doc_id')
->leftJoin('documents as doc_cert_path', 'project_certificate.ci_doc_id_certificate', '=', 'doc_cert_path.doc_id')
->leftJoin('projects', 'project_certificate.ci_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_certificate.ci_user_id', '=', 'users.id')
        ->select('project_firm.f_name as agency_name', 'liability_currency.cur_symbol as liability_currency', 'project_certificate.ci_liability_limit as liability_limit', 
          'project_certificate.ci_liability_exp as liability_exp', 
          'project_certificate.ci_liability_required_min as liability_required_min', 
          'liability_cert_path.doc_path as liability_cert_path', 
          'liability_cert_path.doc_id as liability_cert_path_id', 
          'work_comp_currency.cur_symbol as work_comp_currency',
          'project_certificate.ci_works_comp_include_above as ci_works_comp_include_above',
          'project_certificate.ci_auto_liability_required_min as ci_auto_liability_required_min',
          'project_certificate.ci_auto_liability_not_include as ci_auto_liability_not_include',

          'project_certificate.ci_auto_include_above as ci_auto_include_above',
          'project_certificate.ci_works_comp_required_min as ci_works_comp_required_min',
          'project_certificate.ci_works_comp_not_include as ci_works_comp_not_include',

          'project_certificate.ci_work_comp_limit as work_comp_limit', 
          'project_certificate.ci_work_comp_exp as work_comp_exp', 
          'project_certificate.ci_works_comp_required_min as works_comp_required_min', 
          'project_certificate.ci_work_comp_limit as work_comp_limit', 
          'project_certificate.ci_work_comp_exp as work_comp_exp', 
          'project_certificate.ci_works_comp_required_min as works_comp_required_min', 
          'work_comp_cert_path.doc_path as work_comp_cert_path', 
          'work_comp_cert_path.doc_id as work_comp_cert_path_id', 
          'auto_liability_currency.cur_symbol as auto_liability_currency',
          'project_certificate.ci_auto_liability_limit as auto_liability_limit', 
          'project_certificate.ci_auto_liability_exp as auto_liability_exp', 
          'project_certificate.ci_auto_liability_required_min as auto_liability_required_min', 
          'auto_liability_cert_path.doc_path as auto_liability_cert_path',
          'auto_liability_cert_path.doc_id as auto_liability_cert_path_id',
          'umbrella_liability_currency.cur_symbol as umbrella_liability_currency', 
          'project_certificate.ci_umbrella_liability_limit as umbrella_liability_limit', 
          'project_certificate.ci_umbrella_liability_exp as umbrella_liability_exp', 
          'umbrella_liability.doc_path as umbrella_liability_cert_path',
          'umbrella_liability.doc_id as umbrella_liability_cert_path_id',
          'doc_cert_path.doc_path as doc_cert_path',  
          'projects.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role', 
          'project_certificate.ci_status as status', 
          'project_certificate.ci_timestamp as timestamp')
        ->where('ci_id', '=', $ci_id)
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
   Get all custom Certificate by passing ci_id & project_id
  --------------------------------------------------------------------------
  */
  public function get_custom_certificate_all(Request $request, $project_id, $ci_id)
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
        $query = DB::table('project_certificate_multiple')
->leftJoin('currency', 'project_certificate_multiple.pcm_currency', '=', 'currency.cur_id')
->leftJoin('documents', 'project_certificate_multiple.pcm_cert_path', '=', 'documents.doc_id')
        ->select('project_certificate_multiple.*', 'currency.cur_symbol',
          'documents.doc_path as doc_cert_path')
        ->where('project_certificate_multiple.pcm_project_id', '=', $project_id)
        ->where('project_certificate_multiple.pcm_parent_id', '=', $ci_id)
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
   Get all Certificate by passing Project ID
  ----------------------------------------------------------------------------------
  */
  public function get_certificate_project(Request $request, $project_id)
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
      $permission_key = 'certificate_view_all';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
         $query = DB::table('project_certificate')
->leftJoin('project_firm', 'project_certificate.ci_company_name', '=', 'project_firm.f_id')
->leftJoin('currency as liability_currency', 'project_certificate.ci_liability_currency', '=', 'liability_currency.cur_id')
->leftJoin('documents as liability_cert_path', 'project_certificate.ci_liability_cert_path', '=', 'liability_cert_path.doc_id')
->leftJoin('currency as work_comp_currency', 'project_certificate.ci_work_comp_currency', '=', 'work_comp_currency.cur_id')
->leftJoin('documents as work_comp_cert_path', 'project_certificate.ci_work_comp_cert_path', '=', 'work_comp_cert_path.doc_id')
->leftJoin('currency as auto_liability_currency', 'project_certificate.ci_auto_liability_currency', '=', 'auto_liability_currency.cur_id')
->leftJoin('documents as auto_liability_cert_path', 'project_certificate.ci_auto_liability_cert_path', '=', 'auto_liability_cert_path.doc_id')
->leftJoin('currency as umbrella_liability_symbol', 'project_certificate.ci_umbrella_liability_currency', '=', 'umbrella_liability_symbol.cur_id')
->leftJoin('documents as umbrella_liability', 'project_certificate.ci_umbrella_liability_cert_path', '=', 'umbrella_liability.doc_id')
->leftJoin('documents as doc_cert_path', 'project_certificate.ci_doc_id_certificate', '=', 'doc_cert_path.doc_id')
->leftJoin('projects', 'project_certificate.ci_project_id', '=', 'projects.p_id')
->leftJoin('users', 'project_certificate.ci_user_id', '=', 'users.id')
        ->select('project_firm.f_name as agency_name', 
          'project_certificate.ci_id', 
          'liability_currency.cur_symbol as liability_currency', 
          'project_certificate.ci_liability_limit as liability_limit', 
          'project_certificate.ci_liability_exp as liability_exp', 
          'project_certificate.ci_liability_required_min as liability_required_min', 
          'liability_cert_path.doc_path as liability_cert_path', 
          'work_comp_currency.cur_symbol as work_comp_currency',
          'project_certificate.ci_work_comp_limit as work_comp_limit', 
          'project_certificate.ci_work_comp_exp as work_comp_exp', 
          'project_certificate.ci_works_comp_required_min as works_comp_required_min', 
          'work_comp_cert_path.doc_path as work_comp_cert_path', 
          'auto_liability_currency.cur_symbol as auto_liability_currency',
          'project_certificate.ci_auto_liability_limit as auto_liability_limit', 
          'project_certificate.ci_auto_liability_exp as auto_liability_exp', 
          'project_certificate.ci_auto_liability_required_min as auto_liability_required_min', 
          'auto_liability_cert_path.doc_path as auto_liability_cert_path', 
          'umbrella_liability_symbol.cur_symbol as umbrella_liability_symbol', 
          'project_certificate.ci_umbrella_liability_limit as umbrella_liability_limit', 
          'project_certificate.ci_umbrella_liability_exp as umbrella_liability_exp', 
          'umbrella_liability.doc_path as umbrella_liability_cert_path',
          'doc_cert_path.doc_path as doc_cert_path',  
          'projects.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role', 
          'project_certificate.ci_status as status', 
          'project_certificate.ci_timestamp as timestamp')
          ->where('ci_project_id', '=', $project_id)
          ->orderBy('project_certificate.ci_id','ASC')
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
   Add Custom Certificate
  --------------------------------------------------------------------------
  */
  public function add_custom_certificate(Request $request)
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
        $pcm_name                     = $request['pcm_name'];
        $pcm_currency                 = $request['pcm_currency'];
        $pcm_limit                    = $request['pcm_limit'];
        $pcm_exp                      = $request['pcm_exp'];
        $pcm_cert_path                = $request['pcm_cert_path'];
        $pcm_parent_id                = $request['pcm_parent_id'];
        $project_id                   = $request['project_id'];
        $user_id                      = Auth::user()->id;

        $information = array(
            "pcm_name"                  => $pcm_name,
            "pcm_currency"              => $pcm_currency,
            "pcm_limit"                 => $pcm_limit,
            "pcm_exp"                   => $pcm_exp,
            "pcm_cert_path"             => $pcm_cert_path,
            "pcm_parent_id"             => $pcm_parent_id,
            "project_id"                => $project_id,
            "user_id"                   => $user_id
        );

        $rules = [
            'pcm_name'                  => 'required',
            'pcm_currency'              => 'required',
            'pcm_limit'                 => 'required|numeric',
            'pcm_exp'                   => 'required',
            'pcm_cert_path'             => 'required|numeric',
            'pcm_parent_id'             => 'required|numeric',
            'project_id'                => 'required|numeric',
            'user_id'                   => 'required|numeric'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('project_certificate_multiple')
            ->insert(['pcm_name' => $pcm_name, 'pcm_currency' => $pcm_currency, 'pcm_limit' => $pcm_limit, 'pcm_exp' => $pcm_exp, 'pcm_cert_path' => $pcm_cert_path, 'pcm_parent_id' => $pcm_parent_id, 'pcm_project_id' => $project_id, 'pcm_user_id' => $user_id]);
            
            if(count($query) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {
              $result = array('description'=>"Custom Certificate Added Successfully",'code'=>200);
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


  public function groupdoc(Request $request)
    {
        try
        {

//   $privateKey = '8cdca759585fca9f4bb059fba6a4c8e4'; 
//   $userId = '2bd3b5accbcbb2ff';
//            //Create signer object
// $signer = new GroupDocsRequestSigner($privateKey);
// //Create apiClient object
// $apiClient = new APIClient($signer);
// $api = new AntAPI($apiClient);
// $response = $api->ListAnnotations($userId, $fileId);

$signer = new GroupDocsRequestSigner("8cdca759585fca9f4bb059fba6a4c8e4");
 // Create apiClient object
$apiClient = new APIClient($signer);
 // Create Annotation object
$antApi = new AntApi($apiClient);
 //Set base path
$antApi ->setBasePath("https://api.groupdocs.com/v2.0");
 // Get all collaborators for document
$collaborators = $antApi->GetAnnotationCollaborators("2bd3b5accbcbb2ff", "document guid");

print_r($collaborators);
exit;
          F3::set('userId', '');
          F3::set('privateKey', '');
          F3::set('fileId', '');
          $clientId = F3::get('POST["clientId"]');
          $privateKey = F3::get('POST["privateKey"]');

          if (empty($clientId) || empty($privateKey)) {
              $error = 'Please enter all required parameters';
              F3::set('error', $error);
          } else {

          $basePath = F3::get('POST["basePath"]');
              F3::set('userId', $clientId);
              F3::set('privateKey', $privateKey);

               $signer = new GroupDocsRequestSigner($privateKey);

               $apiClient = new APIClient($signer); // PHP SDK Version > 1.0

               $mgmtApi = new MgmtApi($apiClient);

               if ($basePath == "") {

                $basePath = 'https://api.groupdocs.com/v2.0';
              }
               $mgmtApi->setBasePath($basePath);
              try {

               $userAccountInfo = $mgmtApi->GetUserProfile($clientId);

               if ($userAccountInfo->status == "Ok") {

                F3::set('userInfo', $userAccountInfo->result->user);
                  } else {
                      throw new Exception($userAccountInfo->error_message);
                  }
              } catch (Exception $e) {
                  $error = 'ERROR: ' . $e->getMessage() . "\n";
                  F3::set('error', $error);
              }
          }

          echo 'faizan1';
          
        }
        catch(Exception $e)
        {
          return response()->json(['error' => 'Something is wrong'], 500);
        }
    }


}