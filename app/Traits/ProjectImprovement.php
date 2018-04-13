<?php 
namespace App\Traits;
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
use App;
use Session;

use Gate;
use App\User; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post; 
use App\Repositories\Util\AclPolicy;

trait ProjectImprovement{

    public function getType($project_id)
    {
  try
  {
      $user_id              = Auth::user()->id;
      $query = DB::table('projects')
      ->select('p_type')
      ->where('p_id', '=', $project_id)
      ->get();
        $pro_type = array();
        $pro_type =  $query[0]->p_type;
        $faizan = $pro_type;
        $faizan = str_replace('"','',$pro_type);
        $faizan = str_replace('[','',$faizan);
        $faizan = str_replace(']','',$faizan);
        $faizan = explode(",",$faizan);
        $query2 = DB::table('project_type_improvement')
        ->select()
        ->whereIn('pt_id', $faizan)
        ->where('pt_status', '=', 'active')
        ->orderBy('pt_name', 'asc')
        ->get();
      if(count($query2) < 1)
      {
        return NULL;
      }
      else
      {
        return $query2;
      }
  }
  catch(Exception $e)
  {
    return NULL;
  }
}
    
    public function get_contract_amount($project_id)
  {
      try
      {
          $query = DB::table('project_bid_items')
          ->select(DB::raw('SUM(pbi_item_total_price) as total_amount, COUNT(pbi_id) as total_item'))
          ->where('pbi_project_id', '=', $project_id)
          ->where('pbi_status', '=', 'active')
          ->orderBy('pbi_id','asc')
          ->get();
          if(count($query) < 1)
          {
            return NULL;
          }
          else
          {
            return $query;
          }
      }
      catch(Exception $e)
      {
        return NULL;
      }
  }
  
  public function netChange($param,$project_id) {
    $query = DB::table('project_change_order_request_detail')
          ->select('project_change_order_request_detail.*')
          ->where('pcd_project_id', '=', $project_id)
            ->where('pcd_rfi','=','[]')
          ->whereNotIn('pcd_id', $param)
          ->get();
    $sum = 0;
    foreach ($query as $row){
        if($row->pcd_price){
            $sum+=$row->pcd_price;
        }else{
            $sum+=$row->pcd_unit_number*$row->pcd_unit_price;
        }
    }
    return $sum;
  }
  
  public function contractSumIncreased($param,$project_id) {
    $query = DB::table('project_change_order_request_detail')
          ->select('project_change_order_request_detail.*')
          ->where('pcd_project_id', '=', $project_id)
          //->where('pcd_rfi','=','[]')
          ->whereIn('pcd_id', $param)
          ->get();
//    $sum = 0;
//    foreach ($query as $row){
//        if($row->pcd_price){
//            $sum+=$row->pcd_price;
//        }else{
//            $sum+=$row->pcd_unit_number*$row->pcd_unit_price;
//        }
//    }
    return $query;
  }

}