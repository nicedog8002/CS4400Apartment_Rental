$(document).ready(
	function() {
		var now = new Date();

		$('.datepicker').datepicker({dateFormat: "yy-mm-dd", minDate: now});
		$('#prefMoveDate').datepicker({ 
				dateFormat: "yy-mm-dd", 
				minDate: now,
				maxDate: "+2m"
			});
	}
);

var SITE_BASE = '/CS4400Apartment_Rental/';
var AJAX_BASE = SITE_BASE + 'ajax/';