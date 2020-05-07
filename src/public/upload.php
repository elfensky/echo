<!DOCTYPE html>
<html>

<head>
	<title>Upload your files</title>
</head>

<body>
	<form enctype="multipart/form-data" action="upload.php" method="POST">
		<p>Upload your file</p>
		<input type="file" name="uploaded_file"></input><br />
		<input type="submit" value="upload"></input>
	</form>
</body>

</html>

<?php
//include response generator
include 'response.php';

//An array of HTTP methods that we want to allow.
$allowed_methods = array('POST');

//The current request type.
$request_method = strtoupper($_SERVER['REQUEST_METHOD']);

//If the request method isn't in our list of allowed methods.
if (!in_array($request_method, $allowed_methods)) {

	//Send a 405 Method Not Allowed header.
	header($_SERVER["SERVER_PROTOCOL"] . " 405 Method Not Allowed", true, 405);
	echo "You are using " . $request_method . "\r\n";
	echo "Only GET is allowed at this endpoint \r\n";

	exit; //Halt the script's execution.
} else {
	if (!empty($_FILES['uploaded_file'])) {
		//------GET URI------//
		$uri_full = explode('/', trim($_SERVER['REQUEST_URI'], '/')); //split full uri into an array
		// foreach ($uri_full_array as $uri_item){echo $uri_item . "\r\n";} echo "\r\n"; //testing $uri_full
		$uri_script = explode("/", trim($_SERVER['SCRIPT_NAME'], "/")); //split the uri-up-to-filename into an array
		// foreach ($uri_script_array as $uri_item){echo $uri_item . "\r\n";} echo "\r\n"; //testing $uri_script
		$uri_relative = array_diff($uri_full, $uri_script); //keep only the relative uri
		// foreach ($uri_relative as $uri_item){echo $uri_item . "\r\n";} echo "\r\n"; //testing $uri_relative
		$path_relative = "templates/" . implode("/", $uri_relative) . "/";

		if (is_dir($path_relative)) {
			$path = $path_relative . basename($_FILES['uploaded_file']['name']);

			if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path)) {
				echo "\r\n";
				echo "The file " .  basename($_FILES['uploaded_file']['name']) .
					" has been uploaded";
			} else {
				echo "\r\n";
				echo "There was an error uploading the file, please try again!";
			}
		}

		else {
			mkdir($path_relative, 0755, true);

			$path = $path_relative . basename($_FILES['uploaded_file']['name']);

			if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path)) {
				echo "\r\n";
				echo "The file " .  basename($_FILES['uploaded_file']['name']) .
					" has been uploaded";
			} else {
				echo "\r\n";
				echo "There was an error uploading the file, please try again!";
			}
		}
	}
}

?>