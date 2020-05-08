<?php
//include response generator
include '../response.php';

//An array of HTTP methods that we want to allow.
$allowed_methods = array('GET');

//The current request type.
$request_method = strtoupper($_SERVER['REQUEST_METHOD']);

//If the request method isn't in our list of allowed methods.
if(!in_array($request_method, $allowed_methods)) {
    
    //Send a 405 Method Not Allowed header.
    // header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed", true, 405);
    http_response_code(405); //better and easier solution.

    //create response
    $message = "You are using " . $request_method . "\n Only GET is allowed at this endpoint.";
    generate_json_response($message);

	//Halt the script's execution.
	exit; 
	
} else {
	//getting path & filename from URI

    // $uri_full = explode('/', trim($_SERVER['REQUEST_URI'], '/')); //split full uri into an array
    // $uri_script = explode("/", trim($_SERVER['SCRIPT_NAME'], "/")); //split the uri-up-to-filename into an array
    // $uri_relative = array_diff($uri_full, $uri_script); //keep only the relative uri
    // $file_name = end($uri_relative);
	// $relative_path = "templates/" . implode("/", $uri_relative);

	//getting path & filename from $_GET
	$path = htmlspecialchars($_GET["path"]);
	$file_name = htmlspecialchars($_GET["filename"]);
	$relative_path = "templates" . DIRECTORY_SEPARATOR . $path;
	// echo $relative_path;

    if(!empty($path) || !empty($file_name)) {

		//if no file name provided, delete the specified directory.
        if(empty($file_name)) {           

            //this is 5.2+ PHP code
            $dir = $relative_path;
            $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it,
                        RecursiveIteratorIterator::CHILD_FIRST);
            foreach($files as $file) {
                if ($file->isDir()){
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            rmdir($dir);

            $message = "folder '{$relative_path}' and its contents have been deleted.";
            generate_json_response($message);
		}
		
        else {
			$full_path = $relative_path . DIRECTORY_SEPARATOR . $file_name;

			//If the file exists, delete file.
			if (file_exists($full_path)) {
				//delete the file
				unlink($full_path);
	
				//create response
				$message = "{$file_name} has been deleted from './$relative_path'";
				generate_json_response($message);
			}

			else {
				//if the user forgot to add .json, check for and if it exists delete the file.
				if (file_exists($full_path . ".json")) {
					//delete the json file
					unlink($full_path . ".json"); 
			
					//create response
					$message = "{$file_name}.json has been deleted from './$relative_path'";
					generate_json_response($message);
				}

				//if the file does not exist, send back an error.
				else {
                    //create response
                    http_response_code(404);
                    $message = "Neither {$file_name} nor {$file_name}.json have been found inside './$relative_path'";
                    generate_json_response($message);
                }      
            }
        }
	}
	
    else {
		http_response_code(500);
        $message = "You must provide a filename or directory to be deleted.";
        generate_json_response($message);
    }
    
}

?>