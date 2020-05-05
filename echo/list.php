<?php

//An array of HTTP methods that we want to allow.
$allowedMethods = array('GET');

//The current request type.
$requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);

//If the request method isn't in our list of allowed methods.
if(!in_array($requestMethod, $allowedMethods)) {
    
    //Send a 405 Method Not Allowed header.
    header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed", true, 405);
    echo "You are using " . $requestMethod . "\r\n";
    echo "Only GET is allowed at this endpoint";
    
    //Halt the script's execution.
    exit;
}

else {
    //This will only be executed out if a POST request is used.

    $dir = "templates"; //directory path where templates are stored
    $files = scandir($dir); //scan the directory with templates

    //remove the ".", "..", and ".DS_Store" from the array
    // $files = array_slice(scandir('templates'), 3); //filesystem dependant and cannot be relied upon. Non-linux systems might not have the dots or a .DS_Store

    // perhaps a better way, but it complicates the result
    $files = array_filter(scandir("templates"), function($item) {
        return $item[0] !== '.';
    });

    header('Content-Type: application/json'); //set header content-type to json
    echo json_encode(array_values($files)); //takes only the values from the array (no keys), and encodes them into json
}

?>