<?php
if(isset($_POST)){
	//GET DATA
	// $v = htmlspecialchars($_POST["data_id"]);

	$template_name = htmlspecialchars($_POST["template_name"]);
	$version_name = htmlspecialchars($_POST["version_name"]);
	$description = htmlspecialchars($_POST["description"]);
	$author = htmlspecialchars($_POST["author"]);
	
	// $version_number = (int) htmlspecialchars($_POST["version_number"]) + 1;
	// $info_id = (int) htmlspecialchars($_POST["info_id"]);

	$departments = htmlspecialchars($_POST["departments"]);
	$departments = json_decode(str_replace('&quot;', '"', $departments), true);

	$template = htmlspecialchars($_POST["template"]);
	$template = str_replace('&quot;', '"', $template);

	$datetime = new DateTime();
	$date = date("Y-m-d H:i:s");
	
	// echo $template_name, "\n", $version_name, "\n", $description, "\n", $author, "\n", $date, "\n", 1, $departments, "\n", $template, "\n";

	$db = new SQLite3('../db/echo.sqlite', SQLITE3_OPEN_READWRITE);

	//create new entry in template_info (new template)
	$t_info = $db->prepare("INSERT INTO template_info (template_name, last_modified_by, last_modified_on, versions) VALUES (:template_name, :last_modified_by, :last_modified_on, :versions);");
	$t_info->bindValue(":template_name", $template_name);
	$t_info->bindValue(":last_modified_by", $author);
	$t_info->bindValue(":last_modified_on", $date);
	$t_info->bindValue(":versions", 1);
	$t_info->execute();

	// get info_id from template_info
	$info_id = $db->lastInsertRowID();

	//using info_id create new entry in template_data
	$t_data = $db->prepare("INSERT INTO template_data (info_id, template, version_name, version_number, last_modified_by, last_modified_on, data_description) VALUES (:info_id, :template, :version_name, :version_number, :last_modified_by, :last_modified_on, :data_description);");
	$t_data->bindValue(":info_id", $info_id);
	$t_data->bindValue(":template", $template);
	$t_data->bindValue(":version_name", $version_name);
	$t_data->bindValue(":version_number", 1);
	$t_data->bindValue(":last_modified_by", $author);
	$t_data->bindValue(":last_modified_on", $date);
	$t_data->bindValue(":data_description", $description);
	$t_data->execute();

	//get data_id
	$data_id = $db->lastInsertRowID();

	//UPDATE TEMPLATES_DEPARTMENTS with NEW
	$db->querySingle("DELETE FROM templates_departments WHERE template_id = $info_id;");
		
	if(empty($departments)){
		$db->querySingle("INSERT INTO templates_departments (template_id, department_id) VALUES ($info_id, 0);");
	} else {
		foreach ($departments as $department){
			$update_deps = $db->prepare("INSERT INTO templates_departments (template_id, department_id) VALUES (:info_id, :department);");
			$update_deps->bindValue(":info_id", (int) $info_id);
			$update_deps->bindValue(":department", (int) $department);
			$update_deps->execute();
		}
	}	

	$db->close();

	//used to redirect ot the newly created template
	echo $data_id;
}
?>