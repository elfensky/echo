<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Echo Service</title>
	<link rel="icon" type="image/png" sizes="32x32" href="../img/favicon-32x32.png">

	<!-- jquery-3 -->
	<script src="../dependencies/jquery/3.5.1.min.js"></script>

	<!-- datatables -->
	<link rel="stylesheet" type="text/css" href="../dependencies/datatables/datatables.min.css" />
	<script type="text/javascript" src="../dependencies/datatables/datatables.min.js"></script>

	<!-- boostrap -->
	<link rel="stylesheet" href="../dependencies/boostrap/4.5.0/css/bootstrap.min.css">
	<script src="../dependencies/boostrap/4.5.0/js/bootstrap.min.js"></script>

	<!-- fontawesome -->
	<link rel="stylesheet" href="../dependencies/fontawesome/5.13.0/css/all.css">
	<!--load all styles -->

	<!-- custom styles, need to change to sass but later -->
	<link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="../css/override.css">
</head>

<body>
	<div class="o-main">
		<div class="o-container">

			<?php
			if (isset($_GET['id']) || isset($_GET['v'])) {
				//check if an id (of the template) is supplied. 

				// if(is)
				if(isset($_GET['id'])){
					$id = htmlspecialchars($_GET['id']);

					//create db connection to SQLite database
					$db = new SQLite3('../db/echo.sqlite', SQLITE3_OPEN_READWRITE);

					//get the id of the most recent version from database using app id, and redirect to the ?v=$v page. 
					$stmt2 = "SELECT data_id FROM template_data AS td WHERE td.info_id = $id ORDER BY td.version_number DESC LIMIT 1";
					$template_data = $db->query($stmt2)->fetchArray();

					$v = $template_data['data_id'];

					header("Location: view.php?v=$v"); //redirect to the latest version of selected template
					die(); //stackoverflow said this was good practise
				}

				if(isset($_GET['v'])){
					$v = htmlspecialchars($_GET['v']); //get selected template

					//create db connection to SQLite database
					$db = new SQLite3('../db/echo.sqlite', SQLITE3_OPEN_READWRITE);
	
					//access template_data using $v
					$stmt2 = "SELECT * FROM template_data AS td WHERE td.data_id = $v";
					$template_data = $db->query($stmt2)->fetchArray();

					if(!empty($template_data)){ //if template_data exists and has been successfully fetched
						//set id
						$id = $template_data['info_id'];
						//access template_info
						$stmt1 = "SELECT t.info_id, t.template_name, GROUP_CONCAT(d.department_name) AS tags 
						FROM template_info AS t INNER JOIN templates_departments  AS td 
						ON t.info_id = td.template_id
						INNER JOIN department as d 
						ON d.id= td.department_id 
						WHERE t.info_id = $id";

						$template_info = $db->query($stmt1)->fetchArray();
					}					
				}

				//check if both aren't empty, then build table.
				if (!empty($template_info) && !empty($template_data)) {

					//--- DATA ---//
					$structure = json_decode($template_data["template"])->structure;
					$required = json_decode($template_data["template"])->required;
					$parents = [];

					//--- HEADING ---//
					echo '<a href="../public"><i class="far fa-long-arrow-left"></i> back to list</a>';

					//create heading html.
					$heading = '<div class="o-heading">';
					$heading .= '<h1 class="o-heading__item">' . $template_info["template_name"] . '</h1>';
					$heading .= '<h3 class="o-heading__item">' . $template_data["version_name"] . '</h3>';
					$heading .= '<p class="o-heading__item">' . $template_data["data_description"] . '</p>';
					$heading .= '<p class="o-heading__item">By<span id="author"> ' . $template_data["last_modified_by"] . '<span></p><div>';

					echo $heading;

					$tags_array = explode(',', $template_info['tags']);
					foreach ($tags_array as $tag) {
						echo "<span class='badge badge-pill badge-primary o-heading__pill o-heading__pill--$tag'>$tag</span>";
					}

					$heading = '<div style="display:none;"><span id="version_number">' . $template_data['version_number'] . '</span>';
					$heading .= '<span id="info_id">' . $template_data['info_id'] . '</span>';
					$heading .= '<span id="template_data_id">' . $template_data['data_id'] . '</span>';
					$heading .= '</div></div></div>';
					echo $heading;

					

					//--- TABLE ---//
					//set whether template should be accessed using GET or POST
					//make function from this.
					if(empty($required)){
						$access = "Access using GET";
					} else { $access = "Access using POST"; }

					//table and preview wrapper
					echo "<div class='o-wrapper'>";
					//create table html
					$table = '<div class="o-section o-table"><div class="o-section__buttons">'; //table wrappers
					$table .= '<div id="message"></div>';
					$table .= get_all_versions($db, $id, $v);
					$table .= '<a href="edit.php?v=' . $v . '" class="btn btn-primary"><i class="far fa-edit"></i> Edit Current</a>';
					$table .= '<button id="delete_view" class="btn btn-danger"><i class="far fa-trash"></i></button>';
					$table .= '</div><h2 class="o-section__title">View Template</h2>'; //title
					$table .= '<div class="o-section__access" >' . $access . ' | <a href="echo.php?v=' . $v . '">' . $_SERVER['HTTP_HOST'] . "/public/echo.php?v=" . $v . '</a></div>';
					$table .= '<table class="table table-hover">'; //actual table itself
					$table .= '<thead><tr>';
					$table .= '<th><i class="fas fa-star-of-life" style="font-size:0.9rem;"></i></th>';
					$table .= '<th>Key</th>';
					$table .= '<th>Type</th>';
					$table .= '<th>Value</th>';
					// $table .= '<th></th>';
					$table .= '</tr></thead>';
					$table .= '<tbody>';
					echo $table;

					
					//fill in table
					json_to_table($structure, $required, 0, $parents);

					//end table thml
					$table = "</tbody></table></div>";
					echo $table;

					//--- PREVIEW ---//
					
					//generate preview with javascript
					//end preview html

					
					//create preview html
					$preview = '<div class="o-section o-preview">
									<h2 class="o-section__title">Request Preview</h2>
									<pre id="preview" class="o-section__content"></pre>
								</div>';
					echo $preview;

					echo "</div>"; //end of wrapper

				} else {
					//if it doesn't exist, show error
					// echo "AGWGRG";
					echo "This template doesn't exist";
				}

			} else {
				//if no id is specified, show another error. 
				echo "you need to specify the id of the template you want to view";
			}

			?>

		</div>

		<footer class="o-footer">
			© 2020 Andrei lavrenov
		</footer>
	</div>

	<!-- scripts -->
	<!-- <script type="text/javascript" src="../js/pills.js"></script> -->
	<script type="text/javascript" src="../js/collapse.js"></script> <!-- Show/hide nested rows -->
	<script type="text/javascript" src="../js/json.js"></script> <!-- Generates the json preview -->
	<script type="text/javascript" src="../js/delete.js"></script>

</body>

</html>


<?php
function json_to_table($template_structure, &$required, $indentation, $parents)
{
	foreach ($template_structure as $key => $value) {
		array_push($parents, $key);

		$class = "";
		$checkbox = "";

		if(is_key_required($key, $required)){
			$class = "alert-secondary";
			$checkbox = "<i class='far fa-check'></i>";
		}

		# Check if nested
		if (is_object($value) || is_array($value)) {

			echo "<tr level='" . $indentation . "' class='toggle $class' keys='" . string_with_uuid($parents) . "' state='visible'>";
			echo "<td>$checkbox</td>";
			echo "<td class='o-table__row__key'><span style='margin-left: " . $indentation * 2 . "rem;'><i class='u-collapse fal fa-chevron-down'></i>" . $key . "</span></td>";
			// echo "<td>" . gettype($value) . "</td>";
			echo "<td><span>" . get_json_value_type($value) . "</span></td>";
			echo "<td><div></div></td>"; //empty value
			// echo "<td><input type='checkbox'></td>";
			// echo "<td><i class='fal fa-plus'></i><i class='fal fa-trash'></i></td>";
			echo "</tr>";


			$indentation += 1;

			json_to_table($value, $required, $indentation, $parents);

			array_pop($parents);
			$indentation -= 1;
		} else {

			// if(var_in_array chang key to parentkey+key)

			echo "<tr class='$class' level='" . $indentation . "' keys='" . string_with_uuid($parents) . "'>";

			//required
			echo "<td>$checkbox</td>";

			echo "<td class='o-table__row__key'><span style='margin-left: " . $indentation * 2 . "rem;'>" . $key . "</span></td>";

			//type
			echo "<td><span>" . get_json_value_type($value) . "</span></td>";

			//value
			echo "<td><div type='" . get_json_value_type($value) . "' id='$key' name='$key'>$value</div></td>";

			//actions
			// echo "<td><i class='fal fa-plus'></i><i class='fal fa-trash'></i></td>";
			echo "</tr>";

			array_pop($parents);
		}
	}
}

function string_with_uuid($array)
{
	return implode(",", $array);
}

function select_current_type($type)
{
	$search = '"' . $type . '"';
	$replace = '"' . $type . '" selected="selected"';

	$html  = '<select class="custom-select u-select u-nofa">';
	$html .= '<option value="string">string</option>';
	$html .= '<option value="number">number</option>';
	$html .= '<option value="object">object</option>';
	$html .= '<option value="array">array</option>';
	$html .= '<option value="boolean">boolean</option>';
	$html .= '<option value="null">null</option>';
	$html .= '</select>';

	return str_replace($search, $replace, $html);
	return $html;
}

function get_json_value_type($item)
{
	$php_type = gettype($item);

	if ($php_type === "integer" || $php_type === "double") {
		return "number";
	} else if ($php_type === NULL || $php_type === null || $php_type === "NULL") {
		return "null";
	} else {
		return $php_type;
	}
}

function get_all_versions($db, $id, $v)
{
	$stmt = "SELECT version_name, data_id FROM template_data AS td WHERE td.info_id = $id ORDER BY td.version_number DESC";
	$versions = $db->query($stmt);

	// $res = $db->query('SELECT id, template_name, author FROM template_info');
	// print_r($res->fetchArray());
	$select = "<form id='version-form' name='version-form' method='GET'><select id='v' name='v' onchange='this.form.submit()' class='custom-select c-version u-nofa'>";
	while ($row = $versions->fetchArray()) {

		if($row['data_id'] == $v){
			$select .= "<option value='" . $row['data_id'] . "' selected='selected'>" . $row['version_name'] . "</option>";
		}
		else{
			$select .= "<option value='" . $row['data_id'] . "'>" . $row['version_name'] . "</option>";
		}
	}
	$select .= "</select></form>";
	return $select;
}

function is_key_required($key, &$required){
	if(!empty($required)){
		if(in_array($key, $required)){
			array_shift($required);
			return TRUE;
		} else { return FALSE;}
	} else { return FALSE;}
}
?>