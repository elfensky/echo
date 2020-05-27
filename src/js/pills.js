$(".btn_dep").click(function() {     
	$(this).toggleClass("badge-primary");
	$(this).toggleClass("badge-secondary");

	//remove all
	$("#btn_all").removeClass("badge-primary");
	$("#btn_all").addClass("badge-secondary");
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