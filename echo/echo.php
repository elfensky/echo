<?php

//initial 
// echo file_get_contents('php://input');


//testing binary files1
// $file = fopen("php://input", "rw");
// echo $file;


//useful, gets content type of submitted file
//question, is it possible to submit multiple files at ones using an api end-point? If so this breaks down.
// $contentType = $_SERVER["CONTENT_TYPE"]


//doesn't work on form-less post requests, needs <input type='file'>
// $file = $_FILES
// echo $file


//testing binary files2, promising result -> displays image in postman
// $attachment_location = "test/test.txt";
// if (file_exists($attachment_location)) {
//     header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
//     header("Cache-Control: public"); // needed for internet explorer
//     header("Content-Type: application/text");
//     header("Content-Transfer-Encoding: Binary");
//     header("Content-Length:".filesize($attachment_location));
//     header("Content-Disposition: attachment;");
//     readfile($attachment_location);
//     die();       
// } 
// else {
//     die("Error: File not found.");
// } 

//working prototype

//issue1: response file NAME is not the same
//issue2: when a content_type that does not have a single defined extension is used, the file EXTENSION is set to .bin, which isn't right.
//        for example, when it's data:application/octet-stream 
//issue3: form-data body is not returned.

$contentType = $_SERVER["CONTENT_TYPE"];

// header("Content-Type: " . $contentType);
// // header("Content-Transfer-Encoding: Binary"); //unneccesary
// echo file_get_contents("php://input");


// adding support for form-data
if(strpos($contentType, "multipart/form-data;") !== false)
{
    // ugly/badly readable.
    // echo var_dump($_POST);
    // echo var_dump($_FILES);

    // // works but ugly/not well readable
    // foreach ($_POST as $key => $value) {
    //     echo htmlspecialchars($key) . "=" . htmlspecialchars($value)."\r\n";
    // }

    // //can't access binary data. Is it neccesary?
    // foreach ($_FILES as $key => $value) {
    //     //return filename
    //     // echo htmlspecialchars($key) . "=" . $_FILES[$key]['name'] . "\r\n"; 

    //     //return actual binary data
    //     echo htmlspecialchars($key) . "=" . file_get_contents($_FILES[$key]['tmp_name']) . "\r\n"; 
    // }

    //nicely readable json-formatted response, but no access to raw binary data
    //if neccesary, it's possible to iterate over the encoded json, and add a extra key-value pair with the raw binary data.
    //so "data":"raw_binary"; 
    print_r(json_encode($_POST + $_FILES)); 
    print_r();
}
else
{
    header("Content-Type: " . $contentType);
    // header("Content-Transfer-Encoding: Binary"); //unneccesary
    echo file_get_contents("php://input");

    // $finfo = new finfo(FILEINFO_MIME_TYPE);
    // $mimeType = $finfo->buffer(file_get_contents("php://input"));
    // echo $mimeType;
}





?>