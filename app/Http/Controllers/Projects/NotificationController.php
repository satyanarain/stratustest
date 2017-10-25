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


class NotificationController extends Controller {


    /*
    --------------------------------------------------------------------------
     Add Notifications
    --------------------------------------------------------------------------
    */
    public function add_notification($notification_title, $link, $project_id, $receiver_user_id)
    {
        try
        {    
            $description     = $notification_title;
            $link            = $link;
            $project_id      = $project_id;
            $receiver_user_id= $receiver_user_id;
            $status          = '1';
            $sender_user_id  = Auth::user()->id;

            $information = array(

                "pn_description"      => $description,
                "pn_link"             => $link,
                "pn_status"           => $status,
                "pn_project_id"       => $project_id,
                "pn_user_id"          => $sender_user_id,
                "pn_receiver_user_id" => $receiver_user_id

            );
            $rules = [
                "pn_description"      => 'required',
                "pn_link"             => 'required',
                "pn_status"           => 'required|numeric',
                "pn_project_id"       => 'required|numeric',
                "pn_user_id"          => 'required|numeric',
                "pn_receiver_user_id" => 'required|numeric'
            ];

            $validator = Validator::make($information, $rules);
            if ($validator->fails())
            {
                return $result = response()->json(["data" => $validator->messages()],400);
            }
            else
            {
                $query = DB::table('project_notifications')
                ->insert(['pn_description' => $description, 'pn_link' => $link, 'pn_status' => $status, 'pn_project_id' => $project_id, 'pn_sender_user_id' => $sender_user_id, 'pn_receiver_user_id' => $receiver_user_id]);
                if(count($query) < 1)
                {
                  $result = array('code'=>400, "description"=>"No records found");
                  return response()->json($result, 400);
                }
                else
                {
                  $result = array('description'=>"Add notification successfully",'code'=>200);
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
   Get Notification Short by passing user_id
  --------------------------------------------------------------------------
  */
  public function get_notification_short(Request $request, $user_id)
  {
    try
    {
        $query = DB::table('project_notifications')
        ->leftJoin('projects', 'project_notifications.pn_project_id', '=', 'projects.p_id')
        ->leftJoin('users', 'project_notifications.pn_sender_user_id', '=', 'users.id')
        ->select('project_notifications.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        // ->where('pn_receiver_user_id', '=', $user_id)
        ->where('pn_receiver_user_id', '=', $user_id)
        ->orderBy('pn_id', 'desc')
        ->limit(5)
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

  /*
  --------------------------------------------------------------------------
   Get Notification All by passing user_id
  --------------------------------------------------------------------------
  */
  public function get_notification_all(Request $request, $user_id)
  {
    try
    {
        $query = DB::table('project_notifications')
        ->leftJoin('projects', 'project_notifications.pn_project_id', '=', 'projects.p_id')
        ->leftJoin('users', 'project_notifications.pn_sender_user_id', '=', 'users.id')
        ->select('project_notifications.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('pn_receiver_user_id', '=', $user_id)
        ->orderBy('pn_id', 'desc')
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


  /*
  --------------------------------------------------------------------------
   Get Notification Unread by passing user_id
  --------------------------------------------------------------------------
  */
  public function get_notification_unread(Request $request, $user_id)
  {
    try
    {
        $query = DB::table('project_notifications')
        ->select(DB::raw('count(pn_status) as unread_count'))
        ->where('pn_receiver_user_id', '=', $user_id)
        ->where('pn_status', '=', 1)
        ->orderBy('pn_id', 'desc')
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

  /*
  --------------------------------------------------------------------------
   Update Notification status by passing pn_id
  --------------------------------------------------------------------------
  */
  public function change_notification_status(Request $request, $pn_id)
  {
    try
    {
        $query = DB::table('project_notifications')
        ->where('pn_id', '=', $pn_id)
        ->update(['pn_status' => 0]);
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