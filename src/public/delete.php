<?php
//include response generator
include 'response.php';

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


    exit; //Halt the script's execution.
}

else {
    $uri_full = explode('/', trim($_SERVER['REQUEST_URI'], '/')); //split full uri into an array
    $uri_script = explode("/", trim($_SERVER['SCRIPT_NAME'], "/")); //split the uri-up-to-filename into an array
    $uri_relative = array_diff($uri_full, $uri_script); //keep only the relative uri
    $file_name = end($uri_relative);
    $path_relative = "templates/" . implode("/", $uri_relative);
    // echo $path_relative; echo "\r\n"; //testing $path_relative
    // echo $path_relative . ".json"; echo "\r\n"; //testing $path_relative
    if(!empty($file_name)) {

        if(is_dir($path_relative)) {            

            //this is 5.2+ PHP code
            $dir = $path_relative;
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

            $message = "folder '{$file_name}' has been deleted.";
            generate_json_response($message);
        }
        else {
            if (file_exists($path_relative . ".json")) {
                //delete the json file
                unlink($path_relative . ".json"); 
        
                //create response
                $message = "{$file_name}.json has been deleted.";
                generate_json_response($message);
            }
            else {
                if (file_exists($path_relative)) {
                    //delete the file
                    unlink($path_relative);
        
                    //create response
                    $message = "{$file_name} has been deleted.";
                    generate_json_response($message);
                }
        
                else {
                    //create response
                    http_response_code(404);
                    $message = "Neither {$file_name}.json nor {$file_name} exist and thus cannot be deleted.";
                    generate_json_response($message);
                }
            }
        }
    }
    else {
        http_response_code(500);
        $message = "You must provide a filename to be deleted.";
        generate_json_response($message);
    }
    
}

?>