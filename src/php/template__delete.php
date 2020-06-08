<?php

	if($_SERVER["REQUEST_METHOD"] == "GET"){
		$id = htmlspecialchars($_GET["id"]);


		$db = new SQLite3('../db/echo.sqlite', SQLITE3_OPEN_READWRITE);;

		$stmt = "SELECT template_data.id FROM template_data WHERE template_info_id = $id";

		$result = $db->query($stmt);

		// echo $result->id;
	}

?>