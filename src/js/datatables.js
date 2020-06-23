//initialize and start the datatables library
$(document).ready( function () {

	var table = $('#templates').DataTable({
			// "bFilter" : false,               
			// "bLengthChange": false,
			// "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
			"pageLength": 50,
			"columnDefs": [ {
					"searchable": false,
					"orderable": false,
					"targets": [0, 4, 5]
				} ],
			scroller:false,
			"order": [[ 1, 'asc' ]]
			// bFilter: false
	});

	//custom search for template_name https://datatables.net/reference/api/search()
	$('#search_by_name').on('keyup', function () {
		table.column(1).search($('#search_by_name').val()).draw();
	});

	// //custom search for authors https://datatables.net/reference/api/search()
	$('#search_by_author').on('keyup', function () {
		table.column(3).search($('#search_by_author').val()).draw();
	});

	// //custom search for departments https://datatables.net/reference/api/search()
	// $('#search_by_department').on('keyup', function () {
	// 	table.column(2).search($('#search_by_department').val()).draw();
	// });

	//custom rows-per-page selector. See https://datatables.net/reference/api/page.len()
	$('#templates_length').change(function() {
		table.page.len(this.value).draw();
	})

	//generated counter_columns https://datatables.net/examples/api/counter_columns.html 
	table.on( 'order.dt search.dt', function () {
		table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
		cell.innerHTML = i+1;
	});
	}).draw();
});