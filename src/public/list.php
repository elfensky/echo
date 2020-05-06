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
    //This will only be executed out if a POST request is used.

    $dir = "templates"; //directory path where templates are stored
    $files = scandir($dir); //scan the directory with templates

    //remove the ".", "..", and ".DS_Store" from the array
    // $files = array_slice(scandir('templates'), 3); //filesystem dependant and cannot be relied upon. Non-linux systems might not have the dots or a .DS_Store

    // removes all files that start with .
    $files = array_filter(scandir("templates"), function($item) {
        return $item[0] !== '.';
    });

    // function dir_to_array($dir)
    // {
    //         if (! is_dir($dir)) {
    //                 // If the user supplies a wrong path we inform him.
    //                 return null;
    //         }
    
    //         // Our PHP representation of the filesystem
    //         // for the supplied directory and its descendant.
    //         $data = [];
    
    //         foreach (new DirectoryIterator($dir) as $f) {
    //                 if ($f->isDot()) {
    //                         // Dot files like '.' and '..' must be skipped.
    //                         continue;
    //                 }
    
    //                 $path = $f->getPathname();
    //                 $name = $f->getFilename();
    
    //                 if ($f->isFile()) {
    //                         $data[] = [ 'file' => $name ];
    //                 } else {
    //                         // Process the content of the directory.
    //                         $files = dir_to_array($path);
    
    //                         $data[] = [ 'dir'  => $files,
    //                                     'name' => $name ];
    //                         // A directory has a 'name' attribute
    //                         // to be able to retrieve its name.
    //                         // In case it is not needed, just delete it.
    //                 }
    //         }
    
    //         // Sorts files and directories if they are not on your system.
    //         \usort($data, function($a, $b) {
    //                 $aa = isset($a['file']) ? $a['file'] : $a['name'];
    //                 $bb = isset($b['file']) ? $b['file'] : $b['name'];
    
    //                 return \strcmp($aa, $bb);
    //         });
    
    //         return $data;
    // }

    // function dir_to_json($dir)
    // {
    //         $data = dir_to_array($dir);
    //         $data = json_encode($data);
    
    //         return $data;
    // }
    // echo dir_to_json($dir);
    // $x = array("file1","file2","file3","dir1" => array("file1","file2","file3"), "dir2" => array("file1","file2","file3"));


    header('Content-Type: application/json'); //set header content-type to json
    echo json_encode(array_values($files)); //takes only the values from the array (no keys), and encodes them into json

    echo "\n\n\n";

    
}

?>