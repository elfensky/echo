<?php
require '../response.php';

//An array of HTTP methods that we want to allow.
$allowed_methods = array('GET');
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
	//if a path is given, use this path, otherwise use default path.
	$path = "templates";

	if(isset($_GET["path"])){
		$path .= DIRECTORY_SEPARATOR . htmlspecialchars($_GET["path"]);
	}

	//if the specified directory does not exist, show error and stop script
	if(!is_dir($path))
	{
		http_response_code(400);
		$message = "$path does not lead to an existing directory";
    	generate_json_response($message);
		exit;
	}
	
	//if no mode is selected, it defaults to the most complete reponse possible, showing the full
	//file tree, with all the folders and their contents
	if(!isset($_GET["mode"]))
	{
		//test2
		function dir_to_array($path)
		{
			$contents = array();
			# Foreach node in $path
			foreach (scandir($path) as $node) {
				# Skip link to the current and parent folders
				if ($node == '.') {
					continue;
				}
				if ($node == '..') {
					continue;
				}
				# Check if it's a node or a folder
				if (is_dir($path . DIRECTORY_SEPARATOR . $node)) {
					# Add directory recursively, be sure to pass a valid path
					# to the function, not just the folder's name
					$contents[$node] = dir_to_array($path . DIRECTORY_SEPARATOR . $node);
				} else {
					# Add node, the keys will be updated automatically
					$contents[] = $node;
				}
			}
			# done
			return $contents;
		}

		//generate response
		$message = "returning complete list of all folders and files in './$path'";
		$data = dir_to_array($path);
		generate_json_response($message, $data);
	}

	else if (isset($_GET["mode"]))
	{
		//if you're asking it to only show files, it will only show files in specified directory
		if(htmlspecialchars($_GET["mode"]) == "files")
		{
			// echo "show only files";

			// function files_to_array($path){
			// 	$result = array();
		
			// 	$result = glob("templates/*", GLOB_BRACE);
			
			// 	return $result;
			// }

			// $message = "returning a one-dimensional list of all files. Files in subdirectories are not shown.";
			// $data = files_to_array($path);
			// generate_json_response($message, $data);
			
				
			function files_to_array($path){
				$result = array();

				$dir = scandir($path);

				foreach($dir as $file => $value)
				{
					if(is_file($path . DIRECTORY_SEPARATOR . $value)){
						array_push($result,$value);
					}
				}

				return $result;
			}

			$message = "returning a one-dimensional array of all files inside './$path'. Subdirectories are not shown.";
			$data = files_to_array($path);
			generate_json_response($message, $data);
		}

		//if you ask it to show folders, it will show folders in the specified directory (no tree, 1 dimensional)
		else if (htmlspecialchars($_GET["mode"]) == "folders")
		{
			// echo "show only directories";

			function folders_to_array($path){

				$path = $path . "/*"; 
				$result = array();

				foreach(glob($path, GLOB_ONLYDIR) as $dir) {
					array_push($result, basename($dir));
					// $result = basename($dir);
				}

				return $result;
			}

			$message = "returning a one-dimensional array of all folders inside './$path'. Subdirectories are not shown.";
			$data = folders_to_array($path);
			generate_json_response($message, $data);
		}

		//400 error
		else{
			http_response_code(400);
			$message = "Only 'files' and 'folders' are acceptable values for mode";
			generate_json_response($message);
		}
	}

	
}

?>