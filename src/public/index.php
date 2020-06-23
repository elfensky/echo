<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Echo Service</title>
	<!-- <link rel="icon" type="image/png" sizes="16x16" href="..img/favicon.ico"> -->
	<link rel="icon" type="image/png" sizes="32x32" href="../img/favicon-32x32.png">

	<!-- jquery-3 -->
	<script src="../dependencies/jquery/3.5.1.min.js"></script>

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
	<!-- <link rel="stylesheet" href="../css/override.css"> -->
</head>
<body>
	<div class="o-main">
		<div class="o-container">

			<div class="o-heading o-heading--index">
				<!-- <h1 class="o-heading__title">Browse Templates</h1> -->

				<div id="filter" class="o-section c-filter">
					<!-- <h2 class="c-filter__title">Filter</h2> -->

					<div class="c-filter__input">
						<input class="c-filter__input--template" type="text" id="search_by_name" placeholder="&#xF002; Template name">
						<input class="c-filter__input--author" type="text" id="search_by_author" placeholder="&#xF007;  Author">
						<!-- <input class="c-filter__input--department" type="text" id="search_by_department" placeholder="Department"> -->
						<!-- <div class="dataTables_length" id="templates_length"><label>Show <select name="templates_length" aria-controls="templates" class=""><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> entries</label></div> -->
					</div>
					
					<!-- <div class="c-filter__badges">
						<div class="c-filter__badgesection">
							<button id="btn_all" class="btn badge badge-pill badge-primary">All <i class="fal fa-minus"></i></button>
							<button class="btn badge badge-pill badge-secondary btn_dep">dico <i class="fal fa-plus"></i></button>
							<button class="btn badge badge-pill badge-secondary btn_dep">dios <i class="fal fa-plus"></i></button>
							<button class="btn badge badge-pill badge-secondary btn_dep">dist <i class="fal fa-plus"></i></button>
							<button class="btn badge badge-pill badge-secondary btn_dep">dipo <i class="fal fa-plus"></i></button>
							<button class="btn badge badge-pill badge-secondary btn_dep">dise <i class="fal fa-plus"></i></button>
						</div>
					</div> -->
				</div>

			</div>

			<div class="o-wrapper">

				<div class="o-section o-table u-100">
					<div class="o-section__buttons">

						<div style="display:flex;flex-direction:row;align-items:center;">
							
							<select class="custom-select" id="templates_length" name="templates_length" aria-controls="templates">
								<option value="10">10 Rows</option>
								<option value="25">25 Rows</option>
								<option value="50" selected="selected">50 Rows</option>
								<option value="-1">All Rows</option>
							</select>
							<!-- Rows -->
						</div> 

						<a href="new.php" style="margin-left: 2rem;" class="btn btn-primary"><i class="far fa-file-plus"></i> New Template</a>
					</div>

					<table class="table table-hover" id="templates" class="display">
						<thead>
							<tr>
								<th>#</th>
								<th>Template</th>
								<th>Departments</th>
								<th>Last Modified By</th>
								<th>Versions</th>
								<!-- <th>Type</th> -->
								<!-- <th>Version</th> -->
								<th></th>
							</tr>
						</thead>

						<tbody>

							<?php 
								$db = new SQLite3('../db/echo.sqlite', SQLITE3_OPEN_READWRITE);
								
								$res = $db->query('SELECT t.info_id, t.template_name, t.last_modified_by, t.versions, GROUP_CONCAT(d.department_name) AS tags 
													FROM template_info AS t INNER JOIN templates_departments  AS td 
													ON t.info_id = td.template_id 
													INNER JOIN department as d 
													ON d.id= td.department_id 
													GROUP BY t.info_id');

								while ($row = $res->fetchArray()) {
									$tags_array = explode(',', $row['tags']);

									echo "<tr class='c-table__row'>
											<td></td>
											<td><a href='view.php?id=" . $row['info_id'] . "'>{$row['template_name']}</a></td>
											<td>";
									
									foreach ($tags_array as $tag) {
										echo "<span class='badge badge-pill badge-primary pill--$tag'>$tag</span>";
									}
												
									echo   "</td>
											<td>{$row['last_modified_by']}</td>
											<td>" . $row['versions'] . "</td>
											<td>
												<div class='c-table__actions'>
													<a class='c-table__actions--delete' href='../php/delete.php?id=" . $row['info_id'] . "'><i class='fas fa-trash'></i></a>
												</div>
											</td>
										</tr>";
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<footer class="o-footer">
			© 2020 Andrei lavrenov
		</footer>
	</div>

	<script type="text/javascript" src="../js/datatables.js"></script>
</body>
</html>