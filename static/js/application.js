$(document).ready(function() {

	$('a').click(function() {
		var exit = confirm("Are you sure you want to navigate away? " 
			+ "Your account with be deleted if you don't complete your application. ");
		if (exit) {
			$.ajax({
			  url: AJAX_BASE + "delete_user",
			})
		}
		return exit;
	});
});