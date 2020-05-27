var $filter_height = $("#filter").outerHeight();
console.log($filter_height);

$("#btn_filter").click(function() {     
	$("#filter").toggleClass("c-filter--hidden");

	if ( $("#filter").hasClass("c-filter--hidden") )
		$("#filter").css("margin-top",-$filter_height)
	else
		$("#filter").removeAttr("style")
});