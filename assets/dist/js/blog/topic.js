$(document).ready(function() {
	$('table.topic-list-box').DataTable({
		"pageLength": 12,
		"lengthMenu": [[12, 21, 48, -1], [12, 21, 48, "All"]],
		"order": [[0, 'desc']]
	});
});
