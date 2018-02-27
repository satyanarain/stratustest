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


class PictureVideoController extends Controller {

    public function getMediaName($no)
    {
        $array = array('1'=>'a','2'=>'b','3'=>'c','4'=>'d','5'=>'e','6'=>'f','7'=>'g','8'=>'h','9'=>'i','10'=>'j','11'=>'k','12'=>'l','13'=>'m','14'=>'n','15'=>'o','16'=>'p','17'=>'q','18'=>'r','19'=>'s','20'=>'t','21'=>'u','22'=>'v','23'=>'w','24'=>'x','25'=>'y','26'=>'z');
        $new_name = '';
        
        if($no<26)
        {
            $new_name = $array[$no];
        }else{
            $name = round($no/26);
            if($name>26)
            {
                $name1 = ($name/26);
                
            }else{
                //for
            } 
        }
            
        return $new_name;
    }
    /*
    --------------------------------------------------------------------------
    Add Standards
    --------------------------------------------------------------------------
    */
    public function add_picture(Request $request)
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
            // $name             = $request['name'];
            $taken_by         = $request['taken_by'];
            $pic_type         = $request['pic_type'];
            $doc_id           = $request['doc_id'];
            $description      = $request['description'];
            $project_id       = $request['project_id'];
            $ppv_taken_on     = $request['ppv_taken_on'];
            $date             = date('Y-m-d');
            $user_id          = Auth::user()->id;
            $status           = 'active';
          // Check User Permission Parameter 
          $user_id = Auth::user()->id;
          $permission_key = 'project_picture_video_add';
          $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
          if(count($check_single_user_permission) < 1){
            $result = array('code'=>403, "description"=>"Access Denies");
            return response()->json($result, 403);
          }
          else {
            if($pic_type == 'image/png' || $pic_type == 'image/jpg' || $pic_type == 'image/jpeg'){
              $name = 'Pictures-'. date('Y-md');
              $query = DB::table('project_picture_video')
              ->select()
              ->where('ppv_date', '=', $date)
              ->count();
              if($query == 0){
                $name = $name;
              }
              else {
                $latest_name = $this->getMediaName($query);   
                //$query = $query+1;
                $name = $name.' '.$latest_name;
              }
            }
            elseif($pic_type == 'video/mp4' || $pic_type == 'video/mp3' || $pic_type == 'video/avi' || $pic_type == 'video/mkv'){
              $name = 'Videos-'. date('Y-md');
              $query = DB::table('project_picture_video')
              ->select()
              ->where('ppv_date', '=', $date)
              ->count();
              if($query == 0){
                $name = $name;
              }
              else {
                //$query = $query+1;
                //$name = $name.', '.$query;
                $latest_name = $this->getMediaName($query);   
                $name = $name.' '.$latest_name;
              }
            }
            else {
              $name = date('Y-m-d');

              $query = DB::table('project_picture_video')
              ->select()
              ->where('ppv_date', '=', $date)
              ->count();
              if($query == 0){
                $name = $name;
              }
              else {
                $query = $query+1;
                $name = $name.', '.$query;
              }
            }
            // print_r($name)
            // exit;

            $information = array(
                // "name"            => $name,
                "taken_by"        => $taken_by,
                "pic_type"        => $pic_type,
                "description"     => $description,
                "doc_id"          => $doc_id,
                "project_id"      => $project_id,
                "user_id"         => $user_id,
                "status"          => $status,
                "ppv_taken_on"    => $ppv_taken_on
            );

            $rules = [
                // 'name'            => 'required',
                'taken_by'        => 'required',
                'pic_type'        => 'required',
                'description'     => 'required',
                'doc_id'          => 'required|numeric',
                'project_id'      => 'required|numeric',
                'user_id'         => 'required|numeric',
                'status'          => 'required',
                'ppv_taken_on'    => 'required'
            ];
            $validator = Validator::make($information, $rules);
            if ($validator->fails()) 
            {
                return $result = response()->json(["data" => $validator->messages()],400);
            }
            else
            {
                $query = DB::table('project_picture_video')
                ->insert(['ppv_taken_on'=>$ppv_taken_on,'ppv_name' => $name, 'ppv_taken_by' => $taken_by, 'ppv_type' => $pic_type, 'ppv_description' => $description, 'ppv_doc_id' => $doc_id, 'ppv_date' => $date, 'ppv_project_id' => $project_id, 'ppv_user_id' => $user_id, 'ppv_status' => $status]);

                if(count($query) < 1)
                {
                  $result = array('code'=>400, "description"=>"No records found");
                  return response()->json($result, 400);
                }
                else
                {
                  $result = array('description'=>"Added successfully",'code'=>200);
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
    Update Standard by passing pic_id
    --------------------------------------------------------------------------
    */
    public function update_picture(Request $request, $pic_id)
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
            $user_id          = Auth::user()->id;
            $status           = 'deactive';
            
            $information = array(
                "user_id"         => $user_id,
                "status"          => $status,
            );

            $rules = [
                'user_id'         => 'required|numeric',
                'status'          => 'required',
            ];
            $validator = Validator::make($information, $rules);
            if ($validator->fails()) 
            {
                return $result = response()->json(["data" => $validator->messages()],400);
            }
            else
            {
                $query = DB::table('project_picture_video')
                ->where('ppv_id', '=', $pic_id)
                ->update(['ppv_user_id' => $user_id, 'ppv_status' => $status]);
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
    Get picture by passing pic_id
    --------------------------------------------------------------------------
    */
    public function get_picture_single(Request $request, $pic_id)
    {
        try
        {
          // $pic_id            = $pic_id;
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
            $query = DB::table('project_picture_video')
            ->join('documents', 'project_picture_video.ppv_doc_id', '=', 'documents.doc_id')
            ->leftJoin('projects', 'project_picture_video.ppv_project_id', '=', 'projects.p_id')
            ->leftJoin('users', 'project_picture_video.ppv_user_id', '=', 'users.id')
            ->select('project_picture_video.*', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
            ->where('ppv_id', '=', $pic_id)
            ->where('project_picture_video.ppv_status', '=', 'active')
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
    --------------------------------------------------------------------------
    Get picture single by passing doc parent id ppv_doc_id
    --------------------------------------------------------------------------
    */
    public function get_picture_parent_id_single(Request $request, $ppv_doc_id)
    {
        try
        {
          // $ppv_doc_id            = $ppv_doc_id;
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
            $query = DB::table('project_picture_video')
            ->join('documents', 'project_picture_video.ppv_doc_id', '=', 'documents.doc_id')
            ->leftJoin('projects', 'project_picture_video.ppv_project_id', '=', 'projects.p_id')
            ->leftJoin('users', 'project_picture_video.ppv_user_id', '=', 'users.id')
            ->select('project_picture_video.*', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
            ->where('ppv_doc_id', '=', $ppv_doc_id)
            ->where('project_picture_video.ppv_status', '=', 'active')
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
    Get all picture & videos by padding project ID  
    ----------------------------------------------------------------------------------
    */
    public function get_project_picture(Request $request, $project_id)
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
        $permission_key = 'project_picture_video_view_all';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
        if(isset($request['ppv_sort_by']) && $request['ppv_sort_by'])  
        {
            $sort_arr = explode(' ',$request['ppv_sort_by']);
            $query = DB::table('project_picture_video')
            ->join('documents', 'project_picture_video.ppv_doc_id', '=', 'documents.doc_id')
            ->leftJoin('projects', 'project_picture_video.ppv_project_id', '=', 'projects.p_id')
            ->leftJoin('users', 'project_picture_video.ppv_user_id', '=', 'users.id')
            ->select('project_picture_video.*', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
            ->where('doc_project_id', '=', $project_id)
            ->where('project_picture_video.ppv_status', '=', 'active')
            ->orderBy($sort_arr[0],$sort_arr[1])
            ->get();
        }else
            {
            $query = DB::table('project_picture_video')
            ->join('documents', 'project_picture_video.ppv_doc_id', '=', 'documents.doc_id')
            ->leftJoin('projects', 'project_picture_video.ppv_project_id', '=', 'projects.p_id')
            ->leftJoin('users', 'project_picture_video.ppv_user_id', '=', 'users.id')
            ->select('project_picture_video.*', 'documents.*', 'projects.*', 'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
            ->where('doc_project_id', '=', $project_id)
            ->where('project_picture_video.ppv_status', '=', 'active')
            ->orderBy('ppv_timestamp', 'asc')
            ->get();
            }
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
    Get no ofcount picture passing project ID  
    ----------------------------------------------------------------------------------
    */
    public function get_project_count_picture(Request $request, $project_id)
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
        $permission_key = 'project_picture_video_view_all';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
          $query = DB::table('project_picture_video')
          ->select('project_picture_video.*')
          ->where('ppv_project_id', '=', $project_id)
          ->where('project_picture_video.ppv_status', '=', 'active')
          ->whereIn('ppv_type', array('image/png','image/jpg','image/jpeg'))
          ->count();
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
    Get no of count video passing project ID  
    ----------------------------------------------------------------------------------
    */
    public function get_project_count_video(Request $request, $project_id)
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
        $permission_key = 'project_picture_video_view_all';
        $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
        if(count($check_single_user_permission) < 1){
          $result = array('code'=>403, "description"=>"Access Denies");
          return response()->json($result, 403);
        }
        else {
          $query = DB::table('project_picture_video')
          ->select('project_picture_video.*')
          ->where('ppv_project_id', '=', $project_id)
          ->where('project_picture_video.ppv_status', '=', 'active')
          ->whereIn('ppv_type', array('video/mp4','video/mp3','video/avi', 'video/mkv'))
          ->count();
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
   Delete picture to project
  --------------------------------------------------------------------------
  */
  public function picture_delete_project(Request $request, $project_id, $id)
  {
    try
    {
      //  $user = array(
      //   'id'        => Auth::user()->id,
      //   'role'      => Auth::user()->role
      // );
      // $user = (object) $user;
      // $post = new Resource_Post(); // You create a new resource Post instance
      // if (Gate::forUser($user)->denies('allow_admin', [$post,false])) { 
      //   $result = array('code'=>403, "description"=>"Access Denies");
      //   return response()->json($result, 403);
      // } 
      // else { 
      // Check User Permission Parameter 
      $user_id = Auth::user()->id;
      $permission_key = 'project_picture_video_remove';
      $check_single_user_permission = app('App\Http\Controllers\Projects\PermissionController')->check_single_user_permission($project_id, $user_id, $permission_key);
      if(count($check_single_user_permission) < 1){
        $result = array('code'=>403, "description"=>"Access Denies");
        return response()->json($result, 403);
      }
      else {
        $getuser = new User();
        $user = DB::table('project_picture_video')
        ->where('ppv_project_id', $project_id)
        ->where('ppv_id', $id)
        ->update(['ppv_status' => 'deactive']);
        if(count($user) < 1)
        {
          $result = array('code'=>400, "description"=>"No Records Found");
          return response()->json($result, 400);
        }
        else
        {
          $result = array('data'=>$user,'code'=>200);
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