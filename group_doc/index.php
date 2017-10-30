<?php

require_once __DIR__.'/src/APIClient.php';
require_once __DIR__.'/src/GroupDocsRequestSigner.php';
require_once __DIR__.'/src/AsyncApi.php';
require_once __DIR__.'/src/StorageApi.php';
require_once __DIR__.'/src/FileStream.php';

// uzair account
// $clientId = '617ba46257dd04fd';
// $privateKey = '60df5b421a904b64d9f6faf03ea36239';

// ahsan account
// $clientId = 'ae5dc12d0178c4d9';
// $privateKey = '26a35376491b36d5e36b920c064ccb77';

// Faizan@solutionspark.com
$clientId = '667f39e7a5de972d';
$privateKey = 'fc4c7ffc71f2afa91f42b7815351b29e';

$signer = new GroupDocsRequestSigner($privateKey);
$apiClient = new APIClient($signer);
$asyncApi = new AsyncApi($apiClient);
$storageApi = new StorageApi($apiClient);
$basePath = 'https://api.groupdocs.com/v2.0';
$asyncApi->setBasePath($basePath);
$storageApi->setBasePath($basePath);

if (isset($_FILES['file']) && $_FILES["file"]["name"] != "") {
	$file = $_FILES['file'];
	// $tmpName = $file['tmp_name'].'_'.rand(11111,99999).'_'.date("Ymd");
	$tmpName = $file['tmp_name'];
	$name = rand(11111,99999).'_'.date("Ymd").'_'.$file['name']; // $file['name'];

	// echo '<pre>';
	// print_r($file);
	// print_r(rand(11111,99999).'_'.date("Ymd").'_'.$file['name']);
	// echo '</pre>';
	// exit;
	$fs = FileStream::fromFile($tmpName);
	try {
	    $uploadResult = $storageApi->Upload($clientId, $name, 'uploaded', "", 0, $fs);
	    if ($uploadResult->status == "Ok") {
	       $fileGuId = $uploadResult->result->guid;
	        $fileId = "";
	        // $iframe = "https://apps.groupdocs.com/document-annotation2/embed/" . $fileGuId;
	        // $iframe = "https://apps.groupdocs.com/document-viewer/embed/" . $fileGuId;
	        echo $iframeUrl = $signer->signUrl($fileGuId);
	        echo '<br/>';
	        echo $iframe = "https://apps.groupdocs.com/document-viewer/embed/" . $iframeUrl;
			// echo $fileGuId;
			return $fileGuId;
	    } else {
	        throw new Exception($uploadResult->error_message);
	    }
	} catch (Exception $e) {
	    $error = 'ERROR: ' . $e->getMessage() . "\n";
	    F3::set('error', $error);
	}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo</title>
</head>

<body>

<h2>Upload File for PDF Realtime Annotation</h2>
<form action="" method="post" enctype="multipart/form-data">
<input type="file" name="file" />
<input type="submit" name="submit" value="Submit" />
</form>
<br />
<br />
<br />
<?php if(!empty($iframeUrl)): ?>
<input value="<?php echo $iframeUrl; ?>" />
<iframe src="<?php echo $iframeUrl; ?>" frameborder="0" width="1000" height="650"></iframe>
<?php endif; ?>

</body>

</html>