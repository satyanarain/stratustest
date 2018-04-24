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
use App;
use Session;

use Gate;
use App\User; //your model
use App\Repositories\Custom\Resource\Post as Resource_Post; 
use App\Repositories\Util\AclPolicy;


class DocusignController extends Controller {
    
    
    public function download_documents(Request $request) {
        $email = env('DOCUSIGN_EMAIL');
        $password = env('DOCUSIGN_PASSWORD');
        $integratorKey = env('DOCUSIGN_INTEGRATOR_KEY');
        $url = env('DOCUSIGN_URL');
        $header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";
        $url = "https://demo.docusign.net/restapi/v2/login_information";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
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
        
        //UPDATE & DOWNLOAD NOTICE OF AWARD DOCUMENT FROM DOCUSIGN        
        $awards = DB::table('project_notice_award')
                ->select('project_notice_award.*')
                ->where('pna_docusign_status', '=',"pending")
                ->where('pna_envelope_id', '!=',"")
                ->get();
        foreach($awards as $award){
            $envelopeId = $award->pna_envelope_id;
            $doc_id = $award->pna_notice_path;
            $pna_id = $award->pna_id;
            $curl1 = curl_init($baseUrl . "/envelopes/" . $envelopeId);
            curl_setopt($curl1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl1, CURLOPT_HTTPHEADER, array(                                                                          
                    "X-DocuSign-Authentication: $header" )                                                                       
            );
            $json_response1 = curl_exec($curl1);
            $status1 = curl_getinfo($curl1, CURLINFO_HTTP_CODE);
            if ($status1 == 200 ) {
                $response1 = json_decode($json_response1, true);
                //print_r($response1);die;
                //echo $response1["status"];die;
                curl_close($curl1);
                if($response1["status"]=="completed")
                {
                    $curl2 = curl_init($baseUrl . "/envelopes/" . $envelopeId . "/documents" );
                    curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl2, CURLOPT_HTTPHEADER, array(                                                                          
                            "X-DocuSign-Authentication: $header" )                                                                       
                    );
                    $json_response2 = curl_exec($curl2);
                    $status2 = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
                    if ($status2 == 200 ) {
                        $response2 = json_decode($json_response2, true);
                        curl_close($curl2);
                        //echo "<pre>";print_r($response2);die;
                        foreach( $response2["envelopeDocuments"] as $document ) {
                                $docUri = $document["uri"];
                                $curl3 = curl_init($baseUrl . $docUri );
                                curl_setopt($curl3, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl3, CURLOPT_BINARYTRANSFER, true);  
                                curl_setopt($curl3, CURLOPT_HTTPHEADER, array(                                                                          
                                        "X-DocuSign-Authentication: $header" )                                                                       
                                );
                                $data = curl_exec($curl3);
                                //echo env('APP_URL').$envelopeId . "-" . $document["name"];
                                //echo "<pre>";print_r($document);die;
                                $status3 = curl_getinfo($curl3, CURLINFO_HTTP_CODE);
                                if($status3==200){
                                    $file_upload_path = "/uploads/notice_award/".$envelopeId . "-" . $document["name"];
                                    file_put_contents(base_path().$file_upload_path, $data);
                                    if($document['type']=="content")
                                    {
                                        $query = DB::table('documents')
                                        ->where('doc_id', '=', $doc_id)
                                        ->update(['doc_path' => $file_upload_path]);
                                        $query = DB::table('project_notice_award')
                                        ->where('pna_id', '=', $pna_id)
                                        ->update(['pna_docusign_status' => "complete"]);
                                    }
                                    curl_close($curl3);
                                }else{continue;}
                        }
                    }else{continue;}
                }else{continue;}
            }else{continue;}
        }
        
        //UPDATE & DOWNLOAD NOTICE TO PROCEED DOCUMENT FROM DOCUSIGN        
        $awards = DB::table('project_notice_proceed')
                ->select('project_notice_proceed.*')
                ->where('pnp_docusign_status', '=',"pending")
                ->where('pnp_envelope_id', '!=',"")
                ->get();
        foreach($awards as $award){
            $envelopeId = $award->pnp_envelope_id;
            $doc_id = $award->pnp_path;
            $pna_id = $award->pnp_id;
            $curl1 = curl_init($baseUrl . "/envelopes/" . $envelopeId);
            curl_setopt($curl1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl1, CURLOPT_HTTPHEADER, array(                                                                          
                    "X-DocuSign-Authentication: $header" )                                                                       
            );
            $json_response1 = curl_exec($curl1);
            $status1 = curl_getinfo($curl1, CURLINFO_HTTP_CODE);
            if ($status1 == 200 ) {
                $response1 = json_decode($json_response1, true);
                //echo $response1["status"];die;
                
                curl_close($curl1);
                if($response1["status"]=="completed")
                {
                    $curl2 = curl_init($baseUrl . "/envelopes/" . $envelopeId . "/documents" );
                    curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl2, CURLOPT_HTTPHEADER, array(                                                                          
                            "X-DocuSign-Authentication: $header" )                                                                       
                    );
                    $json_response2 = curl_exec($curl2);
                    $status2 = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
                    if ($status2 == 200 ) {
                        $response2 = json_decode($json_response2, true);
                        curl_close($curl2);
                        //echo "<pre>";print_r($response2);die;
                        foreach( $response2["envelopeDocuments"] as $document ) {
                                $docUri = $document["uri"];
                                $curl3 = curl_init($baseUrl . $docUri );
                                curl_setopt($curl3, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl3, CURLOPT_BINARYTRANSFER, true);  
                                curl_setopt($curl3, CURLOPT_HTTPHEADER, array(                                                                          
                                        "X-DocuSign-Authentication: $header" )                                                                       
                                );
                                $data = curl_exec($curl3);
                                //echo env('APP_URL').$envelopeId . "-" . $document["name"];
                                //echo "<pre>";print_r($document);die;
                                $status3 = curl_getinfo($curl3, CURLINFO_HTTP_CODE);
                                if($status3==200){
                                    $file_upload_path = "/uploads/notice_proceed/".$envelopeId . "-" . $document["name"];
                                    file_put_contents(base_path().$file_upload_path, $data);
                                    if($document['type']=="content")
                                    {
                                        $query = DB::table('documents')
                                        ->where('doc_id', '=', $doc_id)
                                        ->update(['doc_path' => $file_upload_path]);
                                        $query = DB::table('project_notice_proceed')
                                        ->where('pnp_id', '=', $pna_id)
                                        ->update(['pnp_docusign_status' => "complete"]);
                                    }
                                    curl_close($curl3);
                                }else{continue;}
                        }
                    }else{continue;}
                } else {
                    continue;
                }
            }else{continue;}
        }
        
        //UPDATE & DOWNLOAD UNCONDITIONAL FINALS DOCUMENT FROM DOCUSIGN        
        $finals = DB::table('project_unconditional_finals')
                ->select('project_unconditional_finals.*')
                ->where('docusign_status', '=',"pending")
                ->where('envelope_id', '!=',"")
                ->get();
        foreach($finals as $final){
            $envelopeId = $final->envelope_id;
            $doc_id = $final->puf_file_path;
            $pna_id = $final->puf_id;
            $curl1 = curl_init($baseUrl . "/envelopes/" . $envelopeId);
            curl_setopt($curl1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl1, CURLOPT_HTTPHEADER, array(                                                                          
                    "X-DocuSign-Authentication: $header" )                                                                       
            );
            $json_response1 = curl_exec($curl1);
            $status1 = curl_getinfo($curl1, CURLINFO_HTTP_CODE);
            if ($status1 == 200 ) {
                $response1 = json_decode($json_response1, true);
                //echo $response1["status"];die;
                curl_close($curl1);
                if($response1["status"]=="completed")
                {
                    $curl2 = curl_init($baseUrl . "/envelopes/" . $envelopeId . "/documents" );
                    curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl2, CURLOPT_HTTPHEADER, array(                                                                          
                            "X-DocuSign-Authentication: $header" )                                                                       
                    );
                    $json_response2 = curl_exec($curl2);
                    $status2 = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
                    if ($status2 == 200 ) {
                        $response2 = json_decode($json_response2, true);
                        curl_close($curl2);
                        //echo "<pre>";print_r($response2);die;
                        foreach( $response2["envelopeDocuments"] as $document ) {
                                $docUri = $document["uri"];
                                $curl3 = curl_init($baseUrl . $docUri );
                                curl_setopt($curl3, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl3, CURLOPT_BINARYTRANSFER, true);  
                                curl_setopt($curl3, CURLOPT_HTTPHEADER, array(                                                                          
                                        "X-DocuSign-Authentication: $header" )                                                                       
                                );
                                $data = curl_exec($curl3);
                                //echo env('APP_URL').$envelopeId . "-" . $document["name"];
                                //echo "<pre>";print_r($document);die;
                                $status3 = curl_getinfo($curl3, CURLINFO_HTTP_CODE);
                                if($status3==200){
                                    $file_upload_path = "/uploads/unconditional_finals/".$envelopeId . "-" . $document["name"];
                                    file_put_contents(base_path().$file_upload_path, $data);
                                    if($document['type']=="content")
                                    {
                                        $query = DB::table('documents')
                                        ->where('doc_id', '=', $doc_id)
                                        ->update(['doc_path' => $file_upload_path]);
                                        $query = DB::table('project_unconditional_finals')
                                        ->where('puf_id', '=', $pna_id)
                                        ->update(['docusign_status' => "complete"]);
                                    }
                                    curl_close($curl3);
                                }else{continue;}
                        }
                    }else{continue;}
                }else{continue;}
            }else{continue;}
        }
        
        //UPDATE & DOWNLOAD STATEMENT OF COMPLIANCE DOCUMENT FROM DOCUSIGN        
        $compliances = DB::table('project_labor_compliance')
                ->select('project_labor_compliance.*')
                ->where('docusign_status', '=',"pending")
                ->where('envelope_id', '!=',"")
                ->get();
        foreach($compliances as $compliance){
            $envelopeId = $compliance->envelope_id;
            $pna_id = $compliance->plc_id;
            $curl1 = curl_init($baseUrl . "/envelopes/" . $envelopeId);
            curl_setopt($curl1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl1, CURLOPT_HTTPHEADER, array(                                                                          
                    "X-DocuSign-Authentication: $header" )                                                                       
            );
            $json_response1 = curl_exec($curl1);
            $status1 = curl_getinfo($curl1, CURLINFO_HTTP_CODE);
            if ($status1 == 200 ) {
                $response1 = json_decode($json_response1, true);
                //echo $response1["status"];die;
                curl_close($curl1);
                if($response1["status"]=="completed")
                {
                    $curl2 = curl_init($baseUrl . "/envelopes/" . $envelopeId . "/documents" );
                    curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl2, CURLOPT_HTTPHEADER, array(                                                                          
                            "X-DocuSign-Authentication: $header" )                                                                       
                    );
                    $json_response2 = curl_exec($curl2);
                    $status2 = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
                    if ($status2 == 200 ) {
                        $response2 = json_decode($json_response2, true);
                        curl_close($curl2);
                        //echo "<pre>";print_r($response2);die;
                        foreach( $response2["envelopeDocuments"] as $document ) {
                                $docUri = $document["uri"];
                                $curl3 = curl_init($baseUrl . $docUri );
                                curl_setopt($curl3, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl3, CURLOPT_BINARYTRANSFER, true);  
                                curl_setopt($curl3, CURLOPT_HTTPHEADER, array(                                                                          
                                        "X-DocuSign-Authentication: $header" )                                                                       
                                );
                                $data = curl_exec($curl3);
                                //echo env('APP_URL').$envelopeId . "-" . $document["name"];
                                //echo "<pre>";print_r($document);die;
                                $status3 = curl_getinfo($curl3, CURLINFO_HTTP_CODE);
                                if($status3==200){
                                    $file_upload_path = "/uploads/labor_compliance/".$envelopeId . "-" . $document["name"];
                                    file_put_contents(base_path().$file_upload_path, $data);
                                    if($document['type']=="content")
                                    {
                                        $information = array(
                                            "doc_status"     => "active",
                                            "doc_project_id" => $compliance->plc_project_id,
                                            "doc_user_id"    => 0,
                                            "doc_name"       => $document["name"],
                                            "doc_path"       => $file_upload_path,
                                        );
                                        $doc_id = DB::table('documents')->insertGetId($information);
                                        $query = DB::table('project_labor_compliance')
                                        ->where('plc_id', '=', $pna_id)
                                        ->update(['docusign_status' => "complete",'plc_compliance'=>$doc_id]);
                                    }
                                    curl_close($curl3);
                                }else{continue;}
                        }
                    }else{continue;}
                }else{continue;}
            }else{continue;}
        }
        
        //UPDATE & DOWNLOAD STATEMENT OF NON PERFORMANCE DOCUMENT FROM DOCUSIGN        
        $compliances = DB::table('project_labor_compliance')
                ->select('project_labor_compliance.*')
                ->where('performance_docusign_status', '=',"pending")
                ->where('performance_envelope_id', '!=',"")
                ->get();
        foreach($compliances as $compliance){
            $envelopeId = $compliance->performance_envelope_id;
            $pna_id = $compliance->plc_id;
            $curl1 = curl_init($baseUrl . "/envelopes/" . $envelopeId);
            curl_setopt($curl1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl1, CURLOPT_HTTPHEADER, array(                                                                          
                    "X-DocuSign-Authentication: $header" )                                                                       
            );
            $json_response1 = curl_exec($curl1);
            $status1 = curl_getinfo($curl1, CURLINFO_HTTP_CODE);
            if ($status1 == 200 ) {
                $response1 = json_decode($json_response1, true);
                //echo $response1["status"];die;
                curl_close($curl1);
                if($response1["status"]=="completed")
                {
                    $curl2 = curl_init($baseUrl . "/envelopes/" . $envelopeId . "/documents" );
                    curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl2, CURLOPT_HTTPHEADER, array(                                                                          
                            "X-DocuSign-Authentication: $header" )                                                                       
                    );
                    $json_response2 = curl_exec($curl2);
                    $status2 = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
                    if ($status2 == 200 ) {
                        $response2 = json_decode($json_response2, true);
                        curl_close($curl2);
                        //echo "<pre>";print_r($response2);die;
                        foreach( $response2["envelopeDocuments"] as $document ) {
                                $docUri = $document["uri"];
                                $curl3 = curl_init($baseUrl . $docUri );
                                curl_setopt($curl3, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl3, CURLOPT_BINARYTRANSFER, true);  
                                curl_setopt($curl3, CURLOPT_HTTPHEADER, array(                                                                          
                                        "X-DocuSign-Authentication: $header" )                                                                       
                                );
                                $data = curl_exec($curl3);
                                //echo env('APP_URL').$envelopeId . "-" . $document["name"];
                                //echo "<pre>";print_r($document);die;
                                $status3 = curl_getinfo($curl3, CURLINFO_HTTP_CODE);
                                if($status3==200){
                                    $file_upload_path = "/uploads/labor_compliance/".$envelopeId . "-" . $document["name"];
                                    file_put_contents(base_path().$file_upload_path, $data);
                                    if($document['type']=="content")
                                    {
                                        $information = array(
                                            "doc_status"     => "active",
                                            "doc_project_id" => $compliance->plc_project_id,
                                            "doc_user_id"    => 0,
                                            "doc_name"       => $document["name"],
                                            "doc_path"       => $file_upload_path,
                                        );
                                        $doc_id = DB::table('documents')->insertGetId($information);
                                        $query = DB::table('project_labor_compliance')
                                        ->where('plc_id', '=', $pna_id)
                                        ->update(['performance_docusign_status' => "complete",'plc_compliance_non_performance'=>$doc_id]);
                                    }
                                    curl_close($curl3);
                                }else{continue;}
                        }
                    }else{continue;}
                }else{continue;}
            }else{continue;}
        }
        
        //UPDATE & DOWNLOAD NOTICE OF COMPLETION DOCUMENT FROM DOCUSIGN        
        $nocs = DB::table('project_notice_of_completion')
                ->select('project_notice_of_completion.*')
                ->where('docusign_status', '=',"pending")
                ->where('envelope_id', '!=',"")
                ->get();
        foreach($nocs as $noc){
            $envelopeId = $noc->envelope_id;
            $doc_id = $noc->noc_file_path;
            $noc_id = $noc->noc_id;
            $curl1 = curl_init($baseUrl . "/envelopes/" . $envelopeId);
            curl_setopt($curl1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl1, CURLOPT_HTTPHEADER, array(                                                                          
                    "X-DocuSign-Authentication: $header" )                                                                       
            );
            $json_response1 = curl_exec($curl1);
            $status1 = curl_getinfo($curl1, CURLINFO_HTTP_CODE);
            if ($status1 == 200 ) {
                $response1 = json_decode($json_response1, true);
                //echo $response1["status"];die;
                curl_close($curl1);
                if($response1["status"]=="completed")
                {
                    $curl2 = curl_init($baseUrl . "/envelopes/" . $envelopeId . "/documents" );
                    curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl2, CURLOPT_HTTPHEADER, array(                                                                          
                            "X-DocuSign-Authentication: $header" )                                                                       
                    );
                    $json_response2 = curl_exec($curl2);
                    $status2 = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
                    if ($status2 == 200 ) {
                        $response2 = json_decode($json_response2, true);
                        curl_close($curl2);
                        //echo "<pre>";print_r($response2);die;
                        foreach( $response2["envelopeDocuments"] as $document ) {
                                $docUri = $document["uri"];
                                $curl3 = curl_init($baseUrl . $docUri );
                                curl_setopt($curl3, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl3, CURLOPT_BINARYTRANSFER, true);  
                                curl_setopt($curl3, CURLOPT_HTTPHEADER, array(                                                                          
                                        "X-DocuSign-Authentication: $header" )                                                                       
                                );
                                $data = curl_exec($curl3);
                                //echo env('APP_URL').$envelopeId . "-" . $document["name"];
                                //echo "<pre>";print_r($document);die;
                                $status3 = curl_getinfo($curl3, CURLINFO_HTTP_CODE);
                                if($status3==200){
                                    $file_upload_path = "/uploads/notice_completion/".$envelopeId . "-" . $document["name"];
                                    file_put_contents(base_path().$file_upload_path, $data);
                                    if($document['type']=="content")
                                    {
                                        $query = DB::table('documents')
                                        ->where('doc_id', '=', $doc_id)
                                        ->update(['doc_path' => $file_upload_path]);
                                        $query = DB::table('project_notice_of_completion')
                                        ->where('noc_id', '=', $noc_id)
                                        ->update(['docusign_status' => "complete"]);
                                    }
                                    curl_close($curl3);
                                }else{continue;}
                        }
                    }else{continue;}
                }else{continue;}
            }else{continue;}
        }
        
        
        //UPDATE & DOWNLOAD CHANGE ORDER DOCUMENT WITH REIMBURSEMENT FROM DOCUSIGN        
        $nocs = DB::table('project_change_order_request_detail')
                ->select('project_change_order_request_detail.*')
                ->where('docusign_status', '=',"pending")
                ->where('envelope_id', '!=',"")
                ->get();
        foreach($nocs as $noc){
            $envelopeId = $noc->envelope_id;
            $doc_id = $noc->pcd_file_path;
            $noc_id = $noc->pcd_id;
            $curl1 = curl_init($baseUrl . "/envelopes/" . $envelopeId);
            curl_setopt($curl1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl1, CURLOPT_HTTPHEADER, array(                                                                          
                    "X-DocuSign-Authentication: $header" )                                                                       
            );
            $json_response1 = curl_exec($curl1);
            $status1 = curl_getinfo($curl1, CURLINFO_HTTP_CODE);
            if ($status1 == 200 ) {
                $response1 = json_decode($json_response1, true);
                //echo $response1["status"];die;
                curl_close($curl1);
                if($response1["status"]=="completed")
                {
                    $curl2 = curl_init($baseUrl . "/envelopes/" . $envelopeId . "/documents" );
                    curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl2, CURLOPT_HTTPHEADER, array(                                                                          
                            "X-DocuSign-Authentication: $header" )                                                                       
                    );
                    $json_response2 = curl_exec($curl2);
                    $status2 = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
                    if ($status2 == 200 ) {
                        $response2 = json_decode($json_response2, true);
                        curl_close($curl2);
                        //echo "<pre>";print_r($response2);die;
                        foreach( $response2["envelopeDocuments"] as $document ) {
                                $docUri = $document["uri"];
                                $curl3 = curl_init($baseUrl . $docUri );
                                curl_setopt($curl3, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl3, CURLOPT_BINARYTRANSFER, true);  
                                curl_setopt($curl3, CURLOPT_HTTPHEADER, array(                                                                          
                                        "X-DocuSign-Authentication: $header" )                                                                       
                                );
                                $data = curl_exec($curl3);
                                //echo env('APP_URL').$envelopeId . "-" . $document["name"];
                                //echo "<pre>";print_r($document);die;
                                $status3 = curl_getinfo($curl3, CURLINFO_HTTP_CODE);
                                if($status3==200){
                                    $file_upload_path = "/uploads/cor/".$envelopeId . "-" . $document["name"];
                                    file_put_contents(base_path().$file_upload_path, $data);
                                    if($document['type']=="content")
                                    {
                                        $query = DB::table('documents')
                                        ->where('doc_id', '=', $doc_id)
                                        ->update(['doc_path' => $file_upload_path]);
                                        $query = DB::table('project_change_order_request_detail')
                                        ->where('pcd_id', '=', $noc_id)
                                        ->update(['docusign_status' => "complete"]);
                                    }
                                    curl_close($curl3);
                                }else{continue;}
                        }
                    }else{continue;}
                }else{continue;}
            }else{continue;}
        }
    }
}