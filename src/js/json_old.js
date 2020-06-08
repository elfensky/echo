// generate_and_display_preview();

$("input").on("change keyup paste click", function () {
	// generate_and_display_preview();
	output(syntax_highlight(generate_json()));
});

output(syntax_highlight(generate_json()));

function generate_json() {
	var json = new Map();
	var flat_rows = $("tr").slice(1);

	let parent_key = "";


	for (var i=0; i<flat_rows.length; i++){   
		
		let row = flat_rows[i]; //current row

		let key = row.childNodes[0].innerText; //key of current row (FLAT)
		let type = row.childNodes[1].innerText; //type of current row (FLAT)
		let uuid = $(row).attr("uuid").split(","); //array of uuids of current row
		let level = uuid.length-1; //indentation level of current row (FLAT)
		
		// let parent_key = key;
		let parent_uuid = uuid[level-1];
		console.log(parent_uuid);

		if(parent_uuid === undefined){

			if(type == "object"){
				// var temp_map = new Map();
				set_key_value(json, key, {});
				// parent_key = key;
				
			} else if(type == "array"){
				set_key_value(json, key, []);
				// parent_key = key;
				// console.log(parent);
		
			} else {
				let value = row.childNodes[2].childNodes[0].value;
				set_key_value(json, key, value);
			}
		}

		else{

			for(var o=i-1; o>=0; o--){

				let row2 = flat_rows[o];
				let uuid2 = $(row2).attr("uuid").split(" "); //array of uuids of current row
				let level2 = uuid2.length-1; //indentation level of current row (FLAT)

				
				// let level = uuid.length
				// if 
				// console.log(flat_rows[o]);

			}
			// let key2 = flat_rows[i-1].childNodes[0].innerText;
			// set_key_value(json, key2, "");

			// let value = row.childNodes[2].childNodes[0].value;
			// set_key_value(json, key, value);
		}

		// if(level == 0){
		// 	if(type == "object"){
		// 		set_key_value(json, key, {});
		// 		parent = key;
				
		// 	} else if(type == "array"){
		// 		set_key_value(json, key, []);
		// 		parent = key;
		// 		console.log(parent);

		// 	} else {
		// 		let value = row.childNodes[2].childNodes[0].value;
		// 		set_key_value(json, key, value);
		// 	}

		// } else if(level == 1){
		// 	for(let o = i; o>=0; o--){ let parent_row = flat_rows[i]; //current row

		// 	}
		// 	//find uuid of parent, get parent key, add yourself to parent. 

		// 	// let value = row.childNodes[2].childNodes[0].value;
		// 	// console.log(value);
		// 	// map.set(key, value);

		// 	// set_key_value(json, key, value);


		

		}

	// console.log(json);
	return json
	
}

function get_key_values(type, json, key, row){
	if(type == "object"){
		set_key_value(json, key, {});
		parent = key;
		
	} else if(type == "array"){
		set_key_value(json, key, []);
		parent = key;
		console.log(parent);

	} else {
		let value = row.childNodes[2].childNodes[0].value;
		set_key_value(json, key, value);
	}
}

function set_key_value(map, key, value){
	map.set(key, value);
}

function output(json) {
	document.getElementById("preview").innerHTML = json;
}

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