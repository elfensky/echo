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


//testing binary files2, promising result

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
//issue2: when a content_type that does not have a single defined extension is used, the extension is set to .bin, which isn't right. 
//issue3: form-data body is not returned.
$contentType = $_SERVER["CONTENT_TYPE"];
header("Content-Type: " . $contentType);
// header("Content-Transfer-Encoding: Binary"); //unneccesary
echo file_get_contents("php://input");

?>