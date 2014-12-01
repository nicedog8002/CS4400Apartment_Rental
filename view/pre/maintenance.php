<?php 
// The keys of the $_POST object are based on the "name" attribute
// of the HTML forms which submit to this page
$Issue_Type = $_POST['issue'];
$Apt_No = $_POST['apt_no'];

if ($_POST['submit']) {
	// db() is a custom function written to abstract PHP queries
	$query = "INSERT INTO Maintenance_Request (Date_Of_Request, Apt_No, Issue_Type, Issue_Status)
				VALUES (NOW(), $Apt_No, '$Issue_Type', 'unresolved')";
	if (db()->query($query)) {
		$_SESSION['notice'] = "Your maintenance request for Apartment $Apt_No 
			has been submitted. ";
		redirect('home');
		exit; 
	} else {
		$_SESSION['error'] = "You've already submitted a maintenance request of that type today!";
		redirect('maintenance');
		exit; 
	}
}
?>