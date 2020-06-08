//CHECKBOX INSTEAD OF BUTTON
$('.o-pill__checkbox').change(function(){
	if (this.checked){
		//change bg color
		$(this).parent().addClass("badge-primary");
		$(this).parent().removeClass("badge-secondary");
		//use custom color
		// $(this).parent().css("background-color", this.name);
		//change symbol
		$(this).siblings().addClass("fa-minus");
		$(this).siblings().removeClass("fa-plus");
	}
	
	else{
		$(this).parent().removeClass("badge-primary");
		$(this).parent().addClass("badge-secondary");
		// $(this).parent().css("background-color", "#E8E8E8");

		$(this).siblings().removeClass("fa-minus");
		$(this).siblings().addClass("fa-plus");
	}
})