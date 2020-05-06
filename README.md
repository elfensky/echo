# stage

A repository containing various projects created by Andrei Lavrenov during his internship at the Directorate of police information and ICT. 

## response.php
A helper class that's included with every microservice and generates a proper json response for any possible need. 

## echo | body.php
When accessed, it returns the body of the received POST request. Any type of body is accepted, and responses try to be as close as possible to the originally received format and mime_type.

It will deny any GET requests and empty POST bodies.

if ?mode=json is specified, instead of returning the body with corresponding mime-type header, it will return a .json response with the data (whether it's text or binary) inside a json object.


