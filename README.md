# echo microservice

A repository containing various projects created by **Andrei Lavrenov** during his internship at the Directorate of police information and ICT. 

## response.php
should not be accessed directly, but if accessed will remain inert

A helper function that's included with every microservice and generates a proper json response for any possible need. 
The function is called **generate_json_response($message, $data);**  both both variables being optional and defaulting to NULL. 

A response is provided in the following format:
```
{
    "response": {
        "status": "OK",
        "code": 200,
        "message": $message
    },
    "data": "$data"
}
```


## body.php
requires POST

When accessed, it returns the body of the received POST request. Any type of body is accepted, and responses try to be as close as possible to the originally received format and mime_type.

It will deny any GET requests and empty POST bodies.

*not yet implemented:
if ?mode=json is specified, instead of returning the body with corresponding mime-type header, it will return a .json response with the data, converted to base64 inside a json object. b64 is needed because json only support utf-8*


## delete.php
requires GET

this allows the client to delete the specified folder or file from the ./templates directory. 
The extension only has to be provided if the template is something other than json or extensionless file.
```
http://<server>/delete.php/<path>/<to>/<file>.<extension>
```

## upload.php
requires POST

allows the user to upload files to the ./templates directory using curl.
```
curl -F "uploaded_file=@my_file.txt" http://<server>/upload.php
```
allows specifying a directory inside the templates folder by extending the url.*
eg: 
```
.../upload.php/<specific>/<directory>
```
will upload to 
```
./templates/specific/directory/my_file.txt
```

## list.php
requires GET

provides a response with an array of all available templates, directories and files inside the directories in json format.
example:
```
response
```

## template.php
Provides a response using an existing template from templates. 
It is non recursive, so if you want the template to use your key value, it must be nested the same as in the template



## template_recursive.php
No matter where your key is, if the template has the same one, its value will be replaced by yours
