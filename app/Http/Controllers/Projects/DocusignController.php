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
    
    //RETURNS
    //Associative array with elements:
    //ok -- true for success
    //errMsg -- only if !ok
    //The following are valid only if ok:
    //envelopeId
    //accountId
    //baseUrl
    public function request_sig_via_email(Request $request,$recipientEmail,$recipientName,$documentName,$documentFileName) {
        request_signature_on_a_document("kunalvision2009@gmail.com","kunal kkumar","test.pdf","test.pdf");
	
	// Set Authentication information
	// Set via a config file or just set here using constants.
	$email = "kunal_kumar@opiant.in";// your account email
        $password = "Password@123";// your account password
        $integratorKey = "3eb1ebfe-5e8b-4569-8b5a-1887c082a42c";// your account integrator key, found on (Preferences -> API page)

	// api service point
	$url = "https://demo.docusign.net/restapi/v2/login_information"; // change for production
	// construct the authentication header:
	$header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// STEP 1 - Login (to retrieve baseUrl and accountId)
	/////////////////////////////////////////////////////////////////////////////////////////////////
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-DocuSign-Authentication: $header"));

	$json_response = curl_exec($curl);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	if ( $status != 200 ) {
		return (['ok' => false, 'errMsg' => "Error calling DocuSign, status is: " . $status]);
	}

	$response = json_decode($json_response, true);
	$accountId = $response["loginAccounts"][0]["accountId"];
	$baseUrl = $response["loginAccounts"][0]["baseUrl"];
	curl_close($curl);

	/////////////////////////////////////////////////////////////////////////////////////////////////
	// STEP 2 - Create and send envelope with one recipient, one tab, and one document
	/////////////////////////////////////////////////////////////////////////////////////////////////

	// the following envelope request body will place 1 signature tab on the document, located
	// 100 pixels to the right and 100 pixels down from the top left of the document
	$data = 
		array (
			"emailSubject" => "DocuSign API - Please sign " . $documentName,
			"documents" => array( 
				array("documentId" => "1", "name" => $documentName)
				),
			"recipients" => array( 
				"signers" => array(
					array(
						"email" => $recipientEmail,
						"name" => $recipientName,
						"recipientId" => "1",
						"tabs" => array(
							"signHereTabs" => array(
								array(
									"xPosition" => "100",
									"yPosition" => "100",
									"documentId" => "1",
									"pageNumber" => "1"
								)
							)
						)
					),
                                        array(
						"email" => $recipientEmail,
						"name" => $recipientName,
						"recipientId" => "1",
						"tabs" => array(
							"signHereTabs" => array(
								array(
									"xPosition" => "100",
									"yPosition" => "100",
									"documentId" => "1",
									"pageNumber" => "1"
								)
							)
						)
					)
				)
			),
		"status" => "sent"
	);
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
	curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);                                                                  
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: multipart/form-data;boundary=myboundary',
		'Content-Length: ' . strlen($requestBody),
		"X-DocuSign-Authentication: $header" )                                                                       
	);
	$json_response = curl_exec($curl); // Do it!

	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if ( $status != 201 ) {
		echo "Error calling DocuSign, status is:" . $status . "\nerror text: ";
		print_r($json_response); echo "\n";
		exit(-1);
	}

	$response = json_decode($json_response, true);
	$envelopeId = $response["envelopeId"];
	
	print_r( [
	            'ok' => true,
	    'envelopeId' => $envelopeId,
	     'accountId' => $accountId,
	       'baseUrl' => $baseUrl
    ]);
	


    }
    public function download_documents(Request $request) {
        
  // Input your info here:
$email = "kunal_kumar@opiant.in";// your account email
$password = "Password@123";// your account password
$integratorKey = "3eb1ebfe-5e8b-4569-8b5a-1887c082a42c";// your account integrator key, found on (Preferences -> API page)

// copy the envelopeId from an existing envelope in your account that you want to query:
//$envelopeId = '7e6ce8fb-5e4d-4cf8-a217-1f80b44c016c';
$envelopeId = '9706c551-3ff7-46c6-9542-d04bcb2041a8';
// construct the authentication header:
$header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";

/////////////////////////////////////////////////////////////////////////////////////////////////
// STEP 1 - Login (retrieves baseUrl and accountId)
/////////////////////////////////////////////////////////////////////////////////////////////////
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

//--- display results
echo "accountId = " . $accountId . "\nbaseUrl = " . $baseUrl . "\n";

/////////////////////////////////////////////////////////////////////////////////////////////////
// STEP 2 - Get document information
/////////////////////////////////////////////////////////////////////////////////////////////////                                                                                  
$curl = curl_init($baseUrl . "/envelopes/" . $envelopeId . "/documents" );
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
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
echo "Envelope has following document(s) information...\n<pre>";
print_r($response);	echo "\n";

/////////////////////////////////////////////////////////////////////////////////////////////////
// STEP 3 - Download the envelope's documents
/////////////////////////////////////////////////////////////////////////////////////////////////
foreach( $response["envelopeDocuments"] as $document ) {
	$docUri = $document["uri"];
	
	$curl = curl_init($baseUrl . $docUri );
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);  
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
		"X-DocuSign-Authentication: $header" )                                                                       
	);
	
	$data = curl_exec($curl);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if ( $status != 200 ) {
		echo "error calling webservice, status is:" . $status;
		exit(-1);
	}

	file_put_contents($envelopeId . "-" . $document["name"], $data);
	curl_close($curl);
	
	//*** Documents should now be downloaded in the same folder as you ran this program
}

//--- display results
echo "Envelope document(s) have been downloaded, check your local directory.\n";


    }
    
    public function embed_the_console(Request $request) {
        
// Input your info:
$email = "kunal_kumar@opiant.in";// your account email
$password = "Password@123";// your account password
$integratorKey = "3eb1ebfe-5e8b-4569-8b5a-1887c082a42c";// your account integrator key, found on (Preferences -> API page)

// construct the authentication header:
$header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";    

/////////////////////////////////////////////////////////////////////////////////////////////////
// STEP 1 - Login (retrieves baseUrl and accountId)
/////////////////////////////////////////////////////////////////////////////////////////////////
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

//--- display results
echo "accountId = " . $accountId . "\nbaseUrl = " . $baseUrl . "\n";

/////////////////////////////////////////////////////////////////////////////////////////////////
// STEP 2 - Get the Embedded Console view
/////////////////////////////////////////////////////////////////////////////////////////////////
$data = array("accountId" => $accountId);                                                                    
$data_string = json_encode($data);                                                                                   
$curl = curl_init($baseUrl . "/views/console" );
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
	'Content-Type: application/json',                                                                                
	'Content-Length: ' . strlen($data_string),
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
$url = $response["url"];

//--- display results
echo "Embedded URL is: \n\n" . $url . "\n\nNavigate to this URL to open the DocuSign Member Console...\n"; 


    }
    public function embed_the_tag_and_send_ux(Request $request) {
        
// Input your info:
$email = "***";			// your account email
$password = "***";		// your account password
$integratorKey = "***";		// your account integrator key, found on (Preferences -> API page)
$recipientName = "***";		// provide a recipient (signer) name
$templateId = "***";		// provide a valid templateId of a template in your account
$templateRoleName = "***";	// use same role name that exists on the template in the console
$clientUserId = "***";		// to add an embedded recipient you must set their clientUserId property in addition to
				// the recipient name and email.  Whatever you set the clientUserId to you must use the same
				// value when requesting the sending URL
							
// construct the authentication header:
$header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";

/////////////////////////////////////////////////////////////////////////////////////////////////
// STEP 1 - Login (retrieves baseUrl and accountId)
/////////////////////////////////////////////////////////////////////////////////////////////////
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

//--- display results		
echo "accountId = " . $accountId . "\nbaseUrl = " . $baseUrl . "\n";

/////////////////////////////////////////////////////////////////////////////////////////////////
// STEP 2 - Create an envelope with an Embedded recipient (uses the clientUserId property)
/////////////////////////////////////////////////////////////////////////////////////////////////
$data = array("accountId" => $accountId, 
	"emailSubject" => "DocuSign API - Embedded Sending Example",
	"templateId" => $templateId, 
	"templateRoles" => array(
		array( "roleName" => $templateRoleName, "email" => $email, "name" => $recipientName, "clientUserId" => $clientUserId )),
	"status" => "created");                                                                    

$data_string = json_encode($data);  
$curl = curl_init($baseUrl . "/envelopes" );
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
	'Content-Type: application/json',                                                                                
	'Content-Length: ' . strlen($data_string),
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
echo "Envelope created! Envelope ID: " . $envelopeId . "\n"; 

/////////////////////////////////////////////////////////////////////////////////////////////////
// STEP 3 - Get the Embedded Sending View (aka the "tag-and-send" view)
/////////////////////////////////////////////////////////////////////////////////////////////////
$data = array("returnUrl" => "http://www.docusign.com/devcenter");                                                                    
$data_string = json_encode($data);                                                                                   
$curl = curl_init($baseUrl . "/envelopes/$envelopeId/views/sender" );
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
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
echo "Embedded URL is: \n\n" . $url . "\n\nNavigate to this URL to start the tag-and-send view of the envelope\n"; 

    }
    public function get_envelope_recipient_status(Request $request) {
            
	// Input your info here:
	$email = "kunal_kumar@opiant.in";// your account email
$password = "Password@123";// your account password
$integratorKey = "3eb1ebfe-5e8b-4569-8b5a-1887c082a42c";// your account integrator key, found on (Preferences -> API page)

// copy the envelopeId from an existing envelope in your account that you want to query:
//$envelopeId = '7e6ce8fb-5e4d-4cf8-a217-1f80b44c016c';
$envelopeId = '9706c551-3ff7-46c6-9542-d04bcb2041a8';
	
	// construct the authentication header:
	$header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// STEP 1 - Login (retrieves baseUrl and accountId)
	/////////////////////////////////////////////////////////////////////////////////////////////////
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
	
	//--- display results
	echo "\naccountId = " . $accountId . "\nbaseUrl = " . $baseUrl . "\n";
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// STEP 2 - Get envelope information
	/////////////////////////////////////////////////////////////////////////////////////////////////
	$data_string = json_encode($data);                                                                                   
	$curl = curl_init($baseUrl . "/envelopes/" . $envelopeId . "/recipients" );
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
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
	
	$response = json_decode($json_response, true);
	
	//--- display results
	echo "First signer = " . $response["signers"][0]["name"] . "\n";
	echo "First Signer's email = " . $response["signers"][0]["email"] . "\n";
	echo "Signing status = " . $response["signers"][0]["status"] . "\n\n";
    }
    public function get_envelope_status(Request $request) {
        
// Input your info here:
$email = "kunal_kumar@opiant.in";// your account email
$password = "Password@123";// your account password
$integratorKey = "3eb1ebfe-5e8b-4569-8b5a-1887c082a42c";// your account integrator key, found on (Preferences -> API page)

// copy the envelopeId from an existing envelope in your account that you want to query:
//$envelopeId = '7e6ce8fb-5e4d-4cf8-a217-1f80b44c016c';	
$envelopeId = '3ccf5d30-0244-4f41-b0fc-f0d0536378dc';
// construct the authentication header:
$header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";

/////////////////////////////////////////////////////////////////////////////////////////////////
// STEP 1 - Login (to retrieve baseUrl and accountId)
/////////////////////////////////////////////////////////////////////////////////////////////////
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

//--- display results
echo "\naccountId = " . $accountId . "\nbaseUrl = " . $baseUrl . "\n";

/////////////////////////////////////////////////////////////////////////////////////////////////
// STEP 2 - Get envelope information
/////////////////////////////////////////////////////////////////////////////////////////////////                                                                                
$curl = curl_init($baseUrl . "/envelopes/" . $envelopeId );
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
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

$response = json_decode($json_response, true);

//--- display results
echo "status = " . $response["status"] . "\nsent time = " . $response["sentDateTime"] . "\n\n";

    }
    public function polling_status_changes(Request $request) {
        
// Input your info here:
$email = "***";			// your account email
$password = "***";		// your account password
$integratorKey = "***";		// your account integrator key, found on (Preferences -> API page)

// construct the authentication header:
$header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";

/////////////////////////////////////////////////////////////////////////////////////////////////
// STEP 1 - Login (retrieves baseUrl and accountId)
/////////////////////////////////////////////////////////////////////////////////////////////////
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

//--- display results
echo "\naccountId = " . $accountId . "\nbaseUrl = " . $baseUrl . "\n";

/////////////////////////////////////////////////////////////////////////////////////////////////
// STEP 2 - status retrieval using filters
/////////////////////////////////////////////////////////////////////////////////////////////////
echo "Performing status retrieval using filters...\n";
date_default_timezone_set('America/Los_Angeles');
$from_date  = date("m") . "%2F" . (date("d")-7) . "%2F". date("Y");

$curl = curl_init($baseUrl . "/envelopes?from_date=$from_date&status=created,sent,delivered,signed,completed" );
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
	'Accept: application/json',
	"X-DocuSign-Authentication: $header" )                                                                       
);

$json_response = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
if ( $status != 200 ) {
	echo "error calling webservice, status is:" . $status . "\nerror text is --> ";
	print_r($json_response); echo "\n";
	exit(-1);
}

$response = json_decode($json_response, true);

//--- display results
echo "Received " . count( $response["envelopes"]) . " envelopes\n";
foreach ($response["envelopes"] as $envelope) {
	echo "envelope: " . $envelope["envelopeId"] . " " . $envelope["status"] . " " . $envelope["statusChangedDateTime"] . "\n";
}    


    }
    
    public function request_sig_via_email_w_template(Request $request) {
        
	// Input your info here:
	$email = "***";			// your account email (also where this signature request will be sent)
	$password = "***";		// your account password
	$integratorKey = "***";		// your account integrator key, found on (Preferences -> API page)
	$recipientName = "***";		// provide a recipient (signer) name
	$templateId = "***";		// provide a valid templateId of a template in your account
	$templateRoleName = "***";	// use same role name that exists on the template in the console
	
	// construct the authentication header:
	$header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// STEP 1 - Login (to retrieve baseUrl and accountId)
	/////////////////////////////////////////////////////////////////////////////////////////////////
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
	
	// --- display results
	echo "\naccountId = " . $accountId . "\nbaseUrl = " . $baseUrl . "\n";
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// STEP 2 - Create and envelope using one template role (called "Signer1") and one recipient
	/////////////////////////////////////////////////////////////////////////////////////////////////
	$data = array("accountId" => $accountId, 
		"emailSubject" => "DocuSign API - Signature Request from Template",
		"templateId" => $templateId, 
		"templateRoles" => array( 
				array( "email" => $email, "name" => $recipientName, "roleName" => $templateRoleName )),
		"status" => "sent");                                                                    
	
	$data_string = json_encode($data);  
	$curl = curl_init($baseUrl . "/envelopes" );
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($data_string),
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
	
	// --- display results
	echo "Document is sent! Envelope ID = " . $envelopeId . "\n\n"; 
    }
    public function signing_from_within_your_app(Request $request) {
            
// Input your info:
$email = "***";			// your account email
$password = "***";		// your account password
$integratorKey = "***";		// your account integrator key, found on (Preferences -> API page)
$recipientName = "***";		// provide a recipient (signer) name
$templateId = "***";		// provide a valid templateId of a template in your account
$templateRoleName = "***";	// use same role name that exists on the template in the console
$clientUserId = "***";		// to add an embedded recipient you must set their clientUserId property in addition to
				// the recipient name and email.  Whatever you set the clientUserId to you must use the same
				// value when requesting the signing URL

// construct the authentication header:
$header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";

/////////////////////////////////////////////////////////////////////////////////////////////////
// STEP 1 - Login (retrieves baseUrl and accountId)
/////////////////////////////////////////////////////////////////////////////////////////////////
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

//--- display results
echo "accountId = " . $accountId . "\nbaseUrl = " . $baseUrl . "\n";
    
/////////////////////////////////////////////////////////////////////////////////////////////////
// STEP 2 - Create an envelope with an Embedded recipient (uses the clientUserId property)
/////////////////////////////////////////////////////////////////////////////////////////////////
$data = array("accountId" => $accountId, 
	"emailSubject" => "DocuSign API - Embedded Signing Example",
	"templateId" => $templateId, 
	"templateRoles" => array(
		array( "roleName" => $templateRoleName, "email" => $email, "name" => $recipientName, "clientUserId" => $clientUserId )),
	"status" => "sent");                                                                    

$data_string = json_encode($data);  
$curl = curl_init($baseUrl . "/envelopes" );
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
	'Content-Type: application/json',                                                                                
	'Content-Length: ' . strlen($data_string),
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
echo "Envelope created! Envelope ID: " . $envelopeId . "\n"; 

/////////////////////////////////////////////////////////////////////////////////////////////////
// STEP 3 - Get the Embedded Signing View 
/////////////////////////////////////////////////////////////////////////////////////////////////
$data = array("returnUrl" => "http://www.docusign.com/devcenter",
	"authenticationMethod" => "None", "email" => $email, 
	"userName" => $recipientName, "clientUserId" => $clientUserId
);                                                                    

$data_string = json_encode($data);    
$curl = curl_init($baseUrl . "/envelopes/$envelopeId/views/recipient" );
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
	'Content-Type: application/json',                                                                                
	'Content-Length: ' . strlen($data_string),
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
$url = $response["url"];

//--- display results
echo "Embedded URL is: \n\n" . $url . "\n\nNavigate to this URL to start the embedded signing view of the envelope\n"; 


    }
    
    
    
    
 
}