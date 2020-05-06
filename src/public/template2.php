<?php

//An array of HTTP methods that we want to allow.
$allowedMethods = array(
    'POST'
);

//The current request type.
$requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);

//If the request method isn't in our list of allowed methods.
if (!in_array($requestMethod, $allowedMethods)) {
    
    //Error
    //Send a 405 Method Not Allowed header.
    // header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed", true, 405);
    http_response_code(405);
    header();

    echo "You are using " . $requestMethod . "\r\n";
    echo "Only POST is allowed at this endpoint \r\n";
    
    //Documentation and/or example
    echo "this endpoint requires specifying /<name> of the used template \r\n"; //documentation
    echo "../template.php/example"; //provide an example.json in the /templates directory, that explains it further. 

    exit; //Halt the script's execution.

}

else {
    $content_type = $_SERVER["CONTENT_TYPE"];
    
    //test for content_type, and only allow JSON and XML
    //need to rewrite this, anything is pretty much ok? Or should it be only json?
    if (strpos($content_type, 'json') == FALSE && strpos($content_type, 'xml') == FALSE) {

        // header($_SERVER["SERVER_PROTOCOL"]." 415 Unsupported Media Type", true, 415);
        http_response_code(415);
        header();

        if($content_type == NULL){
            echo "You body empty." . "\r\n";
            echo "You must post something in JSON or XML";
        }
        else{
            echo "You body contains " . "$content_type" . "\r\n";
            echo "You must only post JSON or XML";
        }
        
    }

    else {
         //This will only be executed out if a POST request is used and the content_type is allowed.

        $uri_full = explode('/', trim($_SERVER['REQUEST_URI'], '/')); //split full uri into an array
        // foreach ($uri_full_array as $uri_item){echo $uri_item . "\r\n";} echo "\r\n"; //testing $uri_full

        $uri_script = explode("/", trim($_SERVER['SCRIPT_NAME'], "/")); //split the uri-up-to-filename into an array
        // foreach ($uri_script_array as $uri_item){echo $uri_item . "\r\n";} echo "\r\n"; //testing $uri_script

        $uri_relative = array_diff($uri_full, $uri_script); //keep only the relative uri
        // foreach ($uri_relative as $uri_item){echo $uri_item . "\r\n";} echo "\r\n"; //testing $uri_relative


        $path_relative = "templates/" . implode("/", $uri_relative);
        // echo $path_relative; echo "\r\n"; //testing $path_relative
        // echo $path_relative . ".json"; echo "\r\n"; //testing $path_relative

        //check if json file exists
        if(file_exists($path_relative . ".json")) {

            //get template file
            $path_relative = $path_relative . ".json";
            $template_file = file_get_contents($path_relative); // print_r($file); echo "\r\n";
            $template_array = json_decode($template_file, true); // print_r($template_array); echo "\r\n";

            //set Content-Type header
            $file_info = new finfo(FILEINFO_MIME_TYPE);
            $mime_type = $file_info->buffer($template_file);
            header('Content-Type: ' . $mime_type);

            //get posted data //this is the received JSON data, converted into an array
            $received_array = json_decode(file_get_contents("php://input"), true); // print_r($received_array); echo "\r\n";
            



            //-----------TESTING NATIVE BUILT-IN WAYS TO COMPARE ARRAYS & REPLACE VALUES IF KEYS MATCH-----------//
            

            //------------NEW WAY, MANUALLY WRITING THE FUNCTIONS W/ FOREACH/LOOPS------------//
            print_r($template_array);
        }

        else {

            if(file_exists($path_relative)) {
                //getfile
                $template_file = file_get_contents($path_relative);

                //set header
                $file_info = new finfo(FILEINFO_MIME_TYPE);
                $mime_type = $file_info->buffer($template_file);
                header('Content-Type: ' . $mime_type);

                //show file
                echo $template_file;
            }

            else{
                http_response_code(404);
                header('Content-Type: application/json');
                $response = array("response" => 
                                        array("status" => "Not Found",
                                              "code" => http_response_code(),
                                              "message" => "The requested template does not exist. Please go echo/templates.php to get a full list of available templates"));

                echo json_encode($response);
            }
        }
    }
}

?>