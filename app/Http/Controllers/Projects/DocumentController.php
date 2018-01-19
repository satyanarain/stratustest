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
use Illuminate\Routing\UrlGenerator;
use App;
use Session;
use PDF;

use Gate;
use App\User; //your model
use App\Document; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post; 
use App\Repositories\Util\AclPolicy;

use Response;


class DocumentController extends Controller {

  
  /*
  --------------------------------------------------------------------------
   Add Documents
  --------------------------------------------------------------------------
  */
  public function add_document(Request $request)
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
        $doc_path       = $request['doc_path'];
        $doc_meta       = $request['doc_meta'];
        $doc_name       = $request['doc_meta'].' '.date("d-m-Y H:i:s");
        $doc_project_id = $request['doc_project_id'];
        $doc_user_id    = Auth::user()->id;
        $doc_status     = 'active';
        
        $information = array(
            "doc_path"          => $doc_path,
            "doc_meta"          => $doc_meta,
            "doc_name"          => $doc_name,
            "doc_project_id"    => $doc_project_id,
            "doc_status"        => $doc_status
        );

        $rules = [
            'doc_path'        => 'required',
            'doc_meta'        => 'required',
            'doc_name'        => 'required',
            'doc_project_id'  => 'required',
            'doc_status'      => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            // $query = DB::table('documents')
            // ->insert(['doc_path' => $doc_path, 'doc_meta' => $doc_meta, 'doc_status' => $doc_status, 'doc_project_id' => $doc_project_id, 'doc_user_id' => $doc_user_id]);

            $document = Document::create(['doc_path' => $doc_path, 'doc_meta' => $doc_meta, 
              'doc_name' => $doc_name, 
              'doc_status' => $doc_status, 
              'doc_project_id' => $doc_project_id, 'doc_user_id' => $doc_user_id]);

            $document_id = $document->id;
            // print_r($document_id);
            // exit;



            if(count($document) < 1)
            {
              $result = array('code'=>400, "description"=>"No records found");
              return response()->json($result, 400);
            }
            else
            {

              $result = array('description'=>$document_id,'code'=>200);
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
   Update Document passing doc_id
  --------------------------------------------------------------------------
  */
  public function update_document(Request $request, $doc_id)
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
        $doc_user_id    = Auth::user()->id;
        $doc_status     = $request['doc_status'];
        
        $information = array(
            "doc_status"        => $doc_status
        );

        $rules = [
            'doc_status'      => 'required'
        ];
        $validator = Validator::make($information, $rules);
        if ($validator->fails()) 
        {
            return $result = response()->json(["data" => $validator->messages()],400);
        }
        else
        {
            $query = DB::table('documents')
            ->where('doc_id', '=', $doc_id)
            ->update(['doc_status' => $doc_status, 'doc_user_id' => $doc_user_id]);
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
   Get single document detail passing cur_id
  --------------------------------------------------------------------------
  */
  public function get_document_single(Request $request, $doc_id)
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
        $query = DB::table('documents')
        ->leftJoin('users', 'documents.doc_user_id', '=', 'users.id')
        ->leftJoin('project_firm', 'users.company_name', '=', 'project_firm.f_id')
        ->select('documents.*', 'project_firm.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('doc_id', '=', $doc_id)
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
   Get All Documents
  --------------------------------------------------------------------------
  */
  public function get_document(Request $request, $project_id, $document_meta)
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
          $query = DB::table('documents')
          ->select()
          ->where('doc_project_id', '=', $project_id)
          ->where('doc_meta', '=', $document_meta)
          ->where('doc_status', '=', 'active')
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
   Get All user to single document
  --------------------------------------------------------------------------
  */
  public function get_user_single_document(Request $request, $doc_id)
  {
      try
      {
        $user = array(
          'userid'    => Auth::user()->id,
          'role'      => Auth::user()->role
        );
       
        $query = DB::table('documents_rules')
        ->leftJoin('users', 'documents_rules.dr_user_id', '=', 'users.id')
        ->leftJoin('project_firm', 'users.company_name', '=', 'project_firm.f_id')
        ->select('documents_rules.*', 'project_firm.*', 
          'users.username as user_name', 'users.email as user_email', 'users.first_name as user_firstname', 'users.last_name as user_lastname', 'users.company_name as user_company', 'users.phone_number as user_phonenumber', 'users.status as user_status', 'users.role as user_role')
        ->where('dr_resource_id', '=', $doc_id)
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
   delete user to single document
  --------------------------------------------------------------------------
  */
  public function delete_user_single_document(Request $request, $dr_id)
  {
    try
    {
      $user = array(
        'userid'    => Auth::user()->id,
        'role'      => Auth::user()->role
      );
     
      $query = DB::table('documents_rules')
      ->where('dr_id', '=', $dr_id)
      ->delete();
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
   add user to single document
  --------------------------------------------------------------------------
  */
  public function add_user_single_document(Request $request)
  {
    $user_id            = $request['user_id'];
    $resource_id        = $request['resource_id'];
    $permission         = 'allow';
    
    $information = array(
        "user_id"           => $user_id,
        "resource_id"       => $resource_id,
        "permission"        => $permission
    );

    $rules = [
        'user_id'           => 'required',
        'resource_id'       => 'required',
        'permission'        => 'required'
    ];
    $validator = Validator::make($information, $rules);
    if ($validator->fails()) 
    {
        return $result = response()->json(["data" => $validator->messages()],400);
    }
    else
    {
        $query = DB::table('documents_rules')
        ->insert(['dr_user_id' => $user_id, 'dr_resource_id' => $resource_id, 'dr_permission' => $permission]);

        if(count($query) < 1)
        {
          $result = array('code'=>400, "description"=>"No records found");
          return response()->json($result, 400);
        }
        else
        {
          $result = array('description'=>"New user permission succefully",'code'=>200);
          return response()->json($result, 200);
        }

    }
  }

  public function uploadFiles(Request $request) {

    $image = Input::file('file');
    // echo '<pre>';
    // print_r($image);
    // echo '</pre>';
    $document_path = $request['document_path'];

      if (Input::file('file')->isValid()) {
        $destinationPath = $document_path; // upload path
        // $destinationPath = '/uploads/'; // upload path
        $extension = Input::file('file')->getClientOriginalExtension(); // getting image extension
        $fileName = rand(11111,99999).'_'.date("Ymd").'.'.$extension; // renameing image
        Input::file('file')->move(base_path().$destinationPath, $fileName);
        $file_path = $destinationPath.$fileName;
      }

      if($file_path){
        // return view('documents/document_add', ['upload_path' => $file_path, 'code'=>200]);
        // $result = array('code'=>200, "description"=>$file_path);
        $result = $file_path;
        return response()->json($result, 200);
      }
      else
      {
        $result = array('code'=>404, "description"=>"No Records Found");
        return response()->json($result, 404);
      }
    }



   //  public function upload_group_doc_files(Request $request) {

   //  include(app_path() . '/src/APIClient.php');
   //  include(app_path() . '/src/GroupDocsRequestSigner.php');
   //  include(app_path() . '/src/AsyncApi.php');
   //  include(app_path() . '/src/StorageApi.php');
   //  include(app_path() . '/src/FileStream.php'); 

   //  $clientId = '617ba46257dd04fd';
   //  $privateKey = '60df5b421a904b64d9f6faf03ea36239';

   //  // $signer = new app('App/src/GroupDocsRequestSigner')->GroupDocsRequestSigner($privateKey);
   //  $signer = new app('App\src\GroupDocsRequestSigner', $privateKey);
   //  // $signer = new GroupDocsRequestSigner($privateKey);
   //  $apiClient = new app('App\src\APIClient', $signer);
   //  // $apiClient = new APIClient($signer);
   //  $asyncApi = new app('App\src\AsyncApi', $apiClient);
   //  // $asyncApi = new AsyncApi($apiClient);
   //  $storageApi = new app('App\src\StorageApi', $apiClient);
   //  // $storageApi = new StorageApi($apiClient);
   //  $basePath = 'https://api.groupdocs.com/v2.0';
    
   //  new app('App\src\AsyncApi@setBasePath', $basePath);
   //  // $asyncApi->setBasePath($basePath);
   //  new app('App\src\StorageApi@setBasePath', $basePath);
   // // $storageApi->setBasePath($basePath);

   //  $image = Input::file('file');
   //  // $image = Request::hasFile('file');
   //  // $image = $request->hasFile('file');
   //  // $image = Request::file('file');
   //  echo '<pre>';
   //  print_r($image);
   //  echo '</pre>';
    
   //  // die();

   //    if ($request->hasFile('file') && $image->isValid()) {
   //      $extension = $image->getClientOriginalExtension(); // getting image extension
   //      $fileName = rand(11111,99999).'_'.date("Ymd").'.'.$extension; // renameing image
   //      $fs = FileStream::fromFile($fileName);
   //      try {
   //          $uploadResult = $storageApi->Upload($clientId, $fileName, 'uploaded', "", 0, $fs);
   //          if ($uploadResult->status == "Ok") {
   //              $fileGuId = $uploadResult->result->guid;
   //              $fileId = "";
   //              $iframe = "https://apps.groupdocs.com/document-annotation2/embed/" . $fileGuId;
   //              $iframeUrl = $signer->signUrl($iframe);
   //          } else {
   //              throw new Exception($uploadResult->error_message);
   //          }
   //      } catch (Exception $e) {
   //          $error = 'ERROR: ' . $e->getMessage() . "\n";
   //          F3::set('error', $error);
   //      }
   //    }

   //    if($iframeUrl){
   //      $result = $iframeUrl;
   //      return response()->json($result, 200);
   //    }
   //    else
   //    {
   //      $result = array('code'=>404, "description"=>"No Records Found");
   //      return response()->json($result, 404);
   //    }
   //  }

    public function CreateUploadFiles(Request $request) {

    // $document         = $request->only('document_generated');
    // $document_path    = $request->only('document_path');
    // $documents = json_decode($document['document_generated']);
    $document       = $request->only('document_generated');
    $document_path  = $request->only('document_path');

    
      $data = $document['document_generated'];
      $document_path = $document_path['document_path'];
    // echo '<pre>';
    // print_r($data);
    // // print_r($document_path);
    // echo '</pre>';
    // exit;
      $fname = rand(11111,99999).'_'.date("Ymd").'.pdf'; // name the file
      // $fname = "test.pdf"; // name the file
      $file = fopen($document_path.$fname, 'w'); // open the file path
      fwrite($file, $data); //save data
      fclose($file);
      // echo "Success";

      $file_path = $document_path.$fname;
      // $result = array('code'=>200, "description"=>$file_path);
      return response()->json($file_path, 200);
    }


    public function GeneratePdfFiles(Request $request) {

    $document       = $request->only('document_generated');
    $document_path  = $request->only('document_path');

    
      $data = $document['document_generated'];
      $document_path = $document_path['document_path'];
      $fname = rand(11111,99999).'_'.date("Ymd").'.pdf'; // name the file
      return PDF::loadHTML($data)->setWarnings(false)->save($document_path.$fname);
    // echo '<pre>';->setPaper('letter', 'portrait')
    // print_r($data);
    // // print_r($document_path);
    // echo '</pre>';
    // exit;
      $file_path = $document_path.$fname;
      // $result = array('code'=>200, "description"=>$file_path);
      return response()->json($file_path, 200);
    }
}