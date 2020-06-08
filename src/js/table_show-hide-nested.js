$('.toggle').click(function(){
	var uuid_str = $(this).attr("keys");
	// console.log(uuid);
	var last_value = uuid_str.split(",").slice(-1).pop();
	// console.log(last_value);

	$(this).find('i').toggleClass("fa-chevron-right");
	$(this).find('i').toggleClass("fa-chevron-down");

	$("[uuid~=" + last_value + "]")
	.slice(1) //remove first element//
	// .find('div') //select all divs
	.toggle();
	// .animate({height: "toggle"}, {padding: "toggle"}) //toggle animate height form 0 to auto
	//tr contents should be wrapped in a div, and transition opacity 0 and height 0
});