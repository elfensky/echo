//if html element with id branch (the button) is clicked, execute the branch() function
$("#branch").on("click", function () {
	branch();
});

//create a new entry in template_data
function branch(){
	
	let data_id = get_template_version(); //get template_data_id, the ?v=X
										  //this function further described in json.js

	let template = new Map(); //create an empty Map()
	template.set("required", generate_required()); 
	template.set("structure", generate_structure());
	template = JSON.stringify(map_to_object(template), undefined, 0); //create actual template structure as it is saved in database
	
	//get all the metadata values
	let template_name = document.getElementById("template_name").value;
	let version_name = document.getElementById("version_name").value;
	let description = document.getElementById("description").value;
	let author = document.getElementById("author").value;
	let departments = JSON.stringify(get_selected_departments(), true);
	
	let version_number = document.getElementById("version_number").innerHTML;
	let info_id = document.getElementById("info_id").innerHTML;
	
	//use Ajax to POST all the collected data to branch.php which will create the actual entry in the database.
	//On success redirect to the edit page of the newly created branch.
	$.ajax({
		type: 'post',
		url: '../php/branch.php',
		data: {data_id, template, template_name, version_name, description, author, departments, version_number, info_id},
		success: function(response) {
			MessageManager.show("<div class='btn btn-success'>created</div>");
			window.location.replace("../public/edit.php?v=" + response);
		}
	});
}