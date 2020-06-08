<?php 
	$db = new SQLite3('../db/echo.sqlite', SQLITE3_OPEN_READWRITE);

	
	// $template_id = "3";
	$template_id = $_GET["tid"];
	// echo $template_id
	$request_id = "1";

	$stmt = "SELECT * FROM template_data AS td WHERE td.id = $template_id";
	$result = $db->query($stmt)->fetchArray();
	// print_r(json_decode($result[2])->data);


	$column_id = 0;
	$column_template_info_id = 1;
	$column_template = 2;
	// $res->fetchArray()
?>


<div class="c-header">
	<div class="c-header__title" style="display:flex; flex-direction:column; align-items: start; justify-content: center;">
		<h1><input id="request_name" name="request_name" class="u-bg" type="text" placeholder="Request Name  &#xF044"></h1>
		<h3><input id="request_author" name="request_author" class="u-bg" type="text" placeholder="Author  &#xF044"></h3>
		<br><p><input id="request_author" name="request_author" class="u-bg" type="text" placeholder="Some short description of the request &#xF044"></p>
	</div>
</div>

<div class="o-content">

	<div class="o-content__section">
		<div id="template" class="o-section o-section__template c-template">
			<div class="o-template__buttons">
				<!-- <button id="save_form" class="btn btn-secondary">
				<i class="far fa-file-import"></i> Import</button> -->
				<button id="submit_form" form="new_template_form" class="btn btn-primary">
				<i class="far fa-save"></i> Save Request</button>
			</div>

			<h2 class="o-section__title">Edit Request</h2>

			<div class="o-section__table">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Key</th>
							<th>Type</th>
							<th>Value</th>
							<th></th>
						</tr>
					</thead>

					<tbody>
					<?php
					
					function json_to_table($template_structure, $template_data, $indentation, $uuid)
					{
						

						foreach ($template_structure as $key => $value) {
							array_push($uuid, $key);

							# Check if nested
							if (is_object($value) || is_array($value)) {

								
								
								echo "<tr level='" . $indentation . "' class='toggle' keys='" . string_with_uuid($uuid) . "' state='shown'>";
								echo "<td><span style='margin-left: " . $indentation*2 . "rem;'><i class='fal fa-chevron-down'></i>" . $key . "</span></td>";
								echo "<td>" . gettype($value) . "</td>";
								echo "<td></td>";
								echo "<td><i class='fal fa-plus'></i><i class='fal fa-trash'></i></td>";
								echo "</tr>";

								
								$indentation += 1;
								

								json_to_table((array) $value, $template_data, $indentation, $uuid);

								array_pop($uuid);
								$indentation -= 1;
							}
							else {
								# Add node, the keys will be updated automatically
								// array_push($uuid, $key);

								echo "<tr class='plain' level='" . $indentation . "' keys='" . string_with_uuid($uuid) . "'>";
								echo "<td style='max-width:5rem'><span style='margin-left: " . $indentation*2 . "rem;'>" . $key . "</span></td>";
								echo "<td>" . gettype($value) . "</div></td>";
								echo "<td><input type='text' id='$key' name='$key' value='$value'></td>";
								echo "<td><i class='fal fa-plus'></i><i class='fal fa-trash'></i></td>";
								echo "</tr>";

								array_pop($uuid);
							}
						}
					}

					function string_with_uuid($array){
						return implode(",",$array);
						// $string = "";

						// foreach($array as $key){
						// 	if($key == array_key_last($array)){
						// 		$string .= (string) $key;
						// 	}
						// 	else{
						// 		$string .= (string) $key . ",";
						// 	}
							
						// }

						// return $string;
					}

					$data = json_decode($result[2])->data;
					$structure = json_decode($result[2])->structure;

					$uuid = [];
					// array_push($uuid, uniqid());
					// array_push($uuid, uniqid());
					// array_push($uuid, uniqid());
					// print_r($uuid);
					// echo "<br><br><br>";
					// print_r(string_with_uuid($uuid));

					json_to_table($structure, $data, 0, $uuid);					
					?>
					</tbody>
				</table>
			</div>
		</div>
		

		<div class="o-section o-section__preview c-preview">
			<h2 class="o-section__title">Request Preview</h2>
			<!-- <textarea name="preview" id="" cols="30" rows="10"></textarea> -->
			<pre id="preview" class="c-preview__data">
				<!-- test-data -->



			</pre>
		</div>
	</div>
	<script type="text/javascript" src="../js/json.js"></script>
	<script>
		// generate_and_display_preview();

		// $("input").on("change keyup paste click", function () {
		// 	generate_and_display_preview();
		// });

		// function generate_and_display_preview() {
		// 	var main_array = new Map(); //CREATE EMPTY MAP (will contain structure)
		// 	var tr = $("tr").slice(1); //ALL ROWS (FLAT)
		// 	var len = tr.length; //ARRAY SIZE


		// 	for (var i=0; i<len; i++) { // LOOP OVER ALL ROWS (i = CURRENT ROW)

		// 		var key = tr[i].childNodes[0].innerText; //key van current row (FLAT)
		// 		var type = tr[i].childNodes[1].innerText; //type van current row (FLAT)
		// 		var uuid = $(tr[i]).attr("uuid").split(" "); //array of uuids
		// 		var level = uuid.length-1; //level van current row (FLAT)
				
		// 		// console.log(uuid)


		// 		if(type == "object" || type == "array"){ 
		// 			//ALS ROW = object OF array do shit here
		// 			//FIND ALL NESTED ITEMS WITHIN CURRENT OBJECT OR ARRAY
		// 			// create_collection(i, key);

		// 			// add_key_value(main_array, key, create_collection_map(i, key, level));
		// 			if(type == "object" && level == 0){
		// 				let value = new Map();
		// 				add_key_value_pair(main_array, key, value);


		// 			}


		// 			// if(type == "array" && level == 0){
		// 			// 	let value = new Array();
		// 			// 	add_key_value_pair(main_array, key, value);
		// 			// }

		// 		} else if(level == "0"){
		// 			//ADDS THE KEYS WITHOUT NESTED ITEMS TO THE MAP
		// 			let value = tr[i].childNodes[2].childNodes[0].value;
		// 			add_key_value_pair(main_array, key, value);
		// 		}
				
		// 		function create_collection_map(row_number, key, level) {
		// 			// LOOPS OVER WHOLE (FLAT) LIST, CALLS ITSELF IT IF IT
		// 			// ENCOUNTERS ANOTHER OBJECT OR ARRAY
		// 			// OTHERWISE IT SIMPLY ADDS KEY-VALUE PAIR TO MAP

		// 			add_key_value(main_array, key, new Map()); //CREATE EMPTY ITEM
		// 			var temp_map = new Map();

		// 			for(o=row_number+1; o<len; o++){
		// 				if(level == 0){break;}

		// 				var next_item_row = row_number+1;
		// 				var next_item_type = tr[next_item_row].childNodes[1].innerText;
		// 				var next_item_key = tr[next_item_row].childNodes[0].innerText; 	
		// 				// console.log(next_item_type);

		// 				if(next_item_type == "object"){
		// 					temp_map.set(next_item_key, create_collection_map(next_item_row+1, next_item_key));

		// 				} else {
		// 					var temp_key = tr[next_item_row].childNodes[0].innerText;
		// 					var temp_value = tr[next_item_row].childNodes[2].childNodes[0].value;
		// 					var temp_inner_map = new Map();
		// 					temp_inner_map.set(temp_key, temp_value);

		// 					add_key_value(temp_map, temp_key, temp_value);
		// 				}						
		// 			}
					
		// 			return temp_map;					
		// 		}

		// 		function add_key_value_pair(map, key, value){			
		// 			map.set(key, value);
		// 		}				
		// 	}


		// 	//display json in <pre>
		// 	var json = JSON.stringify(map_to_object(main_array), undefined, 4);
		// 	output(syntax_highlight(json))
		// }
		
		// // output(syntax_highlight(generate_json()))

		// function output(json) {
		// 	document.getElementById("preview").innerHTML = json;
		// }

		// function map_to_object(map) {
		// 	const out = Object.create(null)
		// 	map.forEach((value, key) => {
		// 	if (value instanceof Map) {
		// 		out[key] = map_to_object(value)
		// 	}
		// 	else {
		// 		out[key] = value
		// 	}
		// 	})
		// 	return out
		// }

		// function syntax_highlight(json) {
		// 	json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
		// 	return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
		// 		var cls = 'number';
		// 		if (/^"/.test(match)) {
		// 			if (/:$/.test(match)) {
		// 				cls = 'key';
		// 			} else {
		// 				cls = 'string';
		// 			}
		// 		} else if (/true|false/.test(match)) {
		// 			cls = 'boolean';
		// 		} else if (/null/.test(match)) {
		// 			cls = 'null';
		// 		}
		// 		return '<span class="' + cls + '">' + match + '</span>';
		// 	});
		// }

	</script>
	<!-- <script type="text/javascript" src="../js/submit_new_form.js"></script> -->
</div>