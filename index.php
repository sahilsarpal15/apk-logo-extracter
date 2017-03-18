<?php

ini_set("display_errors", "1");
error_reporting(E_ALL);
include 'vendor/autoload.php';

if(isset($_POST['submit'])){
  	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);


	// Check if file already exists
	if (file_exists($target_file)) {
	    $uploadOk = 0;

	    return giveIcon($target_file);
	}


	// // Check file size
	// if ($_FILES["fileToUpload"]["size"] > 500000) {
	//     echo "Sorry, your file is too large.";
	//     $uploadOk = 0;
	// }

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	    echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
	        return giveIcon($target_file);
	    } else {
	        echo "Sorry, there was an error uploading your file.";
	    }
	}
}

function giveIcon($giveIcon)
{
	$apk = new \ApkParser\Parser($giveIcon);

	$resourceId = $apk->getManifest()->getApplication()->getIcon();

	$resources = $apk->getResources($resourceId);

	$labelResourceId = $apk->getManifest()->getApplication()->getLabel();
	$appLabel = $apk->getResources($labelResourceId);
	echo $appLabel[0];
	header('Content-type: text/html');
	echo $appLabel[0] . '<br/>';
	foreach ($resources as $resource) {
	    echo '<img src="data:image/png;base64,', base64_encode(stream_get_contents($apk->getStream($resource))), '" />';
	}
	
}
?>


<form  method="post" role="form" enctype="multipart/form-data">
    Select apk to upload:
    <input type="file" name="fileToUpload" id="fileToUpload" class="form-control" required>
    <input type="submit" value="Upload Apk" name="submit">
</form>