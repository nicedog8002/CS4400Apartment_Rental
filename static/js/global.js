$(document).ready(
	function() {
		$('.datepicker').datepicker({ dateFormat: "yy-mm-dd", minDate: new Date()});
	}
);

var SITE_BASE = '/CS4400Apartment_Rental/';
var AJAX_BASE = SITE_BASE + 'ajax/';