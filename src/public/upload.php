<!DOCTYPE html>
<html>

<head>
	<title>Upload your files</title>
</head>

<body>
	<form enctype="multipart/form-data" action="upload.php" method="POST">
		<p>Upload your file</p>
		<input type="file" name="file_upload"></input><br />
		<input type="submit" value="upload"></input>
	</form>
</body>

</html>

<?php
//include response generator
include '../response.php';

//An array of HTTP methods that we want to allow.
$allowed_methods = array('POST');

//The current request type.
$request_method = strtoupper($_SERVER['REQUEST_METHOD']);

//If the request method isn't in our list of allowed methods.
if (!in_array($request_method, $allowed_methods)) {

	http_response_code(405);
    $message = "You are using " . $request_method . "\n Only GET is allowed at this endpoint.";
    generate_json_response($message);
	exit;

} else {
	$file = $_FILES["file_upload"];

	//if a file has been uploaded
	if (!empty($file['error'] != UPLOAD_ERR_NO_FILE)) {
		//------GET PATH FROM URI------//
		// $uri_full = explode('/', trim($_SERVER['REQUEST_URI'], '/')); //split full uri into an array
		// // foreach ($uri_full_array as $uri_item){echo $uri_item . "\r\n";} echo "\r\n"; //testing $uri_full
		// $uri_script = explode("/", trim($_SERVER['SCRIPT_NAME'], "/")); //split the uri-up-to-filename into an array
		// // foreach ($uri_script_array as $uri_item){echo $uri_item . "\r\n";} echo "\r\n"; //testing $uri_script
		// $uri_relative = array_diff($uri_full, $uri_script); //keep only the relative uri
		// // foreach ($uri_relative as $uri_item){echo $uri_item . "\r\n";} echo "\r\n"; //testing $uri_relative
		// $relative_path = "templates/" . implode("/", $uri_relative) . "/";

		//------ GET PATH FROM $_GET ------//
		$path = htmlspecialchars($_POST["path"]);
		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		// echo gettype($ext); echo "\r\n";
		// echo $ext;echo "\r\n"; echo "\r\n";
		
		if(!($ext == "json") || empty($ext)) {
			$message = "Only .json or extensionless files can be uploaded to this service";
			generate_json_response($message);
			exit;
		}
		
		if(empty($path))
		{
			$relative_path = "./templates";
		} else {
			$relative_path = "./templates" . DIRECTORY_SEPARATOR . $path;
		}

		// echo $relative_path; echo "\r\n";

		//------ IF PATH EXISTS, UPLOAD FILE -------//
		if (is_dir($relative_path)) {

			$path = $relative_path . DIRECTORY_SEPARATOR . basename($file['name']);
			// echo $path; echo "\r\n";

			//if path exists, set $message to file being overwritten
			if(is_file($path)){
				$message = "{$file['name']} has overwritten the previous version inside $relative_path";
			}
			else{
				$message = "{$file['name']} has been uploaded to $relative_path";
			}

			if (move_uploaded_file($file['tmp_name'], $path)) {				
				generate_json_response($message);
			} else {
				$message = "There was an error uploading the file, please try again!";
				generate_json_response($message);
			}
		}

		else {
			// echo "else";
			mkdir($relative_path, 0755, true);

			$path = $relative_path . DIRECTORY_SEPARATOR . basename($file['name']);

			if (move_uploaded_file($file['tmp_name'], $path)) {
				$message = "{$file['name']} has been uploaded to the newly created $relative_path directory";
				generate_json_response($message);
			} else {
				$message = "There was an error uploading the file, please try again!";
				generate_json_response($message);
			}
		}
	}

	//error
	else {
		http_response_code(400);
		$message = "You need to select a file to upload.";
		generate_json_response($message);
	}
}

?>