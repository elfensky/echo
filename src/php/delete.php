<?php

//delete specific version of a template (accessed at "view.php" and "edit.php")
if(isset($_POST["data_id"])){
	//GET DATA
	$v = htmlspecialchars($_POST["data_id"]);
	$author = htmlspecialchars($_POST["author"]);
	$info_id = (int) htmlspecialchars($_POST["info_id"]);

	$datetime = new DateTime();
	$date = date("Y-m-d H:i:s");
	// echo "success";

	//DATABASE MANIPULATION
	$db = new SQLite3('../db/echo.sqlite', SQLITE3_OPEN_READWRITE);
	
	//UPDATE TEMPLATE_DATA
	$stmt1 = $db->prepare("DELETE FROM template_data WHERE data_id = :data_id;");
	$stmt1->bindValue(":data_id", $v);
	$status1 = $stmt1->execute();
	// echo "template_data updated \n";
	
	//UPDATE TEMPLATE_INFO
	$versions = $db->querySingle("SELECT COUNT(*) FROM template_data WHERE info_id = $info_id;");

	$stmt2 = $db->prepare("UPDATE template_info SET last_modified_by = :last_modified_by,
													last_modified_on = :last_modified_on,
													versions = :versions 
													WHERE info_id=:info_id;");
	$stmt2->bindValue(":last_modified_by", $author);
	$stmt2->bindValue(":last_modified_on", $date);
	$stmt2->bindValue(":versions", $versions);
	$stmt2->bindValue(":info_id", $info_id);
	$status2 = $stmt2->execute();


	$db->close();
	
	echo $info_id;	  
}

//delete a whole template (accessed at "index.php" by hovering over a row)
if(isset($_GET["id"])){
	$info_id = htmlspecialchars($_GET["id"]);

	$db = new SQLite3('../db/echo.sqlite', SQLITE3_OPEN_READWRITE);

	$db->querySingle("DELETE FROM template_data WHERE info_id = $info_id;");
	$db->querySingle("DELETE FROM templates_departments WHERE template_id = $info_id;");
	$db->querySingle("DELETE FROM template_info WHERE info_id = $info_id;");

	$db->close();

	//used to redirect back to the list of all templates
	header("Location: ../public/index.php");
	exit;
}
?>