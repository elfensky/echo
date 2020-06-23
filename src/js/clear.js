$("#clear").on("click", function () {
	clear();
});

function clear(){
	//clear all input elements, set value to nothing
	let elements = document.getElementsByTagName("input");
	for (let i=0; i < elements.length; i++) {
		if (elements[i].type == "text") {
			elements[i].value = "";
		}
	}

	//remove everything inside tbody (removes all rows)
	elements = document.getElementsByTagName("tbody");
	elements[0].innerHTML = "";

	//regenerate the preview (function is described in detail in json.js)
	output(syntax_highlight(generate_structure()));
}

