<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Echo Service</title>
	<!-- <link rel="icon" type="image/png" sizes="16x16" href="..img/favicon.ico"> -->
	<link rel="icon" type="image/png" sizes="32x32" href="../img/favicon-32x32.png">

	<!-- jquery-3 -->
	<script src="../js/jquery-3.5.1.min.js"></script>

	<!-- datatables -->
	<link rel="stylesheet" type="text/css" href="../dependencies/datatables/datatables.min.css"/>
	<script type="text/javascript" src="../dependencies/datatables/datatables.min.js"></script>

	<!-- boostrap -->
	<link rel="stylesheet" href="../dependencies/boostrap/4.5.0/css/bootstrap.min.css">
	<script src="../dependencies/boostrap/4.5.0/js/bootstrap.min.js"></script>

	<!-- fontawesome -->
	<link rel="stylesheet" href="../dependencies/fontawesome/5.13.0/css/all.css"> <!--load all styles -->

	<!-- custom styles, need to change to sass but later -->
	<link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="../css/override.css">
</head>
<body>
	<div class="container-fluid o-main">
		
		<div class="c-header">
			<div class="c-header__title">
				<h1>Browse Templates</h1>
			</div>
			<div class="c-header__buttons">
				<div>
					<button id="btn_filter" class="btn btn-primary"><i class="far fa-filter"></i></button>
					<a href="template.php?id=0" style="margin-left: 2rem;" class="btn btn-primary"><i class="far fa-file-plus"></i> New Template</a>
				</div>
			</div>
		</div>

		<div id="filter" class="o-section c-filter">
			<h2 class="c-filter__title">Filter</h2>

			<div class="c-filter__input">
				<input style="font-family: Roboto, 'Font Awesome 5 Pro'" class="c-filter__input--template" type="text" id="search_by_name" placeholder="&#xF002; Template name">
				<input style="font-family: Roboto, 'Font Awesome 5 Pro'" class="c-filter__input--author" type="text" id="search_by_author" placeholder="&#xF007;  Author">
				<input style="font-family: Roboto, 'Font Awesome 5 Pro'" class="c-filter__input--department" type="text" id="search_by_department" placeholder="Department">
			</div>
			
			<div class="c-filter__badges">
				<div class="c-filter__badgesection">
					<h3>Departments</h3>
					<button id="btn_all" class="btn badge badge-pill badge-primary">All <i class="fal fa-minus"></i></button>
					<button class="btn badge badge-pill badge-secondary btn_dep">dico <i class="fal fa-plus"></i></button>
					<button class="btn badge badge-pill badge-secondary btn_dep">dios <i class="fal fa-plus"></i></button>
					<button class="btn badge badge-pill badge-secondary btn_dep">dist <i class="fal fa-plus"></i></button>
					<button class="btn badge badge-pill badge-secondary btn_dep">dipo <i class="fal fa-plus"></i></button>
					<button class="btn badge badge-pill badge-secondary btn_dep">dise <i class="fal fa-plus"></i></button>
				</div>

				<div class="c-filter__badgesection">
					<h3>Type</h3>
					<button class="btn badge badge-pill badge-secondary btn_type">post <i class="fal fa-plus"></i></button>
					<button class="btn badge badge-pill badge-secondary btn_type">get <i class="fal fa-plus"></i></button>
				</div>
			</div>
		</div>

		<div class="table-responsive o-section c-table ">

			<table class="table table-hover" id="templates" class="display">
				<thead>
					<tr>
						<th>#</th>
						<th>Template</th>
						<th>Departments</th>
						<th>Author</th>
						<th>Version</th>
						<!-- <th>Type</th> -->
						<!-- <th>Version</th> -->
						<th></th>
					</tr>
				</thead>

				<tbody>

					<?php 
						$db = new SQLite3('../db/echo.sqlite', SQLITE3_OPEN_READWRITE);
						
						// $res = $db->query('SELECT id, template_name, author, GROUP_CONCAT(department.department_name) AS tags 
						// 							FROM template_info INNER JOIN templates_departments 
						// 							ON template_info.id = templates_departments.template_id 
						// 							INNER JOIN department 
						// 							ON department.id = templates_departments.department_id 
						// 							GROUP BY template_info.id');

						$res = $db->query('SELECT t.id, t.template_name, t.author, t.last_version, GROUP_CONCAT(d.department_name) AS tags 
											FROM template_info AS t INNER JOIN templates_departments  AS td 
											ON t.id = td.template_id 
											INNER JOIN department as d 
											ON d.id= td.department_id 
											GROUP BY t.id');
						
						// $res = $db->query('SELECT id, template_name, author FROM template_info');
						// print_r($res->fetchArray());

						while ($row = $res->fetchArray()) {
							$tags_array = explode(',', $row['tags']);

							echo "<tr class='c-table__row'>
									<td></td>
									<td><a href='template.php?id=" . $row['id'] . "'>{$row['template_name']}</a></td>
									<td>";
							
							foreach ($tags_array as $tag) {
								echo "<span class='badge badge-pill badge-primary pill--$tag'>$tag</span>";
							}
										
							echo   "</td>
									<td>{$row['author']}</td>
									<td>" . long2ip($row['last_version']) . "</td>
									<td>
										<div class='c-table__actions'>
										<a href='template.php?id=" . $row['id'] . "'><i class='fas fa-edit'></i></a>
										<a style='color:red;' href='delete.php?id=" . $row['id'] . "'><i class='fas fa-trash'></i></a>
										</div>
									</td>
									</tr>";
						}
					?>
				</tbody>
			</table>
						<?php // echo "test";  ?>
		</div>
	</div>

	<script type="text/javascript" src="../js/datatables.js"></script>
	<script type="text/javascript" src="../js/filter_toggle.js"></script>
	<script type="text/javascript" src="../js/pills_filter.js"></script>
	<script type="text/javascript" src="../js/clear_inputs_on_reload.js"></script>
	<script type="text/javascript" src="../js/delete__template.js"></script>
</body>
</html>