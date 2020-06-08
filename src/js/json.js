$("input").on("change keyup paste click", function () {
	// generate_and_display_preview();
	output(syntax_highlight(generate_json()));
});

output(syntax_highlight(generate_json()));



function generate_json() {
	var json = new Map();
	var tbl = $("tr").slice(1); //array of all rows
	// var parent_keys = []; //array of parent keys


	for (i=0; i<tbl.length; i++){
		let row = tbl[i];

		let key = row.childNodes[0].innerText; //key of current row (FLAT)
		let type = row.childNodes[1].innerText; //type of current row (FLAT)
		let level = $(row).attr("level");

		let parent_keys = $(row).attr("keys").split(","); parent_keys.pop();

		
		if(level > 0){

			if(level == 1){
				let temp_data = json.get(parent_keys[0]);
				// console.log(temp_data);

				if(temp_data instanceof Map){
					temp_data.set(key, get_value(row, type));
				}

				if(temp_data instanceof Array){
					temp_data.push(get_value(row, type))
				}				
			}

			if(level == 2){
				let temp_data = json.get(parent_keys[0]).get(parent_keys[1]);
				// temp_data = temp_data.get(parent_keys[1]);
				// console.log(temp_map);

				if(temp_data instanceof Map){
					temp_data.set(key, get_value(row, type));
				}

				if(temp_data instanceof Array){
					temp_data.push(get_value(row, type))
				}
			}

			// temp_data = recursive_temp_data(level);

			// function recursive_temp_data(lvl){
				

			// 	for(var o = 0; o < lvl; o++) {
			// 		let inner_data = json.get(parent_keys[lvl-1]);
			// 		console.log(inner_data);

			// 		return inner_data;
			// 	}	
			// }

			// if(temp_data instanceof Map){
			// 	temp_data.set(key, get_value(row, type));
			// }
	
			// if(temp_data instanceof Array){
			// 	temp_data.push(get_value(row, type))
			// }
		}
		
		else{
			json.set(key, get_value(row, type))
			// set_key_value_pairs(row, type, key, json);
		}

		
	}

	// console.log(json);
	return json
}

function get_value(row, type){
	if(type == "object"){
		return new Map();
		// map.set(key, temp_map);
		
	} else if(type == "array"){
		return new Array();
		// map.set(key, temp_array);

	} else {
		let value = row.childNodes[2].childNodes[0].value;
		return value;
	}
}










// function set_key_value_pairs(row, type, key, map){
// 	if(type == "object"){
// 		map.set(key, new Map());
		
// 	} else if(type == "array"){
// 		map.set(key, new Array());

// 	} else {
// 		let value = row.childNodes[2].childNodes[0].value;
// 		map.set(key, value);
// 	}
// }






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