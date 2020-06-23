$("#update").on("click", function () {
	update();
});

function update(){
	//perform an update in the database
	let data_id = get_template_version(); //get template_data_id, the ?v=X

	let template = new Map();
	template.set("required", generate_required()); 
	template.set("structure", generate_structure());
	template = JSON.stringify(map_to_object(template), undefined, 0); //create actual template structure as it is saved in database
	
	let template_name = document.getElementById("template_name").value;
	let version_name = document.getElementById("version_name").value;
	let description = document.getElementById("description").value;
	let author = document.getElementById("author").value;
	let departments = JSON.stringify(get_selected_departments(), true);
	
	let version_number = document.getElementById("version_number").innerHTML;
	let info_id = document.getElementById("info_id").innerHTML;
	
	// console.log();

	$.ajax({
		type: 'post',
		url: '../php/update.php',
		data: {data_id, template, template_name, version_name, description, author, departments, version_number, info_id},
		success: function(response) {
			// alert(response);
			MessageManager.show("<div class='btn btn-success'>updated</div>");
			// MessageManager.show(response);
		}
	});
}