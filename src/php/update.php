<?php
if(isset($_POST)){
	//GET DATA
	$v = htmlspecialchars($_POST["data_id"]);

	$template_name = htmlspecialchars($_POST["template_name"]);
	$version_name = htmlspecialchars($_POST["version_name"]);
	$description = htmlspecialchars($_POST["description"]);
	$author = htmlspecialchars($_POST["author"]);
	
	$version_number = (int) htmlspecialchars($_POST["version_number"]) + 1;
	$info_id = (int) htmlspecialchars($_POST["info_id"]);

	$departments = htmlspecialchars($_POST["departments"]);
	$departments = json_decode(str_replace('&quot;', '"', $departments), true);

	$template = htmlspecialchars($_POST["template"]);
	$template = str_replace('&quot;', '"', $template);

	$datetime = new DateTime();
	$date = date("Y-m-d H:i:s");


	//DATABASE MANIPULATION
	$db = new SQLite3('../db/echo.sqlite', SQLITE3_OPEN_READWRITE);
	
	//UPDATE TEMPLATE_DATA
	$stmt1 = $db->prepare("UPDATE template_data SET 	template = :template,
													version_name = :version_name, 
													version_number = :version_number,
													last_modified_by = :last_modified_by,
													last_modified_on = :last_modified_on,													
													data_description = :data_description 
													WHERE data_id=:data_id;");

	$stmt1->bindValue(":template", $template);
	$stmt1->bindValue(":version_name", $version_name);
	$stmt1->bindValue(":version_number", $version_number);
	$stmt1->bindValue(":last_modified_by", $author);
	$stmt1->bindValue(":last_modified_on", $date);
	$stmt1->bindValue(":data_description", $description);
	$stmt1->bindValue(":data_id", $v);
	$status1 = $stmt1->execute();
	// echo "template_data updated \n";

	//UPDATE TEMPLATE_INFO
	$versions = $db->querySingle("SELECT COUNT(*) FROM template_data WHERE info_id = $info_id;");

	$stmt2 = $db->prepare("UPDATE template_info SET template_name = :template_name,
													last_modified_by = :last_modified_by,
													last_modified_on = :last_modified_on,
													versions = :versions 
													WHERE info_id=:info_id;");
	$stmt2->bindValue(":template_name", $template_name);												
	$stmt2->bindValue(":last_modified_by", $author);
	$stmt2->bindValue(":last_modified_on", $date);
	$stmt2->bindValue(":versions", $versions);
	$stmt2->bindValue(":info_id", $info_id);
	$status2 = $stmt2->execute();


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
	
	echo "success";
}

?>