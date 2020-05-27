<?php

//connect (or create if it doesn't exist) to database
$db = new SQLite3('db/echo.sqlite');

//if it's a new database, create the neccesary tables
//template_info, this is the main table used for generating the list from which you can select a template
$db->querySingle('CREATE TABLE IF NOT EXISTS template_info (id integer PRIMARY KEY AUTOINCREMENT,
															template_name VARCHAR(64),
															created_by VARCHAR(64),
															created_at DATETIME,
															last_modified DATETIME,
															last_version integer
															)');

//department, this is the table that has all the possible department tags 
$db->querySingle('CREATE TABLE IF NOT EXISTS department (id INTEGER PRIMARY KEY AUTOINCREMENT,
														department_name VARCHAR(32),
														color VARCHAR(6)
														)');

//templates_departments, it's the in-between table that stores what template has what tags
$db->querySingle('CREATE TABLE IF NOT EXISTS templates_departments (id INTEGER PRIMARY KEY AUTOINCREMENT,
																	template_info_id INTEGER,
																	department_id INTEGER,
																	FOREIGN KEY(template_info_id) REFERENCES template_info(id),
																	FOREIGN KEY(department_id) REFERENCES department(id)
																	)');

//template_data, the table where the template themselves are stored, alongside multiple versions of themselves etc.
$db->querySingle('CREATE TABLE IF NOT EXISTS template_data (id INTEGER PRIMARY KEY AUTOINCREMENT,
															template_info_id INTEGER,
															template BLOB,
															version_number INTEGER,
															created_by VARCHAR(64),
															created_at DATETIME,
															last_modified DATETIME,
															FOREIGN KEY(template_info_id) REFERENCES template_info(id)
															)');

//request table where all the requests are stored. They are linked to each template using its id as the foreign key the table where the template themselves are stored, alongside multiple versions of themselves etc.
$db->querySingle('CREATE TABLE IF NOT EXISTS request (id INTEGER PRIMARY KEY AUTOINCREMENT,
													template_data_id INTEGER,
													request BLOB,
													version_number INTEGER,
													created_by VARCHAR(64),
													created_at DATETIME,
													last_modified DATETIME,
													FOREIGN KEY(template_data_id) REFERENCES template_data(id)
													)');



?>