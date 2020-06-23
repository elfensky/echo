$("#delete_edit").on("click", function () {
	console.log("delete from edit")
	delete_version("edit");
});

$("#delete_view").on("click", function () {
	console.log("delete from view");
	delete_version("view");
});

function delete_version(page){
	data_id = get_template_version(); 
	info_id = document.getElementById("info_id").innerHTML;
	let author;

	//this is neccesary because of the slightly different nesting on the view and edit pages. 
	//with some html optimization this should be avoidable. 
	if(page == "edit") {
		author = document.getElementById("author").value;
	}

	if(page == "view"){
		author = document.getElementById("author").innerHTML;
	}
	
	console.log(data_id, info_id, author);

	$.ajax({
		type: 'post',
		url: '../php/delete.php',
		data: {data_id, info_id, author},
		success: function(response) {
			console.log(response);
			MessageManager.show("<div class='btn btn-success'>deleted</div>");
			window.location.replace("../public/view.php?id=" + response);
		}
	});
}