<?php

//An array of HTTP methods that we want to allow.
$allowed_methods = array('GET');

//The current request type.
$request_method = strtoupper($_SERVER['REQUEST_METHOD']);

//If the request method isn't in our list of allowed methods.
if(!in_array($request_method, $allowed_methods)) {
    
    //Send a 405 Method Not Allowed header.
    header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed", true, 405);
    echo "You are using " . $request_method . "\r\n";
    echo "Only GET is allowed at this endpoint \r\n";

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

    if (file_exists($path_relative . ".json")) {
        unlink($path_relative . ".json"); //delete the file

        header('Content-Type: application/json');
        $response = array("response" => 
                                array("status" => "OK",
                                      "code" => http_response_code(),
                                      "message" => "{$file_name}.json has been deleted."));

        echo json_encode($response);
    }
    else {
        if (file_exists($path_relative)) {
            unlink($path_relative); //delete the file

            header('Content-Type: application/json');
            $response = array("response" => 
                                array("status" => "OK",
                                      "code" => http_response_code(),
                                      "message" => "{$file_name} has been deleted."));

            echo json_encode($response);
        }
        else {
            http_response_code(404);
            header('Content-Type: application/json');
            $response = array("response" => 
                                    array("status" => "Not Found",
                                          "code" => http_response_code(),
                                          "message" => "Neither {$file_name}.json nor {$file_name} exist and thus cannot be deleted."));

            echo json_encode($response);
        }
    }
}

?>