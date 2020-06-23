$('.u-collapse').click(function(){

	//Ideally I would use cookies to create persistance, and save collapsed/visible states,
	//allowing to keep hidden child-elements to stay hidden when you open their parent,
	//but that is for later as it will require large changes in the php code that generates the template from database data

	// let v = get_template_version(); //get the value of the "v" GET parameter
	// // create_new_parents_cookie(v);
	// let cookie = get_cookie(v); //get cookie with name of "v"
	// let parents_object = JSON.parse(cookie); //change cookie's json into object
	// let key = this.getAttribute("keys").split(",").pop(); //get key of clicked parent
	
	//get row of pressed chevron
	let row = this.parentNode.parentNode.parentNode;

	//get level and state of pressed parent
	let level = row.getAttribute("level");
	let state = row.getAttribute("state");

	//version1, not optimized, kinda bad code
	//select all siblings following the pressed item
	// let next_siblings = $(row).nextAll();

	// for (let sibling of next_siblings) {
	// 	//get sibling level and state
	// 	let sibling_level = sibling.getAttribute("level");
	// 	let sibling_state = sibling.getAttribute("state");

	// 	//if sibling level is larger (it's nested deeper than (=under) the parent)
	// 	if(sibling_level > level){

	// 		if(state == "visible"){
	// 			//if the parent is it's currently visible, set siblings to hidden
	// 			sibling.classList.add("u-hidden");
	// 		} else{
	// 			//otherwise, display siblings by removing the u-hidden class
	// 			sibling.classList.remove("u-hidden");

	// 			//if sibling is a parent that is hidden, unhide it and reset its classes etc
	// 			if(sibling_state == "hidden"){
	// 				sibling.setAttribute("state", "visible");
	// 				sibling.children[1].getElementsByTagName("i")[0].classList.add("fa-chevron-down")
	// 				sibling.children[1].getElementsByTagName("i")[0].classList.remove("fa-chevron-right")
	// 			}
	// 		}

	// 	} else {
	// 		break;
	// 	}
		
	// }1
	
	//version2, more optimized
	let sibling = row.nextElementSibling; //select the next Sibling

	while (sibling) {
		let sibling_level = parseInt(sibling.getAttribute("level")); //get sibling level
		let sibling_state = sibling.getAttribute("state");
		// If if the sibling is the same level (and thus is not a child anymore), break
		if (sibling_level == level) break;

		
		if(state == "visible"){
			//if the parent is it's currently visible, set siblings to hidden
			sibling.classList.add("u-hidden");
		} else{
			//otherwise, display siblings by removing the u-hidden class
			sibling.classList.remove("u-hidden");
	
			//if sibling is a parent that is hidden, unhide it and reset its classes etc
			if(sibling_state == "hidden"){
				sibling.setAttribute("state", "visible");
				sibling.children[1].getElementsByTagName("i")[0].classList.add("fa-chevron-down")
				sibling.children[1].getElementsByTagName("i")[0].classList.remove("fa-chevron-right")
			}
		}

		// Get the next sibling element, so the while loop continues. 
		sibling = sibling.nextElementSibling;
	}

	//set row attributes 
	if(state == "visible"){
		row.setAttribute("state", "hidden");
		row.children[1].getElementsByTagName("i")[0].classList.add("fa-chevron-right")
		row.children[1].getElementsByTagName("i")[0].classList.remove("fa-chevron-down")
	} else {
		row.setAttribute("state", "visible");
		row.children[1].getElementsByTagName("i")[0].classList.add("fa-chevron-down")
		row.children[1].getElementsByTagName("i")[0].classList.remove("fa-chevron-right")
	}
});

//unused functions that I planned to use to store the hidden/shown state of the templates so it would persist across page reloads
function create_new_parents_cookie(cname){
	//get all toggleable parents and put them in an HTMLcollection
	let parents = document.getElementsByClassName("toggle");
	let parents_object = new Object();

	//ES6 code, not possible on IE
	//Fill parents_object with key (name of toggle) and value (state, whether it's visible or hidden) pairs
	Array.from(parents).forEach((el) => {
		parents_object[el.getAttribute("keys").split(",").pop()] = el.getAttribute("state");
	});

	//convert object to json string
	let parents_json = JSON.stringify(parents_object);
	//figure out current template version (using GET);

	var today = new Date();
	var nextweek = new Date(today.getFullYear(), today.getMonth(), today.getDate()+7).toUTCString();

	// set cookie. 
	document.cookie = cname + "=" + parents_json + ";SameSite=strict;expires=" + nextweek;
}

function get_cookie(cname) {
	var name = cname + "=";
	var decodedCookie = decodeURIComponent(document.cookie);
	var ca = decodedCookie.split(';');
	for(var i = 0; i <ca.length; i++) {
	  var c = ca[i];
	  while (c.charAt(0) == ' ') {
		c = c.substring(1);
	  }
	  if (c.indexOf(name) == 0) {
		return c.substring(name.length, c.length);
	  }
	}
	return "";
}

