<?php 
	// $template_name = "Template Name";
	// $created_by = "Your full name";
	// $created_at = date('Y-m-d H:i:s');
	// $last_modified = $created_at;

	// $departments = [];
	// $type = "";

	// $template = "";

	// $version_major = "1";
	// $version_minor = "0";
	// $version_revision = "0";
	// $version_buildnumber = "0";
	// $version_ip = "1.0.0.0";

	$db = new SQLite3('../db/echo.sqlite', SQLITE3_OPEN_READWRITE);

	

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$current_time = date('Y-m-d H:i:s');
		$version_number = $_POST["v1"] . "." . $_POST["v2"] . "." .$_POST["v3"] . "." .$_POST["v4"];
		$version_ip = ip2long($version_number);

		

		//TEMPLATE_INFO
		$stmt = $db->prepare("INSERT INTO template_info(template_name, 
														author, 
														created_at, 
														last_modified,
														last_version) 
							VALUES (?, ?, ?, ?, ?)");
		$stmt->bindParam(1, $_POST["template_name"]);
		$stmt->bindParam(2, $_POST["created_by"]);
		$stmt->bindParam(3, $current_time);
		$stmt->bindParam(4, $current_time);
		$stmt->bindParam(5, $version_ip);
		$stmt->execute();
		

		//TEMPLATES_DEPARTMENTS
		$last_row_id = $db->lastInsertRowID();
		$departmentsArray = $_POST["department"];

		foreach($departmentsArray as $department){
			$stmt = $db->prepare("INSERT INTO templates_departments(template_id, department_id) VALUES (?, ?)");
			$stmt->bindParam(1, $last_row_id);
			$stmt->bindParam(2, $department);
			$stmt->execute();
		}
		

		//TEMPLATE_DATA
		$stmt = $db->prepare("INSERT INTO template_data(template_info_id, 
														version_number, 
														created_by, 
														created_at) 
							VALUES (?, ?, ?, ?)");
		$stmt->bindParam(1, $last_row_id);
		$stmt->bindParam(2, $version_ip);
		$stmt->bindParam(2, $_POST["created_by"]);
		$stmt->bindParam(4, $current_time);
		$stmt->execute();
	  } 
?>

<form class="o-form" method="post" id="new_template_form" action="">

<div class="c-header">
	<div class="c-header__title">
		<h1>
		<input id="template_name" name="template_name" class="u-bg" type="text" placeholder="Template Name  &#xF044">
		</h1>		
	</div>

	
</div>

<div class="o-content">
	<div class="o-content__section">
		<div id="metadata" class="o-section c-metadata">
			<h2 class="o-section__title">Metadata</h2>

			<!-- CREATED BY  -->
			<div class="c-metadata__section"> 
				<input id="created_by" name="created_by" class="u-bg c-metadata__author" type="text" placeholder="&#xF007;  Author">
			</div>
			

			<!-- VERSION  -->
			<div class="c-metadata__section">
				<h3 class="c-metadata__subtitle">Version #</h3>
				<div class="c-metadata__version">
					<input id="v1" name="v1" class="u-bg u-border u-version" type="text" placeholder="0">
					<span class="u-version--dot">.</span>
					<input id="v2" name="v2" class="u-bg u-border u-version" type="text" placeholder="0">
					<span class="u-version--dot">.</span>
					<input id="v3" name="v3" class="u-bg u-border u-version" type="text" placeholder="0">
					<span class="u-version--dot">.</span>
					<input id="v4" name="v4" class="u-bg u-border u-version" type="text" placeholder="0">
				</div>
			</div>

			<!-- DEPARTMENT  -->
			<div class="c-metadata__section">
				<h3 class="c-metadata__subtitle">Departments</h3>
				<!-- <button class="btn badge badge-pill badge-secondary btn_dep">dico <i class="fal fa-plus"></i></button>
				<button class="btn badge badge-pill badge-secondary btn_dep">dios <i class="fal fa-plus"></i></button>
				<button class="btn badge badge-pill badge-secondary btn_dep">dist <i class="fal fa-plus"></i></button>
				<button class="btn badge badge-pill badge-secondary btn_dep">dipo <i class="fal fa-plus"></i></button>
				<button class="btn badge badge-pill badge-secondary btn_dep">dise <i class="fal fa-plus"></i></button> -->
				<div class="c-metadata__department">
					<?php
					$res_departments = $db->query('SELECT * FROM department');

					while ($row = $res_departments->fetchArray()) {
						echo '<label class="badge badge-pill badge-secondary o-pill" for="pill--' . $row['department_name'] . '">';
						echo '<input class="o-pill__checkbox" name="department[]" id="pill--' . $row['department_name'] . '" type="checkbox" value="' . $row['id'] . '">';
						echo $row['department_name'];
						echo '<i class="o-pill__symbol fal fa-plus"></i></label>';
					}
					?>
				</div>
			</div>

			<!-- TYPE  -->
			<div class="c-metadata__section">
				<h3 class="c-metadata__subtitle">Type</h3>
				<div  class="c-metadata__type">

					<label class="badge badge-pill badge-secondary o-pill" for="pill--post">
						<input class="o-pill__checkbox" name="type[]" id="pill--post" type="checkbox">
						post <i class="o-pill__symbol fal fa-plus"></i>
					</label>
					
					<label class="badge badge-pill badge-secondary o-pill" for="pill--get">
						<input class="o-pill__checkbox" name="type[]" id="pill--get" type="checkbox">
						get <i class="o-pill__symbol fal fa-plus"></i>
					</label> 
				</div>
			</div>

			<!-- DESCRIPTION -->
			<div class="c-metadata__section">
				<h3 class="c-metadata__subtitle">Description</h3>
				<div  class="c-metadata__description">
					<textarea class="u-bg" id="description" name="description" rows="4" placeholder="description"></textarea>
				</div>
			</div>

		</div>
	</div>

	<div class="o-content__section">
		<div id="template" class="o-section o-section__template c-template">
			<div class="o-template__buttons">
				<button id="save_form" class="btn btn-secondary">
				<i class="far fa-file-import"></i> Import</button>
				<button id="submit_form" form="new_template_form" class="btn btn-primary">
				<i class="far fa-save"></i> Save Template</button>
			</div>

			<h2 class="o-section__title">Create Template</h2>

			<div class="o-section__table">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Key</th>
							<th>Type</th>
							<th>Value</th>
						</tr>
					</thead>

					<tbody>
					<?php
						$json = '{
							"key1":"abc",
							"key2":1995,
							"key3": {
								"key4": "ghi",
								"key5": {
									"key11": "ghi",
									"key12": "jkl"
								},
								"key14": {
									"key15": "ghi",
									"key16": "jkl"
								}
							},
							"key13":"value",
							"key6": {
								"key7": "mno",
								"key8": "pqr"
							},
							"key9":"stu",
							"key10":["sometext1", 2, 3]
						}';
					
					
					function json_to_table($data, $indentation, $unique_id)
					{

						foreach ($data as $key => $value) {

							# Check if nested
							if (is_object($value) || is_array($value)) {

								$uid = uniqid();
								
								echo "<tr level='" . $indentation . "' class='toggle' uid='" . $uid . "'>";
								echo "<td><span style='margin-left: " . $indentation . "rem;'><i class='fal fa-chevron-down'></i>" . $key . "</span></td>";
								echo "<td>" . gettype($value) . "</td>";
								echo "<td></td>";
								echo "</tr>";

								
								$indentation += 2;
								
								json_to_table((array) $value, $indentation, $uid);

								$indentation -= 2;
							}
							else {
								# Add node, the keys will be updated automatically
								
								echo "<tr level='$indentation' uid='$unique_id'>";
								echo "<td style='max-width:5rem'><span style='margin-left: " . $indentation . "rem;'>" . $key . "</span></td>";
								echo "<td>" . gettype($value) . "</td>";
								echo "<td>$value</td>";
								echo "</tr>";
							}
						}
					}

					// print_r(json_decode($json));

					json_to_table(json_decode($json), 0, "null");					
					?>
					</tbody>
				</table>
			</div>
		</div>
		
		<script>
			// $('.toggle').click(function(){
			// 	$(this).find('i').text(function(_, value){return value =='-'?'+':'-'});
			// 	$(this).nextUntil('tr.toggle').slideToggle(100, function(){
			// 	});
			// });

			$('.toggle').click(function(){
				var id = $(this).attr('id');
				// console.log(id-1);
				id = "#" + id;
				id2 = "#" + id-2;
				$(this).find('i').toggleClass("fa-chevron-right");
				$(this).find('i').toggleClass("fa-chevron-down");
				$(this).nextUntil(id).slideToggle(1, function(){});
			});
		</script>

		<div id="preview" class="o-section o-section__preview c-preview">
			<h2 class="o-section__title">Request Preview</h2>
			<textarea name="preview" id="" cols="30" rows="10"></textarea>
			<div class="c-preview__data"></div>
		</div>
	</div>
	

	<!-- <script type="text/javascript" src="../js/submit_new_form.js"></script> -->
</div>
</form>
