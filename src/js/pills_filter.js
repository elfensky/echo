let array = [];

$(".btn_dep").click(function() {     
	$(this).toggleClass("badge-primary");
	$(this).toggleClass("badge-secondary");
	array.push($(this).text().trim());
	
	// $("#search_by_department").val() = $(this).text();

	// table.column(2).search(array.join(" ")).draw();

	//remove all
	$("#btn_all").removeClass("badge-primary");
	$("#btn_all").addClass("badge-secondary");

	console.log(array);
	// array.forEach(element => {
	// 	console.log(element);
	// });
});

$("#btn_all").click(function() {
	$(this).toggleClass("badge-primary");
	$(this).toggleClass("badge-secondary");

	$(".btn_dep").removeClass("badge-primary");
	$(".btn_dep").addClass("badge-secondary");
})





$(".btn_type").click(function() {     
	$(this).toggleClass("badge-primary");
	$(this).toggleClass("badge-secondary");
});