$(".input-key").on("change keyup paste click", function () {
	//when a key is changed, change keys attribute
	edit_keys(this);
	//when a key is changed, regenerate preview
	output(syntax_highlight(generate_structure()));
});

$(".input-value").on("change keyup paste click", function () {
	//when a value is changed, regenerate preview
	output(syntax_highlight(generate_structure()));
});

$("select").on("change keyup paste click", function () {
	//when a type is changed, regenerate preview.
	output(syntax_highlight(generate_structure()));
});

//on page load, generate preview. 
output(syntax_highlight(generate_structure()));

//edit the keys attribute when changing the key values in the editor
function edit_keys(clicked) {
	let row = clicked.parentNode.parentNode.parentNode; //select the parent row based on the input
	let keys = row.getAttribute("keys").split(","); //get keys attribute and split into array
	let level = parseInt(row.getAttribute("level")); //get level attribute and parse as Int
	keys[level] = clicked.value; //set key value at level position to new value
	keys = keys.join(); //array to string
	row.setAttribute("keys", keys); //set new attribute value

	if(row.classList.contains("toggle")){ 
		//some extra work is required if the row is an object or array with children,
		//as the children also need their keys attribute changed to match the new parent
		let elem = row.nextElementSibling; //select the next Sibling

		while (elem) {
			let sibling_level = parseInt(elem.getAttribute("level")); //get sibling level

			// If if the sibling is the same level (and thus is not a child anymore), break
			if (sibling_level == level) break;
	
			// Otherwise, do the same as before, get the keys attribute, set to new value and put it back. 
			let sibling_keys = elem.getAttribute("keys").split(",");
			sibling_keys[level] = clicked.value;
			sibling_keys = sibling_keys.join();
			elem.setAttribute("keys", sibling_keys);
	
			// Get the next sibling element, so the while loop continues. 
			elem = elem.nextElementSibling;
		}
	}
}

//generate the json for the template itself
function generate_structure() {
	let json = new Map();
	let tbl = $("tr").slice(1); //array of all rows
	// var parent_keys = []; //array of parent keys

	//for every row, do things. 
	for (i=0; i<tbl.length; i++){
		let row = tbl[i];

		//key of current row (FLAT)
		let e = row.childNodes[1].childNodes[0];
		let key = "";
		
		//depending on whether the user is on the view page or edit page, there is a different way to get the keys
		if(e.getElementsByTagName("INPUT").length > 0){
			key = e.getElementsByTagName("INPUT")[0].value;

		} else {
			key = row.childNodes[1].innerText;
		}

		let type = "";

		e = row.childNodes[2].childNodes[0];

		//depending on whether the user is on the view page or edit page, there is a different way to get the type.
		if(e.tagName == "SELECT"){
			type = e.options[e.selectedIndex].text;

		} else {
			type = e.innerText;
		}

		//get the row attributes and if multiple split into an array
		let level = $(row).attr("level");
		let parent_keys = $(row).attr("keys").split(","); parent_keys.pop();

		//if the row is nested
		if(level > 0){

			let temp_data = null;
			temp_data = get_data(json, level);

			if(temp_data instanceof Map){
				temp_data.set(key, get_value(row, type));
			}

			if(temp_data instanceof Array){
				temp_data.push(get_value(row, type))
			}		

			function get_data(map, level) {
				var workingCopy = null;
				var i = 0;
				while (i != level) {
				  if (i == 0) {
					workingCopy = map.get(parent_keys[0]);
				  } else {
					workingCopy = workingCopy.get(parent_keys[i]);
				  }
				  i++;
				}
				return workingCopy;
			}
		}
		
		else{
			//if it's not nested, just put the key and value into the Map.
			json.set(key, get_value(row, type))
		}		
	}

	return json
}

//generate json array that contains required keys
function generate_required() {
	let required = new Array();
	let tbl = $("tr").slice(1);

	for (i=0; i<tbl.length; i++){
		if(tbl[i].childNodes[0].childNodes[0].childNodes[1].checked == true){
			if(tbl[i].classList.contains("toggle")){
				required.push(tbl[i].childNodes[1].childNodes[0].childNodes[1].value);
			} else {
				required.push(tbl[i].childNodes[1].childNodes[0].childNodes[0].value);
			}
		}
	}
	
	return required;
}

//get a typecast value of each row, based on the select choise
function get_value(row, type){
	if(type == "object"){
		return new Map();
		// map.set(key, temp_map);
		
	} else if(type == "array"){
		return new Array();
		// map.set(key, temp_array);

	} else {
		let value = null;

		//accounts for the differences in structure between the view and edit pages
		if(row.childNodes[3].childNodes[0].tagName == "INPUT"){
			// console.log()
			value = row.childNodes[3].childNodes[0].value;
		} else{
			value = row.childNodes[3].childNodes[0].innerText;
		}
		
		if(type == "number"){
			value = parseFloat(value);

			if(value % 1 == 0){
				value = parseInt(value);
			}
		}

		if(type == "null"){
			value = null;
		}

		if(type == "boolean"){
			// parseInt(value);
			if(value >= 0.5 || value == "true"){
				value = true;
			} else { value = false; }
		}

		// let value = row.childNodes[2].childNodes[0].value;
		return value;
	}
}

//set innerHTML of the preview window to the passed along json
function output(json) {
	document.getElementById("preview").innerHTML = json;
}

//convert map (used to generate template) to a javascript object
function map_to_object(map) {
	const out = Object.create(null)
	map.forEach((value, key) => {
	if (value instanceof Map) {
		out[key] = map_to_object(value)
	}
	else {
		out[key] = value
	}
	})
	return out
}

//syntax highlighting for the json
function syntax_highlight(json) {
	json = JSON.stringify(map_to_object(json), undefined, 4);
	json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
	return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
		var cls = 'number';
		if (/^"/.test(match)) {
			if (/:$/.test(match)) {
				cls = 'key';
			} else {
				cls = 'string';
			}
		} else if (/true|false/.test(match)) {
			cls = 'boolean';
		} else if (/null/.test(match)) {
			cls = 'null';
		}
		return '<span class="' + cls + '">' + match + '</span>';
	});
}

//get template version from url. This is reduntant and should be replaced, as the template version is passed along with a hidden 
//div in the body, and should be selected using document.getElementById("version_number").innerHTML;
function get_template_version(){
	let params = window.$_GET = location.search.substr(1).split("&").reduce((o,i)=>(u=decodeURIComponent,[k,v]=i.split("="),o[u(k)]=v&&u(v),o),{});
	return params["v"];
}

//on pages like new or edit, get and return which departments have been selected
function get_selected_departments(){
	let departments_all = $(".u-pill");
	let departments_selected = [];

	for (let i = 0; i < departments_all.length; i++) {

		if(departments_all[i].childNodes[0].checked == true){
			departments_selected.push(departments_all[i].childNodes[0].value);
		}
		
	}
	
	return departments_selected;
}

var MessageManager = {
    show: function(content) {
        $('#message').html(content);
        setTimeout(function(){
            $('#message').html('');
        }, 5000);
    }
};
