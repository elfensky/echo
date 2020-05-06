<!DOCTYPE html>
<html>
<head>
  <title>Upload your files</title>
</head>
<body>
  <form enctype="multipart/form-data" action="upload.php" method="POST">
    <p>Upload your file</p>
    <input type="file" name="uploaded_file"></input><br />
    <input type="submit" value="upload"></input>
  </form>
</body>
</html>

<?php

//An array of HTTP methods that we want to allow.
$allowed_methods = array('POST');

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
    if(!empty($_FILES['uploaded_file']))
  {
    $path = "templates/";
    $path = $path . basename( $_FILES['uploaded_file']['name']);
    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path)) {
      echo "The file ".  basename( $_FILES['uploaded_file']['name']). 
      " has been uploaded";
    } else{
        echo "There was an error uploading the file, please try again!";
    }
  }
}

?>
