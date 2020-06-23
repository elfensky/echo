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
					//--- DATA ---//
					$db = new SQLite3('../db/echo.sqlite', SQLITE3_OPEN_READWRITE);

					// $structure = json_decode($template_data["template"])->structure;
					// $required = json_decode($template_data["template"])->required;
					// $parents = [];

					//--- HEADING ---//
					echo '<a href="../public/"><i class="far fa-long-arrow-left"></i> back to list</a>';

					//create heading html.
					$heading = '<div class="o-heading">';
					$heading .= '<h1 class="o-heading__input"><input class="c-edit__heading__input" id="template_name" type="text" placeholder="Template Name &#xf044" name="template_name"></h1>';
					$heading .= '<h3 class="o-heading__input"><input class="c-edit__heading__input"  id="version_name" type="text" placeholder="Version Name &#xf044" name="version_name"></h3>';
					$heading .= '<p class="o-heading__input"><input class="c-edit__heading__input" id="description" type="text" placeholder="Description &#xf044" name="description"></p>';
					$heading .= '<p class="o-heading__input">Modified by <input class="c-edit__heading__input" id="author" type="text" placeholder="Full Name &#xf044" name="author"></p><div>';

					echo $heading;

					// $tags_array = explode(',', $template_info['tags']);

					$res_departments = $db->query('SELECT * FROM department');

					while ($row = $res_departments->fetchArray()) {
						if($row['department_name'] != null){
							echo '<label class="u-checkbox u-pill" for="pill--' . $row['department_name'] . '">';
							echo '<input name="department[]" id="pill--' . $row['department_name'] . '" type="checkbox" value="' . $row['id'] . '">';
							echo '<i class="fal fa-plus unchecked badge badge-pill badge-secondary"><span>' . $row['department_name'] . '</span></i>';
							echo '<i class="fal fa-minus checked  badge badge-pill badge-primary"><span>' . $row['department_name'] . '</span></i></label>';
						}	
					}

					$heading = '</div></div>';
					echo $heading;

					

					//--- TABLE ---//

					//table and preview wrapper
					echo "<div class='o-wrapper'>";
					//create table html
					$table = '<div class="o-section o-table"><div class="o-section__buttons">'; //table wrappers
					$table .= '<div id="message"></div>';

					//upload form
					$table .= '<div style="display:flex;align-items:center;"><form id="upload" enctype="multipart/form-data" name="upload" method="POST" ><input id="file" type="file" name="file"></form></div>';
					// $table .= '<button id="upload" name="upload" type="upload" class="btn btn-primary" value="upload"><i class="far fa-upload"></i> Upload</button>';
					
					//other buttons
					$table .= '<button id="create" class="btn btn-primary"><i class="far fa-save"></i> Create</button>';
					$table .= '<button id="clear" class="btn btn-warning"><i class="far fa-eraser"></i></button>';
					$table .= '</div><h2 class="o-section__title">Edit Template</h2>'; //title
					$table .= '<table class="table table-hover c-table">'; //actual table itself
					$table .= '<thead><tr>';
					$table .= '<th><i class="fas fa-star-of-life" style="font-size:0.9rem;"></i></th>';
					$table .= '<th>Key</th>';
					$table .= '<th>Type</th>';
					$table .= '<th>Value</th>';
					// $table .= '<th><i class="u-addrow fal fa-plus"></i></i></th>';
					$table .= '</tr></thead>';
					$table .= '<tbody>';
					echo $table;

					//GENERATE TABLE USING STRUCTURE GAINED ON SELF_POST
					if(isset($_FILES["file"])){
						if($_FILES["file"]["type"] == "application/json"){
							$structure = json_decode(file_get_contents($_FILES['file']['tmp_name']));

							$required = [];
							$parents = [];
							json_to_table($structure, $required, 0, $parents);
						}
					} else {
						echo "please select a json file.";
					}

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

			?>

		</div>

		<footer class="o-footer">
			© 2020 Andrei lavrenov
		</footer>
	</div>

	<!-- scripts -->
	<script type="text/javascript" src="../js/json.js"></script> <!-- Generates the json preview -->
	
	<script type="text/javascript" src="../js/create.js"></script> <!-- uses a post request using ajax and  -->
	<script type="text/javascript" src="../js/clear.js"></script> <!-- Generates the json preview -->

	<script type="text/javascript" src="../js/collapse.js"></script> <!-- Show/hide nested rows -->
	
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
			$checkbox = '<label class="u-checkbox">
						<input type="checkbox" checked>
						<i class="far fa-square unchecked"></i>
						<i class="far fa-check-square checked"></i>
						</label>';
		}else {
				$class = "";
				$checkbox = '<label class="u-checkbox">
							<input type="checkbox">
							<i class="far fa-square unchecked"></i>
							<i class="far fa-check-square checked"></i>
							</label>';
			}

		# Check if nested
		if (is_object($value) || is_array($value)) {

			echo "<tr level='" . $indentation . "' class='toggle $class' keys='" . string_with_uuid($parents) . "' state='visible'>";
			echo "<td>$checkbox</td>";

			echo "<td class='o-table__row__key'><span class='c-table__span' style='margin-left: " . $indentation * 2 . "rem;'><i class='u-collapse fal fa-chevron-down'></i><input class='form-control input-key' type='text' value='" . $key . "'></span></td>";

			// echo "<td><span>" . get_json_value_type($value) . "</span></td>";
			echo "<td>" . select_current_type(get_json_value_type($value)) . "</td>";

			// echo "<td><div></div></td>"; //empty value
			echo "<td><input class='form-control input-value' id='$key' name='$key' value='' disabled></td>";

			// This is the section that has the "add row" and "remove row" controls. 
			// echo "<td class='u-controls'><i class='u-addrow fal fa-plus'></i><i class='u-removerow fal fa-times'></i></td>";
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

			echo "<td class='o-table__row__key'><span class='c-table__span' style='margin-left: " . $indentation * 2 . "rem;'><input class='form-control input-key' type='text' value='" . $key . "'></span></td>";

			//type
			// echo "<td><span>" . get_json_value_type($value) . "</span></td>";
			echo "<td>" . select_current_type(get_json_value_type($value)) . "</td>";

			//value
			// echo "<td><div type='" . get_json_value_type($value) . "' id='$key' name='$key'>$value</div></td>";
			echo "<td><input class='form-control input-value' type='" . get_json_value_type($value) . "' id='$key' name='$key' value='$value'></td>";

			//actions
			// This is the section that has the "add row" and "remove row" controls.
			// echo "<td class='u-controls'><i class='u-addrow fal fa-plus'></i><i class='u-removerow fal fa-times'></i></td>";
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