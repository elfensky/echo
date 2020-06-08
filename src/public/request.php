<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Echo Service</title>
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

		<?php
		$rid = htmlspecialchars($_GET['rid']);

		if($rid == 0){
			echo '<a href="/echo/src/public"><i class="far fa-chevron-left"></i> back</a>';
			require "../php/request__new.php";
		}

		else{
			require "../php/request__edit.php";
		}
		?>
	</div>

	<!-- scripts -->
	<script type="text/javascript" src="../js/datatables.js"></script>
	<script type="text/javascript" src="../js/filter_toggle.js"></script>
	<script type="text/javascript" src="../js/pills.js"></script>
	<script type="text/javascript" src="../js/table_show-hide-nested.js"></script>
	<!-- <script type="text/javascript" src="../js/clear_inputs_on_reload.js"></script> -->
</body>
</html>