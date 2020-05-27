<?php 
	$template_name = "Template Name";
	$created_by = "Your full name";
	$created_at = date('Y-m-d H:i:s');
	$last_modified = $created_at;

	$departments = [];
	$type = "";

	$template = "";

	$version_major = "0";
	$version_minor = "0";
	$version_revision = "0";
	$version_buildnumber = "1";
	$version_ip = "0.0.0.1";
?>

<div class="c-header">
	<div class="c-header__title">
		<h1>
		<input id="template_name" class="u-bg" type="text" placeholder="Template Name  &#xF044">
		</h1>		
	</div>

	<div class="c-header__buttons">
		<div>
			<!-- <a href="template.php?id=<?php echo $id ?>" style="margin-left: 2rem;" class="btn btn-primary"><i class="far fa-edit"></i> Duplicate Template</a> -->
			<a href="template.php?id=<?php echo $id ?>" style="margin-left: 2rem;" class="btn btn-primary"><i class="far fa-save"></i> Save Template</a>
		</div>
	</div>
</div>

<div class="content">
	<div id="metadata" class="o-section c-metadata">
		<h2 class="o-section__title">Metadata</h2>

		<!-- CREATED BY  -->
		<div class="c-metadata__section"> 
			<input id="created_by" class="u-bg c-metadata__author" type="text" placeholder="&#xF007;  Author">
		</div>
		

		<!-- VERSION  -->
		<div class="c-metadata__section">
			<h3 class="c-metadata__subtitle">Version #</h3>
			<div class="c-metadata__version">
				<input id="v1" class="u-bg u-border u-version" type="text" placeholder="0-255">
				<input id="v2" class="u-bg u-border u-version" type="text" placeholder="0-255">
				<input id="v3" class="u-bg u-border u-version" type="text" placeholder="0-255">
				<input id="v4" class="u-bg u-border u-version" type="text" placeholder="0-255">
			</div>
		</div>

		<!-- DEPARTMENT  -->
		<div class="c-metadata__section">
			<h3 class="c-metadata__subtitle">Departments</h3>
			<button class="btn badge badge-pill badge-secondary btn_dep">dico <i class="fal fa-plus"></i></button>
			<button class="btn badge badge-pill badge-secondary btn_dep">dios <i class="fal fa-plus"></i></button>
			<button class="btn badge badge-pill badge-secondary btn_dep">dist <i class="fal fa-plus"></i></button>
			<button class="btn badge badge-pill badge-secondary btn_dep">dipo <i class="fal fa-plus"></i></button>
			<button class="btn badge badge-pill badge-secondary btn_dep">dise <i class="fal fa-plus"></i></button>
		</div>

		<!-- TYPE  -->
		<div class="c-metadata__section">
			<h3 class="c-metadata__subtitle">Type</h3>
			<button class="btn badge badge-pill badge-secondary btn_type">post <i class="fal fa-plus"></i></button>
			<button class="btn badge badge-pill badge-secondary btn_type">get <i class="fal fa-plus"></i></button>
		</div>

	</div>
</div>

<script>var input = document.querySelectorAll('input');
for(i=0; i<input.length; i++){
    input[i].setAttribute('size',input[i].getAttribute('placeholder').length);
}</script>

<!-- 
<div class="o-section c-table ">
	<div>
		<h3>Template</h3>

		<div style="margin:2rem 0; border:1px solid gray; padding: 2rem;">
			template gets loaded and edited here
		</div>

		<a href="save.php"class="btn btn-primary"><i class="far fa-save"></i> Save Template</a>
	</div>

</div> -->