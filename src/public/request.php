<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Echo Service</title>

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
		
		<?php
		$id = htmlspecialchars($_GET['id']);
		// echo $id;
		$db = new SQLite3('db/echo.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
		
		$statement = $db->prepare('SELECT "id", "template_name", "author" FROM "template_info" WHERE "id" = ?');
		$statement->bindValue(1, $id);
		$result = $statement->execute();

		$data = $result->fetchArray(SQLITE3_ASSOC);
		$result->finalize();
		?>
		
		<div class="c-header">
			<div class="c-header__title">
				<h1><?php echo $data['template_name'] ?></h1>
				<br><h5>By <?php echo $data['author'] ?></h5>
			</div>
			<div class="c-header__buttons">
				<div>
					<a href="template.php?id=0" style="margin-left: 2rem;" class="btn btn-primary"><i class="far fa-file-plus"></i> New Template</a>
				</div>
			</div>
		</div>

		<div id="filter" class="o-section c-filter">
			<h2 class="c-filter__title">Filter</h2>

			<div class="c-filter__input">
				<input style="font-family: Roboto, 'Font Awesome 5 Pro'" class="c-filter__input--template" type="text" id="search_by_name" placeholder="&#xF002; Template name">
				<input style="font-family: Roboto, 'Font Awesome 5 Pro'" class="c-filter__input--author" type="text" id="search_by_author" placeholder="&#xF007;  Author">
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
						<th>Tags</th>
						<th>Author</th>
						<!-- <th>Type</th> -->
						<!-- <th>Version</th> -->
						<th></th>
					</tr>
				</thead>

				<tbody>

				</tbody>
			</table>
						<?php // echo "test";  ?>
		</div>
	</div>

	<script type="text/javascript" src="../js/datatables.js"></script>
	<script type="text/javascript" src="../js/filter_toggle.js"></script>
	<script type="text/javascript" src="../js/pills.js"></script>
</body>
</html>