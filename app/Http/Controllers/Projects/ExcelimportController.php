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
//use Maatwebsite\Excel\Excel;
//use Excel;
//use vendor\maatwebsite\excel\src\Maatwebsite\Excel;
use Excel;

class ExcelimportController extends Controller {
 

  /*
  --------------------------------------------------------------------------
   Update Minutes of Meeting by passing con_id
  --------------------------------------------------------------------------
  */
  public function importExport(Request $request,$project_id)
    {
        //$this->view->project_id = $project_id;
        return view('importExport',["project_id"=>$project_id]);
    }
    public function downloadExcel($type)
    {
        $data = Item::get()->toArray();
        return Excel::create('itsolutionstuff_example', function($excel) use ($data) {
                $excel->sheet('mySheet', function($sheet) use ($data)
        {
                        $sheet->fromArray($data);
        });
        })->download($type);
    }
    public function importExcel(Request $request,$project_id)
    {
        if(Input::hasFile('import_file'))
        {
            $path = Input::file('import_file')->getRealPath();
            $data = Excel::load($path, function($reader) {
            })->get();
            echo "<pre>";
            //print_r($data);die();
            if(!empty($data) && $data->count())
            {
                foreach ($data as $key => $value) 
                {
                    $request = $value->toArray();
                    $item_description       = $request['item_description'];
                    $item_unit              = $request['item_unit'];
                    $item_qty               = $request['item_qty'];
                    $item_unit_price        = $request['item_unit_price'];
                    $project_id             = $project_id;
                    $user_id                = Auth::user()->id;
                    $status                 = 'active';
                    $item_total_price = $item_qty * $item_unit_price;
                    $query = DB::table('project_bid_items')
                    ->insert(['pbi_item_description' => $item_description, 'pbi_item_unit' => $item_unit, 'pbi_item_unit_other' => $item_unit_other, 'pbi_item_qty' => $item_qty, 'pbi_item_unit_price' => $item_unit_price, 'pbi_item_total_price' =>$item_total_price, 'pbi_project_id' => $project_id, 'pbi_user_id' => $user_id, 'pbi_status' => $status]);
                }
                //print_r($k);
            }
        }
        return back();
    }
}