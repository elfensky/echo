<?php
//include response generator
require '../response.php';

//An array of HTTP methods that we want to allow.
$allowed_methods = array('POST');

//The current request type.
$request_method = strtoupper($_SERVER['REQUEST_METHOD']);

//If the request method isn't in our list of allowed methods.
if(!in_array($request_method, $allowed_methods)) {
    
    http_response_code(405);

    //create response
    $message = "You are using " . $request_method . "\n Only GET is allowed at this endpoint.";
    generate_json_response($message);
    
    //Halt the script's execution.
    exit;
}

else {
    //This will only be executed out if a POST request is used.
    $content_type = $_SERVER["CONTENT_TYPE"];

    if(strpos($content_type, "multipart/form-data;") !== false)
    {
        //create response
        $message = "returning JSON data";
        $data = "somedata";
        generate_json_response($message, $_POST + $_FILES);
    }
    else
    {
        header("Content-Type: " . $content_type);
        echo file_get_contents("php://input");
    }
}


?>