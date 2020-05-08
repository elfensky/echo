<?php
//include response generator
require '../response.php';

//An array of HTTP methods that we want to allow.
$allowedMethods = array('POST');

//The current request type.
$requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);

//If the request method isn't in our list of allowed methods.
if (!in_array($requestMethod, $allowedMethods)) {

    //set code
    http_response_code(405);

    //create response
    $message = "Only POST is allowed at this endpoint.";
    // $data = array("example"=>"this endpoint requires specifying /<name> of the used template \n ../template.php/example");
    generate_json_response($message);

    exit; //Halt the script's execution.

} 

else {
            //This will only be executed out if a POST request is used and the content_type is allowed.

			// $uri_full = explode('/', trim($_SERVER['REQUEST_URI'], '/')); //split full uri into an array
			// // foreach ($uri_full_array as $uri_item){echo $uri_item . "\r\n";} echo "\r\n"; //testing $uri_full
			// $uri_script = explode("/", trim($_SERVER['SCRIPT_NAME'], "/")); //split the uri-up-to-filename into an array
			// // foreach ($uri_script_array as $uri_item){echo $uri_item . "\r\n";} echo "\r\n"; //testing $uri_script
			// $uri_relative = array_diff($uri_full, $uri_script); //keep only the relative uri
			// // foreach ($uri_relative as $uri_item){echo $uri_item . "\r\n";} echo "\r\n"; //testing $uri_relative	
			// $path_relative = "templates/" . implode("/", $uri_relative);
			// // echo $path_relative; echo "\r\n"; //testing $path_relative
			// // echo $path_relative . ".json"; echo "\r\n"; //testing $path_relative

			$received_body = json_decode(file_get_contents("php://input"), true);
			$path = $received_body["template"]["path"];
			$name = $received_body["template"]["name"];
			$received_array = $received_body["data"];

			//if no name is provided, error out. 
			if(empty($name))
			{
				http_response_code(400);
				$message = "You need to at least provide the name of the template";
				generate_json_response($message);
				exit;
			}

			if(empty($path))
			{	//if no path is specified, look in root/name
				$path_relative = "templates" . DIRECTORY_SEPARATOR . $name;
			} else {
				//if path is set, look in root/path/name
				$path_relative = "templates" . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $name;
			}
	
			//check if json file exists
			if (file_exists($path_relative . ".json")) {
				//get template file
				$path_relative = $path_relative . ".json";
				
				$template_file = file_get_contents($path_relative); // print_r($file); echo "\r\n";
				$template_array = json_decode($template_file, true); // print_r($template_array); echo "\r\n";

				//set Content-Type header 
				//unnneccesary, this is the .json path, it will always be json.
				// $file_info = new finfo(FILEINFO_MIME_TYPE);
				// $mime_type = $file_info->buffer($template_file);
				// header('Content-Type: ' . $mime_type);

				//get posted data //this is the received JSON data, converted into an array
				// $received_array = json_decode(file_get_contents("php://input"), true); // print_r($received_array); echo "\r\n";

				//if no custom data is received, just return the unmodified template
				if (empty($received_array)) {

					//if raw is requested, give back the direct unedited json.
					if($received_body["template"]["mode"] == "raw") {
						header("Content-Type: application/json");
						echo $template_file;	
						exit;
					}

					$message = "Request successfull";
					generate_json_response($message, $template_array);
					exit;
				} 
				//if there is custom data, process it and replace in template where neccesary.
				else {
					//-----------TESTING NATIVE BUILT-IN WAYS TO COMPARE ARRAYS & REPLACE VALUES IF KEYS MATCH-----------//

					//removes not mentioned keys & adds new keys instead of ignoring them & is not recursive
					// $fixed_array = array_merge($template_array, $received_array); 

					//removes not mentioned keys & adds new keys instead of ignoring them & is not recursive
					// $fixed_array = array_replace($template_array, $received_array); 

					//adds new keys instead of ignoring them & is not recursive
					// $fixed_array = array_replace_recursive($template_array, $received_array); 

					// removes the nested key/value instead of replacing the value
					// $fixed_array = array_merge($template_array, array_intersect_key($received_array, $template_array));

					// // semi-working PROTOTYPE. is too recursive, and only matches keys if they are on the same recursion. If a key is sent at lvl0 but it's at lvl1 in the template it remains unnaffected
					$fixed_array = array_replace_recursive($template_array, array_intersect_key($received_array, $template_array));


					if($received_body["template"]["mode"] == "raw") {
						header("Content-Type: application/json");
						echo json_encode($fixed_array);	
						exit;
					}

					// need to write function for this that would automatically fill out the status and message.
					$message = "Request successfull";
					generate_json_response($message, $fixed_array);	
					exit;
				}
				
			} else {
	
				if (file_exists($path_relative)) {
					//getfile
					$template_file = file_get_contents($path_relative);

					
					//set header
					$file_info = new finfo(FILEINFO_MIME_TYPE);
					$mime_type = $file_info->buffer($template_file);
					// header('Content-Type: ' . $mime_type);				

					if($received_body["template"]["mode"] == "raw") {
						header("Content-Type: " . $mime_type);
						echo $template_file;	
						exit;
					}

					//replace all double quotes with single quotes
					$template_contents = str_replace('"', "'", $template_file);

					$message = "Request successfull, this template has the $mime_type format";
					generate_json_response($message, $template_contents);
					exit;	

				} 
				
				else {	
					http_response_code(404);
					$message = "The requested template does not exist. Please go <server>/list.php to get a full list of available templates";
					generate_json_response($message);
					exit;
				}
			}
}
