<?php
//include response generator
// require '../../response.php';

//An array of HTTP methods that we want to allow.
$allowedMethods = array('POST', 'GET');

//The current request type.
$requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);
// echo "$requestMethod";
//If the request method isn't in our list of allowed methods.
if (!in_array($requestMethod, $allowedMethods)) {

    //set code
    http_response_code(405);

    //create response
    $message = "Only POST and GET are allowed at this endpoint.";
    // $data = array("example"=>"this endpoint requires specifying /<name> of the used template \n ../template.php/example");
    generate_json_response($message);

    exit; //Halt the script's execution.

} 

else {
	if($requestMethod == "POST"){ //IF POST

		if(isset($_GET["v"])){
			$v = htmlspecialchars($_GET['v']);
			$db = new SQLite3('../db/echo.sqlite', SQLITE3_OPEN_READWRITE);
			$stmt2 = "SELECT * FROM template_data AS td WHERE td.data_id = $v";
			$template_data = $db->query($stmt2)->fetchArray();

			$required = json_decode($template_data["template"], true)["required"];
			$structure = json_decode($template_data["template"], true)["structure"];

			$received = file_get_contents("php://input");

			if($required){ //if template HAS REQUIRED KEYS

				if($received){
					//try to process to json, if fail error out, if success edit template
					$received_json = json_decode(file_get_contents("php://input"), true);

					if ($received_json === null && json_last_error() !== JSON_ERROR_NONE) { //if badly formatted JSON post body, error out
						http_response_code(400);
						$message = "JSON is badly formatted";
						generate_json_response($message, null, $required);
					} 
					else { //if json processed

						$received_keys = array_keys($received_json);
		
						if(array_intersect($required, $received_keys) === $required){ //if all matching keys are present
		
							foreach($received_json as $key => $value){ //for each key in post body change value
								replace_value_by_key($structure, $key, $value);
							}
		
							$db2 = new SQLite3('../db/random.sqlite', SQLITE3_OPEN_READWRITE);
							replace_with_random_data($db2, $structure);

		
							$message = "Request successful";
							generate_json_response($message, $structure, $required);
		
						} else { //if not all required keys are mentioned
							http_response_code(400);
							$message = "All required keys are must be present in the POST body";
							generate_json_response($message, null, $required);
						}
					}

				}
				else {
					http_response_code(400);
					$message = "All required keys are must be present in the POST body";
					generate_json_response($message, null, $required);
				}				
			}

			else {
				if($received){ //if we have something in the post body
					$received_json = json_decode(file_get_contents("php://input"), true);

					if ($received_json === null && json_last_error() !== JSON_ERROR_NONE) { //if badly formatted JSON post body, error out
						http_response_code(400);
						$message = "JSON is badly formatted 3";
						generate_json_response($message, null, $required);
					} 
					else { //if JSON is formatted well
						// echo $received_json;
						foreach($received_json as $key => $value){ //for each key in post body change value
							// echo "$key : $value \n";
							replace_value_by_key($structure, $key, $value);
						}
	
						$db2 = new SQLite3('../db/random.sqlite', SQLITE3_OPEN_READWRITE);
						replace_with_random_data($db2, $structure);
	
						$message = "Request successful 3";
						generate_json_response($message, $structure, $required);
					}					

				} else {  // IF post body is EMPTY just return the selected template unchanged, like GET	
					$db2 = new SQLite3('../db/random.sqlite', SQLITE3_OPEN_READWRITE);
					replace_with_random_data($db2, $structure);
					
					$message = "Request successfull 4";
					generate_json_response($message, $structure);
				}
				
			}

		} 
		else { //IF NO TEMPLATE IS SELECTED return body. 
			$content_type = $_SERVER["CONTENT_TYPE"];

			if(strpos($content_type, "multipart/form-data;") !== false) {
				//create response
				$message = "returning JSON data";
				$data = "somedata";
				generate_json_response($message, $_POST + $_FILES);
				
			} else {
				header("Content-Type: " . $content_type);
				echo file_get_contents("php://input");
			}
		}
	}

	else{ //IF GET
		if(isset($_GET["v"])){ //IF GET AND TEMPLATE IS SELECTED

			$v = htmlspecialchars($_GET['v']);
			$db = new SQLite3('../db/echo.sqlite', SQLITE3_OPEN_READWRITE);
			$stmt2 = "SELECT * FROM template_data AS td WHERE td.data_id = $v";
			$template_data = $db->query($stmt2)->fetchArray();

			$required = json_decode($template_data["template"], true)["required"];
			$structure = json_decode($template_data["template"], true)["structure"];

			if($required){ //if template has required keys, error out
				http_response_code(424);
				$message = "Template contains required keys, you must POST json with matching keys to overwrite them";
				generate_json_response($message, null, $required);
			}
			else { //if no required keys are present, respond with the template

				$db2 = new SQLite3('../db/random.sqlite', SQLITE3_OPEN_READWRITE);
				replace_with_random_data($db2, $structure);

				$message = "Request successfull 5";
				generate_json_response($message, $structure);
			}

		} else { //IF GET AND NO TEMPLATE SELECTED, error out
			http_response_code(400);
			$message = "You must specify the template you want to use when using GET. Go to /echo/src/public/index.php to browse through the available templates.";
			generate_json_response($message, $structure, $required);
		}
	}
}

function generate_json_response($message = NULL, $data = NULL, $required_keys = NULL) {

    //---a list of all status codes and the corresponding message---//
    $http_status_codes = array(100 => "Continue", 
                            101 => "Switching Protocols", 
                            102 => "Processing", 
                            200 => "OK", 
                            201 => "Created", 
                            202 => "Accepted", 
                            203 => "Non-Authoritative Information", 
                            204 => "No Content", 
                            205 => "Reset Content", 
                            206 => "Partial Content", 
                            207 => "Multi-Status", 
                            300 => "Multiple Choices", 
                            301 => "Moved Permanently", 
                            302 => "Found", 
                            303 => "See Other", 
                            304 => "Not Modified", 
                            305 => "Use Proxy", 
                            306 => "(Unused)", 
                            307 => "Temporary Redirect", 
                            308 => "Permanent Redirect", 
                            400 => "Bad Request", 
                            401 => "Unauthorized", 
                            402 => "Payment Required", 
                            403 => "Forbidden", 
                            404 => "Not Found", 
                            405 => "Method Not Allowed", 
                            406 => "Not Acceptable", 
                            407 => "Proxy Authentication Required", 
                            408 => "Request Timeout", 
                            409 => "Conflict", 
                            410 => "Gone", 
                            411 => "Length Required", 
                            412 => "Precondition Failed", 
                            413 => "Request Entity Too Large", 
                            414 => "Request-URI Too Long", 
                            415 => "Unsupported Media Type", 
                            416 => "Requested Range Not Satisfiable", 
                            417 => "Expectation Failed", 
                            418 => "I'm a teapot", 
                            419 => "Authentication Timeout", 
                            420 => "Enhance Your Calm", 
                            422 => "Unprocessable Entity", 
                            423 => "Locked", 
                            424 => "Failed Dependency", 
                            424 => "Method Failure", 
                            425 => "Unordered Collection", 
                            426 => "Upgrade Required", 
                            428 => "Precondition Required", 
                            429 => "Too Many Requests", 
                            431 => "Request Header Fields Too Large", 
                            444 => "No Response", 
                            449 => "Retry With", 
                            450 => "Blocked by Windows Parental Controls", 
                            451 => "Unavailable For Legal Reasons", 
                            494 => "Request Header Too Large", 
                            495 => "Cert Error", 
                            496 => "No Cert", 
                            497 => "HTTP to HTTPS", 
                            499 => "Client Closed Request", 
                            500 => "Internal Server Error", 
                            501 => "Not Implemented", 
                            502 => "Bad Gateway", 
                            503 => "Service Unavailable", 
                            504 => "Gateway Timeout", 
                            505 => "HTTP Version Not Supported", 
                            506 => "Variant Also Negotiates", 
                            507 => "Insufficient Storage", 
                            508 => "Loop Detected", 
                            509 => "Bandwidth Limit Exceeded", 
                            510 => "Not Extended", 
                            511 => "Network Authentication Required", 
                            598 => "Network read timeout error", 
                            599 => "Network connect timeout error"
                            );


	//---end of http_status_codes array---//
	
	if(!$required_keys){
		$required_keys = "none";
	}
	else{
		$required_keys = implode(", ", $required_keys);
	}


    header('Content-Type: application/json');

    $response = array("status" => $http_status_codes[http_response_code()],
                      "code" => http_response_code(),
					  "message" => $message,
					  "required" => $required_keys);

    
    echo json_encode(array("response" => $response, "echo" => $data), JSON_FORCE_OBJECT);
}

function replace_value_by_key(&$array, $keyFind, $valueReplace) {
	
	foreach ($array as $key => &$value) {
		if (is_array($value)) {
			if($key == $keyFind){
				$array[$key] = $valueReplace;
			}
			else {
				replace_value_by_key($value, $keyFind, $valueReplace);
			}
			
		}
		else {
			if($key == $keyFind){
				$array[$key] = $valueReplace;
			}
		}
	}
}

function replace_with_random_data($db2, &$array){

	foreach ($array as $key => &$value) {
		if (is_array($value)) {
			replace_with_random_data($db2, $value);

		} else {
			if(is_string("$array[$key]")){

				if(substr($value, 0, 2) == "[[" && substr($value,-2) == "]]"){
					// echo $value;

					$column =  substr($value, 2, -2);
					$result = "";

					//GENERATABLE (TRULY RANDOM) VARIABLES
					if($column == "userId"){
						for($i = 0; $i < 10; $i++) {
							$result .= mt_rand(0, 9);
							$value = $result;
						}
					}
					else if($column == "date"){
						$time = rand(0, time());
						$value = date("m-d-Y", $time);
					}

					else if($column == "time"){
						$time = rand(0, time());
						$value = date("h:i:sa", $time);
					}

					else if($column == "today"){
						$time = time();
						$value = date("m-d-Y", $time);
					}

					//GET RANDOM FROM DATABASE
					else {
						$query = "SELECT * FROM $column ORDER BY RANDOM() LIMIT 1";
						$result = $db2->query($query)->fetchArray();
						$value = $result[0];
					}											
				}
			}
		}
	}		
}

