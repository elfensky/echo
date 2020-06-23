//ON FILE UPLOAD, SUBMIT FORM TO SELF
document.getElementById('file').addEventListener('change', function() { 
	document.upload.submit();
	MessageManager.show("<div class='btn btn-success'>Uploaded</div>");
}) 

//On click create, generate and post to create.php using ajax
$("#create").on("click", function () {
	create();
});

//fairly self-explanatory, similar to branch.js
function create(){
	let template = new Map();
	template.set("required", generate_required()); //from json.js
	template.set("structure", generate_structure()); //from json.js
	template = JSON.stringify(map_to_object(template), undefined, 0); //create actual template structure as it is saved in database
	
	let template_name = document.getElementById("template_name").value;
	let version_name = document.getElementById("version_name").value;
	let description = document.getElementById("description").value;
	let author = document.getElementById("author").value;
	let departments = JSON.stringify(get_selected_departments(), true);

	$.ajax({
		type: 'post',
		url: '../php/create.php',
		data: {template, template_name, version_name, description, author, departments},
		success: function(response) {
			MessageManager.show("<div class='btn btn-success'>created</div>");
			window.location.replace("../public/view.php?v=" + response);
		}
	});
}